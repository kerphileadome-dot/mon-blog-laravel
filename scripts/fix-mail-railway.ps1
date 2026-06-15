# Corrige MAIL_PASSWORD sur Railway (sans espace, Gmail SMTP).
# Prérequis : railway login + railway link + MAIL_PASSWORD dans .env
# Usage : powershell -ExecutionPolicy Bypass -File scripts/fix-mail-railway.ps1

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

$mailPassword = Read-DotEnvValue ".env" "MAIL_PASSWORD"
if ([string]::IsNullOrWhiteSpace($mailPassword) -or $mailPassword -match 'votre-mot-de-passe') {
    $mailPassword = Read-DotEnvValue "mail.secret.env" "MAIL_PASSWORD"
}

if ([string]::IsNullOrWhiteSpace($mailPassword)) {
    Write-Host "MAIL_PASSWORD manquant dans .env ou mail.secret.env" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Corrigez MANUELLEMENT dans Railway -> Variables (service web) :" -ForegroundColor Cyan
    Write-Host "  MAIL_PASSWORD=ttexyezeadvcazev   (sans espace, sans guillemets)" -ForegroundColor White
    Write-Host "  MAIL_USERNAME=kerphilesaint@gmail.com" -ForegroundColor White
    Write-Host "  MAIL_FROM_ADDRESS=kerphilesaint@gmail.com" -ForegroundColor White
    Write-Host ""
    Write-Host "Puis redeployez le service." -ForegroundColor Yellow
    exit 1
}

$mailPassword = $mailPassword.Trim()

$railway = Join-Path $projectRoot "node_modules\.bin\railway.cmd"
if (-not (Test-Path $railway)) {
    Write-Host "Railway CLI absent. Corrigez manuellement (voir ci-dessus)." -ForegroundColor Yellow
    exit 1
}

& $railway whoami 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "Connectez-vous : node_modules\.bin\railway.cmd login" -ForegroundColor Yellow
    exit 1
}

$fixes = @{
    MAIL_MAILER       = "smtp"
    MAIL_HOST         = "smtp.gmail.com"
    MAIL_PORT         = "587"
    MAIL_USERNAME     = "kerphilesaint@gmail.com"
    MAIL_PASSWORD     = $mailPassword
    MAIL_ENCRYPTION   = "tls"
    MAIL_FROM_ADDRESS = "kerphilesaint@gmail.com"
    MAIL_FROM_NAME    = "KerpheX Blog"
}

foreach ($key in $fixes.Keys) {
    $value = $fixes[$key]
    $display = if ($key -eq "MAIL_PASSWORD") { "********" } else { $value }
    Write-Host ">> $key = $display" -ForegroundColor Cyan
    & $railway variables set "${key}=${value}" --skip-deploys 2>&1 | Out-Null
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Echec pour $key"
    }
}

Write-Host ""
Write-Host "Variables MAIL corrigees. Redeployez le service web sur Railway." -ForegroundColor Green
