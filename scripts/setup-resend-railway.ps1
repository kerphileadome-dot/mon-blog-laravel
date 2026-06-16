# Configure Resend sur Railway (plan Hobby — SMTP bloqué).
# Prérequis : railway login + railway link
#
# Usage :
#   1. Créez une clé API sur https://resend.com/api-keys
#   2. Ajoutez RESEND_API_KEY=re_... dans .env (ou passez-la en argument)
#   3. powershell -ExecutionPolicy Bypass -File scripts/setup-resend-railway.ps1
#
# Paramètre optionnel : -FromAddress "contact@votredomaine.com" (domaine vérifié sur Resend)

param(
    [string]$FromAddress = "",
    [string]$ApiKey = ""
)

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

$resendKey = $ApiKey
if ([string]::IsNullOrWhiteSpace($resendKey)) {
    $resendKey = Read-DotEnvValue ".env" "RESEND_API_KEY"
}
if ([string]::IsNullOrWhiteSpace($resendKey)) {
    $resendKey = Read-Host "RESEND_API_KEY (commence par re_)"
}

$resendKey = $resendKey.Trim()
if (-not $resendKey.StartsWith("re_")) {
    Write-Host "Clé Resend invalide (doit commencer par re_)" -ForegroundColor Red
    exit 1
}

if ([string]::IsNullOrWhiteSpace($FromAddress)) {
    $FromAddress = Read-DotEnvValue ".env" "MAIL_FROM_ADDRESS"
}
if ([string]::IsNullOrWhiteSpace($FromAddress)) {
    $FromAddress = Read-Host "MAIL_FROM_ADDRESS (ex: contact@kerphileblog.com — domaine vérifié Resend)"
}

$FromAddress = $FromAddress.Trim().Trim('"')

$railway = Join-Path $projectRoot "node_modules\.bin\railway.cmd"
if (-not (Test-Path $railway)) {
    Write-Host "Railway CLI absent. Lancez : npm install" -ForegroundColor Yellow
    exit 1
}

& $railway whoami 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "Connectez-vous :" -ForegroundColor Yellow
    Write-Host "  node_modules\.bin\railway.cmd login --browserless" -ForegroundColor White
    Write-Host "  node_modules\.bin\railway.cmd link" -ForegroundColor White
    exit 1
}

$vars = @{
    MAIL_MAILER       = "resend"
    RESEND_API_KEY    = $resendKey
    MAIL_FROM_ADDRESS = $FromAddress
    MAIL_FROM_NAME    = "KerpheX Blog"
    MAIL_SSL_VERIFY   = "false"
}

foreach ($key in $vars.Keys) {
    $value = $vars[$key]
    $display = if ($key -match 'KEY|PASSWORD') { "********" } else { $value }
    Write-Host ">> $key = $display" -ForegroundColor Cyan
    & $railway variables set "${key}=${value}" --skip-deploys 2>&1 | Out-Null
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Echec pour $key — railway link ?"
    }
}

Write-Host ""
Write-Host "Variables Resend envoyees sur Railway." -ForegroundColor Green
Write-Host "Redeployez le service, puis testez :" -ForegroundColor Cyan
Write-Host "  https://web-production-c5c2f.up.railway.app/admin/mail-diagnostic?key=kerphex-panel-8f3a2c" -ForegroundColor White
