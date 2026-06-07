@echo off
cd /d "%~dp0.."
set RAILWAY=%~dp0..\node_modules\.bin\railway.cmd

if not exist "%RAILWAY%" (
    echo Installation Railway CLI locale...
    call npm.cmd install
)

echo.
echo === Connexion Railway ===
echo 1. Un lien et un code vont s afficher CI-DESSOUS les warnings npm
echo 2. Ouvrez https://railway.com/activate dans Chrome
echo 3. Entrez le code affiche
echo 4. Attendez "Logged in" avant de fermer cette fenetre
echo.
call "%RAILWAY%" login --browserless
if errorlevel 1 goto :error

echo.
echo === Lier ce projet Railway ===
call "%RAILWAY%" link
if errorlevel 1 goto :error

echo.
echo === Sync secrets vers .env ===
powershell -ExecutionPolicy Bypass -File scripts\sync-env-from-railway.ps1
goto :end

:error
echo.
echo Echec. Alternative sans CLI :
echo   powershell -ExecutionPolicy Bypass -File scripts\apply-secrets-manual.ps1
:end
pause
