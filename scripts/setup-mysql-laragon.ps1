# Crée la base MySQL « mon_blog » et lance les migrations Laravel (Laragon).
# Usage : powershell -ExecutionPolicy Bypass -File scripts/setup-mysql-laragon.ps1

$ErrorActionPreference = "Stop"
$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$mysqlDirs = Get-ChildItem "C:\laragon\bin\mysql" -Directory -ErrorAction SilentlyContinue |
    Where-Object { $_.Name -notlike '*DISABLED*' } |
    Sort-Object Name -Descending

if (-not $mysqlDirs) {
    Write-Error "MySQL Laragon introuvable. Démarrez MySQL dans Laragon puis relancez ce script."
}

$mysql = Join-Path $mysqlDirs[0].FullName "bin\mysql.exe"
$dbName = "mon_blog"

Write-Host ">> Création de la base '$dbName'..." -ForegroundColor Cyan
& $mysql -u root -e "CREATE DATABASE IF NOT EXISTS ``$dbName`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

Write-Host ">> Migrations Laravel..." -ForegroundColor Cyan
php artisan migrate --force

Write-Host ">> Seeders (admin + articles)..." -ForegroundColor Cyan
php artisan db:seed --class=AdminUserSeeder --force
php artisan db:seed --class=ArticlesSeeder --force

Write-Host ""
Write-Host "Terminé. Ouvrez phpMyAdmin Laragon -> base '$dbName'." -ForegroundColor Green
Write-Host "URL locale : http://mon-blog.test" -ForegroundColor Green
