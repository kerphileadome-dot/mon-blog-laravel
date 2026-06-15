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
        Write-Host ">> google.local.env cree" -ForegroundColor Yellow
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

# Google n'accepte pas mon-blog.test : URI OAuth locale via 127.0.0.1
$googleRedirectUri = "$appUrl/auth/google/callback"
if ($appUrl -match "mon-blog\.test") {
    $googleRedirectUri = "http://127.0.0.1/mon_blog/public/auth/google/callback"
}

if ([string]::IsNullOrWhiteSpace($clientId) -or $clientId -match "votre-id") {
    Write-Host "GOOGLE_CLIENT_ID manquant dans google.local.env" -ForegroundColor Red
    exit 1
}

if ([string]::IsNullOrWhiteSpace($clientSecret) -or $clientSecret -match "votre-secret") {
    Write-Host ""
    Write-Host "GOOGLE_CLIENT_SECRET manquant dans google.local.env" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "  Option A - Copier depuis Railway :" -ForegroundColor Cyan
    Write-Host "    1. railway.app -> service web -> Variables -> GOOGLE_CLIENT_SECRET" -ForegroundColor Gray
    Write-Host "    2. Collez dans google.local.env" -ForegroundColor Gray
    Write-Host "    3. Relancez ce script" -ForegroundColor Gray
    Write-Host ""
    Write-Host "  Option B - Railway CLI : scripts\railway-login.cmd" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "  Google Cloud Console - URI locaux :" -ForegroundColor Cyan
    Write-Host "    Origine JS      : $appUrl" -ForegroundColor Gray
    Write-Host "    URI redirection : $appUrl/auth/google/callback" -ForegroundColor Gray
    Write-Host ""
    exit 1
}

Set-DotEnvValue ".env" "GOOGLE_CLIENT_ID" $clientId
Set-DotEnvValue ".env" "GOOGLE_CLIENT_SECRET" $clientSecret
Set-DotEnvValue ".env" "GOOGLE_REDIRECT_URI" $googleRedirectUri

php artisan config:clear | Out-Null

Write-Host ""
Write-Host "Google OAuth local configure." -ForegroundColor Green
Write-Host "  GOOGLE_REDIRECT_URI = $googleRedirectUri" -ForegroundColor Gray
Write-Host ""
Write-Host "Test local : http://127.0.0.1/mon_blog/public/login" -ForegroundColor Cyan
Write-Host "Admin unique : kerphilesaint@gmail.com (utiliser /admin/login pour admin)" -ForegroundColor Gray
