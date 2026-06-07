# Collez les secrets depuis Railway Dashboard -> Variables (sans Railway CLI).
# Usage : powershell -ExecutionPolicy Bypass -File scripts/apply-secrets-manual.ps1

$ErrorActionPreference = "Stop"
Set-Location (Split-Path -Parent $PSScriptRoot)

function Format-DotEnvValue($value) {
    if ($value -match '[\s#"]') { return '"' + ($value -replace '"', '\"') + '"' }
    return $value
}

function Set-DotEnvValue($file, $key, $value) {
    $formatted = Format-DotEnvValue $value
    $lines = @(); $found = $false
    if (Test-Path $file) {
        foreach ($line in Get-Content $file) {
            if ($line -match "^\s*$key\s*=") { $lines += "$key=$formatted"; $found = $true }
            else { $lines += $line }
        }
    }
    if (-not $found) { $lines += "$key=$formatted" }
    Set-Content -Path $file -Value $lines -Encoding UTF8
}

Write-Host "=== Secrets manuels (Railway Dashboard) ===" -ForegroundColor Cyan
Write-Host "Ouvrez https://railway.app -> votre projet -> Variables" -ForegroundColor Gray
Write-Host ""

$googleSecret = Read-Host "GOOGLE_CLIENT_SECRET"
$mailPassword = Read-Host "MAIL_PASSWORD (mot de passe application Gmail)"

if ([string]::IsNullOrWhiteSpace($googleSecret) -or [string]::IsNullOrWhiteSpace($mailPassword)) {
    Write-Host "Annule : les deux valeurs sont requises." -ForegroundColor Red
    exit 1
}

Set-DotEnvValue "google.local.env" "GOOGLE_CLIENT_ID" "838121924906-8qr1ttd3hje99j24ka5pdievaohpvgsh.apps.googleusercontent.com"
Set-DotEnvValue "google.local.env" "GOOGLE_CLIENT_SECRET" $googleSecret
Set-DotEnvValue ".env" "GOOGLE_CLIENT_ID" "838121924906-8qr1ttd3hje99j24ka5pdievaohpvgsh.apps.googleusercontent.com"
Set-DotEnvValue ".env" "GOOGLE_CLIENT_SECRET" $googleSecret
Set-DotEnvValue ".env" "MAIL_PASSWORD" $mailPassword

powershell -ExecutionPolicy Bypass -File scripts/setup-local-env.ps1
Write-Host "Secrets appliques." -ForegroundColor Green
