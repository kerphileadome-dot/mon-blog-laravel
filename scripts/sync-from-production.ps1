# Synchronisation production → local (lecture seule, ne modifie jamais la prod).
# Usage : powershell -ExecutionPolicy Bypass -File scripts/sync-from-production.ps1
#
# Méthode 1 (recommandée) : HTTP via SYNC_EXPORT_TOKEN (après déploiement sur Railway)
# Méthode 2 : Railway CLI (railway login + railway link)
# Méthode 3 : Fichier manuel database/imports/production.sqlite

$ErrorActionPreference = "Stop"
$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

function Read-DotEnvValue($file, $key) {
    if (-not (Test-Path $file)) { return $null }
    foreach ($line in Get-Content $file) {
        if ($line -match "^\s*$key\s*=\s*(.+)\s*$") {
            $val = $Matches[1].Trim()
            $val = $val -replace '^["'']|["'']$', ''
            return $val
        }
    }
    return $null
}

function Format-DotEnvValue($value) {
    if ($value -match '[\s#"]') {
        $escaped = $value -replace '"', '\"'
        return '"' + $escaped + '"'
    }
    return $value
}

function Set-DotEnvValue($file, $key, $value) {
    $formatted = Format-DotEnvValue $value
    $lines = @()
    $found = $false
    if (Test-Path $file) {
        foreach ($line in Get-Content $file) {
            if ($line -match "^\s*$key\s*=") {
                $lines += "$key=$formatted"
                $found = $true
            } else {
                $lines += $line
            }
        }
    }
    if (-not $found) {
        $lines += "$key=$formatted"
    }
    Set-Content -Path $file -Value $lines -Encoding UTF8
}

Write-Host ">> Sync PRODUCTION -> LOCAL (KerpheX Blog)" -ForegroundColor Cyan
Write-Host ""

# 1. Aligner l'environnement local sur la prod (sauf APP_URL)
$prodUrl = "https://web-production-c5c2f.up.railway.app"
Set-DotEnvValue ".env" "PRODUCTION_URL" $prodUrl
Set-DotEnvValue ".env" "DB_CONNECTION" "sqlite"
Set-DotEnvValue ".env" "LOG_LEVEL" "error"
Set-DotEnvValue ".env" "MAIL_MAILER" "smtp"
Set-DotEnvValue ".env" "MAIL_HOST" "smtp.gmail.com"
Set-DotEnvValue ".env" "MAIL_PORT" "587"
Set-DotEnvValue ".env" "MAIL_USERNAME" "kerphilesaint@gmail.com"
Set-DotEnvValue ".env" "MAIL_ENCRYPTION" "tls"
Set-DotEnvValue ".env" "MAIL_FROM_ADDRESS" "kerphilesaint@gmail.com"
Set-DotEnvValue ".env" "MAIL_FROM_NAME" "KerpheX Blog"
Set-DotEnvValue ".env" "ADMIN_EMAILS" "kerphilesaint@gmail.com,kerphileadome@gmail.com"

$syncToken = Read-DotEnvValue ".env" "SYNC_EXPORT_TOKEN"
if ([string]::IsNullOrWhiteSpace($syncToken)) {
    $syncToken = [guid]::NewGuid().ToString("N")
    Set-DotEnvValue ".env" "SYNC_EXPORT_TOKEN" $syncToken
    Write-Host ">> SYNC_EXPORT_TOKEN genere localement." -ForegroundColor Yellow
    Write-Host "   Ajoutez la MEME valeur dans Railway -> Variables, puis redeployez." -ForegroundColor Yellow
    Write-Host "   SYNC_EXPORT_TOKEN=$syncToken" -ForegroundColor Gray
}

powershell -ExecutionPolicy Bypass -File scripts/setup-local-env.ps1 | Out-Null

# 2. Secrets Railway (Google, MAIL_PASSWORD) si CLI connectee
$railway = Join-Path $projectRoot "node_modules\.bin\railway.cmd"
if (Test-Path $railway) {
    & $railway whoami 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Write-Host ">> Variables Railway -> .env" -ForegroundColor Cyan
        powershell -ExecutionPolicy Bypass -File scripts/sync-env-from-railway.ps1
    } else {
        Write-Host ">> Railway CLI non connectee (optionnel pour secrets)." -ForegroundColor DarkYellow
        Write-Host "   Lancez scripts/railway-login.cmd pour recuperer Google OAuth et MAIL_PASSWORD." -ForegroundColor DarkYellow
    }
}

# 3. Images versionnees (fallback si export HTTP indisponible)
Write-Host ">> Images de couverture depuis Git" -ForegroundColor Cyan
git checkout HEAD -- storage/app/public/covers/ 2>$null

# 4. Import base SQLite
$dbImported = $false

Write-Host ">> Import base SQLite depuis la production" -ForegroundColor Cyan

php artisan blog:sync-from-production --no-interaction 2>&1 | ForEach-Object { Write-Host $_ }
if ($LASTEXITCODE -eq 0) {
    $dbImported = $true
}

if (-not $dbImported -and (Test-Path $railway)) {
    & $railway whoami 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Write-Host ">> Tentative Railway SSH..." -ForegroundColor Cyan
        $backup = "database/database.sqlite.backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
        if (Test-Path "database/database.sqlite") {
            Copy-Item "database/database.sqlite" $backup
        }
        $b64 = & $railway ssh -- "base64 database/database.sqlite" 2>&1
        if ($LASTEXITCODE -eq 0 -and -not [string]::IsNullOrWhiteSpace($b64)) {
            $bytes = [Convert]::FromBase64String(($b64 -join "").Trim())
            [IO.File]::WriteAllBytes("$projectRoot/database/database.sqlite", $bytes)
            $dbImported = $true
            Write-Host "   Base importee via Railway SSH." -ForegroundColor Green
        }
    }
}

$manualDb = "database/imports/production.sqlite"
if (-not $dbImported -and (Test-Path $manualDb)) {
    $backup = "database/database.sqlite.backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
    if (Test-Path "database/database.sqlite") {
        Copy-Item "database/database.sqlite" $backup
    }
    Copy-Item $manualDb "database/database.sqlite" -Force
    $dbImported = $true
    Write-Host "   Base importee depuis $manualDb" -ForegroundColor Green
}

php artisan config:clear | Out-Null
php artisan cache:clear | Out-Null
php artisan storage:link 2>$null | Out-Null

Write-Host ""
if ($dbImported) {
    Write-Host "Sync terminee : donnees production copiees en local." -ForegroundColor Green
} else {
    Write-Host "Sync partielle : config + images OK, base SQLite non importee." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Pour finir l'alignement complet :" -ForegroundColor Yellow
    Write-Host "  1. Sur Railway -> Variables : SYNC_EXPORT_TOKEN=$syncToken" -ForegroundColor Cyan
    Write-Host "  2. git push (pour deployer les routes /internal/sync/*)" -ForegroundColor Cyan
    Write-Host "  3. Relancez ce script" -ForegroundColor Cyan
    Write-Host "  OU : scripts/railway-login.cmd puis relancez ce script" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "Local : http://mon-blog.test" -ForegroundColor Gray
Write-Host "Prod  : $prodUrl" -ForegroundColor Gray
