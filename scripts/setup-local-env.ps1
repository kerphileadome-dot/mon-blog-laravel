# Configure l'environnement local (MAIL + Google OAuth + admin) comme la production.
# Usage : powershell -ExecutionPolicy Bypass -File scripts/setup-local-env.ps1

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

Write-Host ">> Configuration locale KerpheX Blog" -ForegroundColor Cyan

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host ">> .env cree depuis .env.example" -ForegroundColor Yellow
}

$appUrl = Read-DotEnvValue ".env" "APP_URL"
if ([string]::IsNullOrWhiteSpace($appUrl)) {
    $appUrl = "http://mon-blog.test"
}
$appUrl = $appUrl.TrimEnd('/')

Set-DotEnvValue ".env" "APP_URL" $appUrl
Set-DotEnvValue ".env" "ADMIN_EMAILS" "kerphilesaint@gmail.com"
Set-DotEnvValue ".env" "GOOGLE_REDIRECT_URI" "$appUrl/auth/google/callback"

$mailPassword = Read-DotEnvValue "mail.secret.env" "MAIL_PASSWORD"
if (-not [string]::IsNullOrWhiteSpace($mailPassword)) {
    Set-DotEnvValue ".env" "MAIL_PASSWORD" $mailPassword
}

Set-DotEnvValue ".env" "MAIL_MAILER" "smtp"
Set-DotEnvValue ".env" "MAIL_HOST" "smtp.gmail.com"
Set-DotEnvValue ".env" "MAIL_PORT" "587"
Set-DotEnvValue ".env" "MAIL_USERNAME" "kerphilesaint@gmail.com"
Set-DotEnvValue ".env" "MAIL_ENCRYPTION" "tls"
Set-DotEnvValue ".env" "MAIL_FROM_ADDRESS" "kerphilesaint@gmail.com"
Set-DotEnvValue ".env" "MAIL_FROM_NAME" "KerpheX Blog"

if (-not (Test-Path "google.local.env")) {
    if (Test-Path "google.local.env.example") {
        Copy-Item "google.local.env.example" "google.local.env"
        Write-Host ">> google.local.env cree - renseignez GOOGLE_CLIENT_ID et GOOGLE_CLIENT_SECRET" -ForegroundColor Yellow
    }
}

$googleId = Read-DotEnvValue "google.local.env" "GOOGLE_CLIENT_ID"
$googleSecret = Read-DotEnvValue "google.local.env" "GOOGLE_CLIENT_SECRET"

if (-not [string]::IsNullOrWhiteSpace($googleId) -and $googleId -notmatch "votre-id") {
    Set-DotEnvValue ".env" "GOOGLE_CLIENT_ID" $googleId
}
if (-not [string]::IsNullOrWhiteSpace($googleSecret) -and $googleSecret -notmatch "votre-secret") {
    Set-DotEnvValue ".env" "GOOGLE_CLIENT_SECRET" $googleSecret
}

php artisan config:clear | Out-Null
php artisan storage:link 2>$null | Out-Null

Write-Host ""
Write-Host "Variables appliquees :" -ForegroundColor Green
Write-Host "  APP_URL              = $appUrl"
Write-Host "  GOOGLE_REDIRECT_URI  = $appUrl/auth/google/callback"
Write-Host "  ADMIN_EMAILS         = kerphilesaint@gmail.com"
Write-Host "  MAIL                 = smtp / kerphilesaint@gmail.com"

if ([string]::IsNullOrWhiteSpace($googleId) -or $googleId -match "votre-id") {
    Write-Host ""
    Write-Host "Google OAuth : editez google.local.env avec vos cles Railway," -ForegroundColor Yellow
    Write-Host "puis ajoutez dans Google Cloud Console l URI local :" -ForegroundColor Yellow
    Write-Host "  $appUrl/auth/google/callback" -ForegroundColor Yellow
    Write-Host "et relancez ce script." -ForegroundColor Yellow
} else {
    Write-Host "  Google OAuth         = configure" -ForegroundColor Green
}

Write-Host ""
if ([string]::IsNullOrWhiteSpace($googleSecret) -or $googleSecret -match "votre-secret") {
    Write-Host ""
    Write-Host "Pour recuperer GOOGLE_CLIENT_SECRET depuis Railway :" -ForegroundColor Cyan
    Write-Host "  powershell -ExecutionPolicy Bypass -File scripts/sync-env-from-railway.ps1" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "Test email : php artisan blog:test-mail" -ForegroundColor Cyan
Write-Host "Checklist  : docs/CHECKLIST_TESTS_MANUEL.md" -ForegroundColor Cyan
