# Configure Google OAuth en local (lit google.local.env).
# Usage : powershell -ExecutionPolicy Bypass -File scripts/setup-google-oauth.ps1

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

Write-Host ">> Configuration Google OAuth (local)" -ForegroundColor Cyan

if (-not (Test-Path "google.local.env")) {
    if (Test-Path "google.local.env.example") {
        Copy-Item "google.local.env.example" "google.local.env"
        Write-Host ">> google.local.env cree — renseignez GOOGLE_CLIENT_ID et GOOGLE_CLIENT_SECRET" -ForegroundColor Yellow
    } else {
        Write-Error "google.local.env.example introuvable."
    }
}

$clientId = Read-DotEnvValue "google.local.env" "GOOGLE_CLIENT_ID"
$clientSecret = Read-DotEnvValue "google.local.env" "GOOGLE_CLIENT_SECRET"
$appUrl = Read-DotEnvValue ".env" "APP_URL"
if ([string]::IsNullOrWhiteSpace($appUrl)) {
    $appUrl = "http://mon-blog.test"
}
$appUrl = $appUrl.TrimEnd('/')

if ([string]::IsNullOrWhiteSpace($clientId) -or $clientId -match "votre-id") {
    Write-Host ""
    Write-Host "Etapes manuelles requises :" -ForegroundColor Yellow
    Write-Host "  1. https://console.cloud.google.com → Identifiants OAuth" -ForegroundColor Cyan
    Write-Host "  2. Editez google.local.env avec Client ID et Secret" -ForegroundColor Cyan
    Write-Host "  3. Ajoutez dans Google Console :" -ForegroundColor Cyan
    Write-Host "     Origine JS      : $appUrl" -ForegroundColor Gray
    Write-Host "     URI redirection : $appUrl/auth/google/callback" -ForegroundColor Gray
    Write-Host "     + prod          : https://web-production-c5c2f.up.railway.app/auth/google/callback" -ForegroundColor Gray
    Write-Host "  4. Relancez ce script" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Guide complet : CONFIGURATION_GOOGLE_OAUTH.md" -ForegroundColor Gray
    exit 1
}

Set-DotEnvValue ".env" "GOOGLE_CLIENT_ID" $clientId
Set-DotEnvValue ".env" "GOOGLE_CLIENT_SECRET" $clientSecret
Set-DotEnvValue ".env" "GOOGLE_REDIRECT_URI" "$appUrl/auth/google/callback"

php artisan config:clear | Out-Null

Write-Host ""
Write-Host "Google OAuth local configure." -ForegroundColor Green
Write-Host "  GOOGLE_REDIRECT_URI = $appUrl/auth/google/callback" -ForegroundColor Gray
Write-Host ""
Write-Host "Test : ouvrez $appUrl/login et cliquez « Se connecter avec Google »" -ForegroundColor Cyan
Write-Host ""
Write-Host "Pour Railway : ajoutez les memes GOOGLE_* dans Variables du service web." -ForegroundColor Yellow
