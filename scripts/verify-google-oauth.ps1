# Verifie que Google OAuth est pret (local et/ou prod).
# Usage : powershell -ExecutionPolicy Bypass -File scripts/verify-google-oauth.ps1

$ErrorActionPreference = "Continue"
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

Write-Host ">> Verification Google OAuth" -ForegroundColor Cyan
Write-Host ""

$clientId = Read-DotEnvValue ".env" "GOOGLE_CLIENT_ID"
$clientSecret = Read-DotEnvValue ".env" "GOOGLE_CLIENT_SECRET"
$redirect = Read-DotEnvValue ".env" "GOOGLE_REDIRECT_URI"

$localOk = $true
if ([string]::IsNullOrWhiteSpace($clientId) -or $clientId -match "votre-id") {
    Write-Host "LOCAL  GOOGLE_CLIENT_ID     : manquant" -ForegroundColor Red
    $localOk = $false
} else {
    Write-Host "LOCAL  GOOGLE_CLIENT_ID     : OK" -ForegroundColor Green
}

if ([string]::IsNullOrWhiteSpace($clientSecret) -or $clientSecret -match "votre-secret") {
    Write-Host "LOCAL  GOOGLE_CLIENT_SECRET : manquant" -ForegroundColor Red
    $localOk = $false
} else {
    Write-Host "LOCAL  GOOGLE_CLIENT_SECRET : OK" -ForegroundColor Green
}

if ([string]::IsNullOrWhiteSpace($redirect)) {
    Write-Host "LOCAL  GOOGLE_REDIRECT_URI  : manquant" -ForegroundColor Red
    $localOk = $false
} else {
    Write-Host "LOCAL  GOOGLE_REDIRECT_URI  : $redirect" -ForegroundColor Green
}

Write-Host ""
$prodBase = "https://web-production-c5c2f.up.railway.app"
try {
    $r = Invoke-WebRequest -Uri "$prodBase/auth/google" -UseBasicParsing -MaximumRedirection 0 -TimeoutSec 15 -ErrorAction Stop
    Write-Host "PROD   /auth/google         : HTTP $($r.StatusCode)" -ForegroundColor Yellow
} catch {
    if ($_.Exception.Response.StatusCode.value__ -in 302, 303, 307) {
        $loc = $_.Exception.Response.Headers.Location
        if ($loc -like "*accounts.google.com*") {
            Write-Host "PROD   /auth/google         : OK (redirection Google)" -ForegroundColor Green
        } else {
            Write-Host "PROD   /auth/google         : redirect vers $loc" -ForegroundColor Yellow
        }
    } else {
        Write-Host "PROD   /auth/google         : echec ($($_.Exception.Message))" -ForegroundColor Red
    }
}

Write-Host ""
if (-not $localOk) {
    Write-Host "Local : lancez scripts/setup-google-oauth.ps1 apres avoir rempli google.local.env" -ForegroundColor Yellow
    exit 1
}

Write-Host "Configuration locale OK. Testez sur $redirect" -ForegroundColor Green
exit 0
