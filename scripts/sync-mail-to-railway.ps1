# Synchronise les variables MAIL vers Railway (production).
# Prérequis : npm + connexion Railway (railway login)
#
# Usage :
#   1. Renseignez MAIL_PASSWORD dans .env ou mail.secret.env
#   2. powershell -ExecutionPolicy Bypass -File scripts/sync-mail-to-railway.ps1

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
if ([string]::IsNullOrWhiteSpace($mailPassword)) {
    $mailPassword = Read-DotEnvValue "mail.secret.env" "MAIL_PASSWORD"
}

$vars = @{
    MAIL_MAILER       = "smtp"
    MAIL_HOST         = "smtp.gmail.com"
    MAIL_PORT         = "587"
    MAIL_USERNAME     = "kerphilesaint@gmail.com"
    MAIL_ENCRYPTION   = "tls"
    MAIL_FROM_ADDRESS = "kerphilesaint@gmail.com"
    MAIL_FROM_NAME    = "KerpheX Blog"
}

if (-not [string]::IsNullOrWhiteSpace($mailPassword)) {
    $vars["MAIL_PASSWORD"] = $mailPassword
}

$railway = Join-Path $projectRoot "node_modules\.bin\railway.cmd"
if (-not (Test-Path $railway)) {
    Write-Host "Railway CLI local introuvable. Lancez : npm.cmd install" -ForegroundColor Yellow
    exit 1
}

Write-Host ">> Verification connexion Railway..." -ForegroundColor Cyan
& $railway whoami 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "Non connecte. Lancez :" -ForegroundColor Yellow
    Write-Host "  node_modules\.bin\railway.cmd login --browserless" -ForegroundColor Yellow
    Write-Host "  node_modules\.bin\railway.cmd link" -ForegroundColor Yellow
    exit 1
}

foreach ($key in $vars.Keys) {
    $value = $vars[$key]
    $display = if ($key -eq "MAIL_PASSWORD") { "********" } else { $value }
    Write-Host ">> $key = $display" -ForegroundColor Cyan
    & $railway variables set "${key}=${value}" --skip-deploys 2>&1 | Out-Null
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Échec pour $key. Vérifiez que le projet est lié : railway link"
    }
}

Write-Host ""
if ([string]::IsNullOrWhiteSpace($mailPassword)) {
    Write-Host "Variables publiques envoyées. MAIL_PASSWORD manquant." -ForegroundColor Yellow
    Write-Host "Ajoutez dans .env : MAIL_PASSWORD=votre-mot-de-passe-application-gmail" -ForegroundColor Yellow
    Write-Host "Puis relancez ce script." -ForegroundColor Yellow
} else {
    Write-Host "Toutes les variables MAIL sont configurées sur Railway." -ForegroundColor Green
    Write-Host "Redéployez le service Railway pour appliquer les changements." -ForegroundColor Green
}
