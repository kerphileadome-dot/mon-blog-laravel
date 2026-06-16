# Synchronise GOOGLE_* depuis Railway vers le .env local.
# Les emails locaux (Gmail SMTP) restent dans mail.secret.env — Railway utilise Brevo.
# Prerequis : npm exec --yes @railway/cli -- login  puis  railway link
#
# Usage : powershell -ExecutionPolicy Bypass -File scripts/sync-env-from-railway.ps1

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

Write-Host ">> Sync Railway -> local (.env + google.local.env)" -ForegroundColor Cyan

$railway = Join-Path $projectRoot "node_modules\.bin\railway.cmd"
if (-not (Test-Path $railway)) {
    Write-Host "Railway CLI local introuvable. Lancez : npm.cmd install" -ForegroundColor Yellow
    exit 1
}
$json = & $railway variables --json 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "Echec Railway CLI. Lancez :" -ForegroundColor Yellow
    Write-Host "  npm exec --yes @railway/cli -- login" -ForegroundColor Yellow
    Write-Host "  npm exec --yes @railway/cli -- link" -ForegroundColor Yellow
    exit 1
}

$vars = $json | ConvertFrom-Json
$map = @{}
foreach ($item in $vars) {
    $map[$item.name] = $item.value
}

$keys = @(
    "GOOGLE_CLIENT_ID",
    "GOOGLE_CLIENT_SECRET"
)

$updated = 0
foreach ($key in $keys) {
    if ($map.ContainsKey($key) -and -not [string]::IsNullOrWhiteSpace($map[$key])) {
        if ($key -like "GOOGLE_*") {
            Set-DotEnvValue "google.local.env" $key $map[$key]
        }
        Set-DotEnvValue ".env" $key $map[$key]
        $display = if ($key -match "PASSWORD|SECRET") { "********" } else { $map[$key] }
        Write-Host "  OK $key = $display" -ForegroundColor Green
        $updated++
    }
}

if ($updated -eq 0) {
    Write-Host "Aucune variable recuperee." -ForegroundColor Yellow
    exit 1
}

powershell -ExecutionPolicy Bypass -File scripts/setup-local-env.ps1
powershell -ExecutionPolicy Bypass -File scripts/setup-google-oauth.ps1 2>$null
Write-Host "Sync terminee." -ForegroundColor Green
