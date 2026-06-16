# Verification automatique du site local (Laragon + MySQL).
# Usage : powershell -ExecutionPolicy Bypass -File scripts/run-checklist-local.ps1

$ErrorActionPreference = "Continue"
$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$bases = @(
    "http://mon-blog.test",
    "http://127.0.0.1/mon_blog/public"
)

$base = $null
foreach ($candidate in $bases) {
    try {
        $r = Invoke-WebRequest -Uri $candidate -UseBasicParsing -TimeoutSec 8 -ErrorAction Stop
        if ($r.StatusCode -eq 200) {
            $base = $candidate
            break
        }
    } catch { }
}

if (-not $base) {
    Write-Host "Site local inaccessible." -ForegroundColor Red
    Write-Host "  1. Demarrez Laragon (Apache + MySQL)" -ForegroundColor Yellow
    Write-Host "  2. Ouvrez http://mon-blog.test dans le navigateur" -ForegroundColor Yellow
    exit 1
}

Write-Host ">> Base URL : $base" -ForegroundColor Cyan

$paths = @(
    "/",
    "/login",
    "/register",
    "/forgot-password",
    "/admin/login",
    "/categories",
    "/tags",
    "/about",
    "/search?q=benin"
)

$ok = 0
$fail = 0
foreach ($path in $paths) {
    $url = $base.TrimEnd('/') + $path
    if ($path -eq "/admin/login") {
        try {
            Invoke-WebRequest -Uri $url -UseBasicParsing -MaximumRedirection 0 -TimeoutSec 15 -ErrorAction Stop | Out-Null
            Write-Host "WARN /admin/login accessible sans key (attendu: 404)" -ForegroundColor Yellow
            $fail++
        } catch {
            if ($_.Exception.Response -and $_.Exception.Response.StatusCode.value__ -eq 404) {
                Write-Host "OK 404 /admin/login (protege par key)" -ForegroundColor Green
                $ok++
            } else {
                Write-Host "FAIL /admin/login" -ForegroundColor Red
                $fail++
            }
        }
        continue
    }

    try {
        $r = Invoke-WebRequest -Uri $url -UseBasicParsing -MaximumRedirection 0 -TimeoutSec 15 -ErrorAction Stop
        Write-Host "OK $($r.StatusCode) $path" -ForegroundColor Green
        $ok++
    } catch {
        if ($_.Exception.Response -and $_.Exception.Response.StatusCode.value__ -in 301, 302, 303, 307, 308) {
            Write-Host "OK redirect $path" -ForegroundColor Green
            $ok++
        } else {
            Write-Host "FAIL $path" -ForegroundColor Red
            $fail++
        }
    }
}

Write-Host ""
Write-Host ">> Base de donnees..." -ForegroundColor Cyan
php artisan about --only=drivers 2>&1 | Select-String "Database"

Write-Host ""
Write-Host "Resultat pages : $ok OK, $fail echecs" -ForegroundColor $(if ($fail -eq 0) { "Green" } else { "Yellow" })
exit $(if ($fail -eq 0) { 0 } else { 1 })
