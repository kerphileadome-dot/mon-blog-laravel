# Verification production Railway — variables et fonctionnalites.
# Usage : powershell -ExecutionPolicy Bypass -File scripts/verify-railway-prod.ps1

$ErrorActionPreference = "Continue"
$base = "https://web-production-c5c2f.up.railway.app"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Verification PRODUCTION Railway" -ForegroundColor Cyan
Write-Host "  $base" -ForegroundColor Gray
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# --- Pages HTTP ---
Write-Host ">> Pages publiques" -ForegroundColor Yellow
$paths = @("/", "/login", "/register", "/admin/login", "/forgot-password", "/categories", "/about")
$pageOk = 0
foreach ($path in $paths) {
    try {
        $r = Invoke-WebRequest -Uri ($base + $path) -UseBasicParsing -TimeoutSec 20
        Write-Host "  OK  $path" -ForegroundColor Green
        $pageOk++
    } catch {
        Write-Host "  FAIL $path" -ForegroundColor Red
    }
}

# --- Google OAuth ---
Write-Host ""
Write-Host ">> Google OAuth" -ForegroundColor Yellow
$googleOk = $false
try {
    Invoke-WebRequest -Uri "$base/auth/google" -UseBasicParsing -MaximumRedirection 0 -TimeoutSec 15 -ErrorAction Stop
} catch {
    if ($_.Exception.Response.StatusCode.value__ -in 302, 303, 307) {
        $loc = $_.Exception.Response.Headers.Location
        if ($loc -like "*accounts.google.com*") {
            Write-Host "  OK  Redirection vers Google (GOOGLE_CLIENT_ID configure)" -ForegroundColor Green
            $googleOk = $true
        } elseif ($loc -like "*login*") {
            Write-Host "  WARN Redirection login (GOOGLE_CLIENT_ID peut-etre vide)" -ForegroundColor Yellow
        } else {
            Write-Host "  WARN Redirect: $loc" -ForegroundColor Yellow
        }
    } else {
        Write-Host "  FAIL /auth/google" -ForegroundColor Red
    }
}

# --- Health ---
Write-Host ""
Write-Host ">> Sante application" -ForegroundColor Yellow
try {
    $up = Invoke-WebRequest -Uri "$base/up" -UseBasicParsing -TimeoutSec 15
    if ($up.Content -match "Application up") {
        Write-Host "  OK  /up" -ForegroundColor Green
    }
} catch {
    Write-Host "  FAIL /up" -ForegroundColor Red
}

# --- Checklist variables Railway (manuel) ---
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  CHECKLIST Variables Railway (service web)" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$required = @(
    @{ Name = "APP_NAME";           Value = "KerpheX Blog";                    Critical = $true },
    @{ Name = "APP_ENV";            Value = "production";                      Critical = $true },
    @{ Name = "APP_DEBUG";          Value = "false";                           Critical = $true },
    @{ Name = "APP_KEY";            Value = "base64:... (votre cle)";           Critical = $true },
    @{ Name = "APP_URL";            Value = $base;                               Critical = $true },
    @{ Name = "DB_CONNECTION";      Value = "sqlite";                            Critical = $true },
    @{ Name = "SESSION_DRIVER";     Value = "database";                          Critical = $true },
    @{ Name = "CACHE_STORE";        Value = "database";                          Critical = $true },
    @{ Name = "QUEUE_CONNECTION";   Value = "database";                          Critical = $true },
    @{ Name = "LOG_LEVEL";          Value = "error";                             Critical = $false },
    @{ Name = "ADMIN_EMAILS";       Value = "kerphilesaint@gmail.com";           Critical = $true }
)

$mail = @(
    @{ Name = "MAIL_MAILER";        Value = "smtp";                              Critical = $true },
    @{ Name = "MAIL_HOST";          Value = "smtp.gmail.com";                    Critical = $true },
    @{ Name = "MAIL_PORT";          Value = "587";                               Critical = $true },
    @{ Name = "MAIL_USERNAME";      Value = "kerphilesaint@gmail.com";           Critical = $true },
    @{ Name = "MAIL_PASSWORD";      Value = "(mot de passe application Gmail)";   Critical = $true },
    @{ Name = "MAIL_ENCRYPTION";   Value = "tls";                               Critical = $true },
    @{ Name = "MAIL_FROM_ADDRESS";  Value = "kerphilesaint@gmail.com";           Critical = $true },
    @{ Name = "MAIL_FROM_NAME";     Value = "KerpheX Blog";                      Critical = $false }
)

$google = @(
    @{ Name = "GOOGLE_CLIENT_ID";     Value = "838121924906-...apps.googleusercontent.com"; Critical = $true },
    @{ Name = "GOOGLE_CLIENT_SECRET"; Value = "(secret Google Cloud)";                        Critical = $true },
    @{ Name = "GOOGLE_REDIRECT_URI";  Value = "$base/auth/google/callback";                 Critical = $true }
)

$optional = @(
    @{ Name = "SYNC_EXPORT_TOKEN"; Value = "(jeton sync prod->local)"; Critical = $false }
)

$remove = @("DB_HOST", "DB_PORT", "DB_DATABASE", "DB_USERNAME", "DB_PASSWORD")

function Show-Group($title, $items) {
    Write-Host "  $title" -ForegroundColor Yellow
    foreach ($item in $items) {
        $mark = if ($item.Critical) { "[!]" } else { "[ ]" }
        Write-Host "  $mark $($item.Name)" -ForegroundColor White
        Write-Host "      = $($item.Value)" -ForegroundColor DarkGray
    }
    Write-Host ""
}

Show-Group "OBLIGATOIRES" $required
Show-Group "EMAILS (reset mot de passe)" $mail
Show-Group "GOOGLE OAUTH" $google
Show-Group "OPTIONNEL" $optional

Write-Host "  A SUPPRIMER si presentes (MySQL inutile) :" -ForegroundColor Red
foreach ($v in $remove) {
    Write-Host "    - $v" -ForegroundColor DarkRed
}
Write-Host ""

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ACTIONS Railway hors variables" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  [ ] Volume monte sur /app/database (SQLite persistante)" -ForegroundColor White
Write-Host "  [ ] Volume monte sur /app/storage (images persistantes)" -ForegroundColor White
Write-Host "  [ ] Dernier deploiement = Success" -ForegroundColor White
Write-Host "  [ ] Mot de passe admin change (plus Franklinblog20?)" -ForegroundColor White
Write-Host ""

Write-Host "Resultat auto : $pageOk/$($paths.Count) pages OK" -ForegroundColor $(if ($pageOk -eq $paths.Count) { "Green" } else { "Yellow" })
if ($googleOk) {
    Write-Host "Google OAuth : configure cote prod" -ForegroundColor Green
} else {
    Write-Host "Google OAuth : a verifier dans Railway Variables" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Envoyez une capture de Railway -> Variables pour validation personnalisee." -ForegroundColor Cyan
