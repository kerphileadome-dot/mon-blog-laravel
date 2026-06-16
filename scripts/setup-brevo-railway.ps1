# Configure Brevo sur Railway (plan Hobby — SMTP bloqué, API HTTPS OK).
# Prérequis : railway login + railway link
#
# Usage :
#   1. Compte gratuit https://www.brevo.com
#   2. Vérifiez un expéditeur (Senders) : kerphilesaint@gmail.com
#   3. API Keys → créez une clé xkeysib-...
#   4. powershell -ExecutionPolicy Bypass -File scripts/setup-brevo-railway.ps1

param(
    [string]$FromAddress = "kerphilesaint@gmail.com",
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

$brevoKey = $ApiKey
if ([string]::IsNullOrWhiteSpace($brevoKey)) {
    $brevoKey = Read-DotEnvValue ".env" "BREVO_API_KEY"
}
if ([string]::IsNullOrWhiteSpace($brevoKey)) {
    $brevoKey = Read-Host "BREVO_API_KEY (commence par xkeysib-)"
}

$brevoKey = $brevoKey.Trim()
if (-not $brevoKey.StartsWith("xkeysib-")) {
    Write-Host "Clé Brevo invalide (doit commencer par xkeysib-)" -ForegroundColor Red
    exit 1
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
    MAIL_MAILER       = "brevo"
    BREVO_API_KEY     = $brevoKey
    MAIL_FROM_ADDRESS = $FromAddress
    MAIL_FROM_NAME    = "KerpheX Blog"
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
Write-Host "Variables Brevo envoyees sur Railway." -ForegroundColor Green
Write-Host "Redeployez, puis testez :" -ForegroundColor Cyan
Write-Host "  https://web-production-c5c2f.up.railway.app/admin/mail-diagnostic?key=kerphex-panel-8f3a2c" -ForegroundColor White
