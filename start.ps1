# ============================================================
# start.ps1 - Lance l'API et le back-office AP4 (SIGEANSUD)
# Usage : clic droit -> "Executer avec PowerShell"
#         ou depuis un terminal : .\start.ps1
# Prerequis : Laragon doit tourner (pour MySQL)
# ============================================================

$API_PORT  = 8000
$BO_PORT   = 8001
$ROOT      = "C:\laragon\www\AP4"
$API_DIR   = "$ROOT\api"
$BO_DIR    = "$ROOT\backoffice"
$SQL_FILE  = "$ROOT\sigansud.sql"

# --- Recherche de mysql.exe (Laragon ou PATH) ---
$mysqlExe = $null
$laragonPaths = Get-ChildItem "C:\laragon\bin\mysql" -ErrorAction SilentlyContinue |
    Where-Object { $_.PSIsContainer } |
    ForEach-Object { "$($_.FullName)\bin\mysql.exe" } |
    Where-Object { Test-Path $_ }

if ($laragonPaths) {
    $mysqlExe = $laragonPaths | Select-Object -Last 1
} elseif (Get-Command mysql -ErrorAction SilentlyContinue) {
    $mysqlExe = "mysql"
}

# --- Import de la base de donnees ---
Write-Host ""
if ($mysqlExe) {
    Write-Host "  Import de la base de donnees..." -NoNewline
    try {
        & $mysqlExe -u root --execute="source $SQL_FILE" 2>&1 | Out-Null
        Write-Host " OK" -ForegroundColor Green
    } catch {
        Write-Host " ECHEC (continue quand meme)" -ForegroundColor Yellow
    }
} else {
    Write-Host "  mysql.exe introuvable - importe la BDD manuellement via phpMyAdmin." -ForegroundColor Yellow
}

# --- Lancement des serveurs en arriere-plan ---
# API sur 0.0.0.0 pour que l'emulateur Android puisse y acceder via 10.0.2.2
Write-Host ""
Write-Host "  Lancement de l'API sur le port $API_PORT ..."
$apiProc = Start-Process php `
    -ArgumentList "-S 0.0.0.0:$API_PORT -t public" `
    -WorkingDirectory $API_DIR `
    -PassThru -WindowStyle Minimized

Write-Host "  Lancement du back-office sur le port $BO_PORT ..."
$boProc = Start-Process php `
    -ArgumentList "-S localhost:$BO_PORT -t public" `
    -WorkingDirectory $BO_DIR `
    -PassThru -WindowStyle Minimized

# --- Attente que l'API reponde ---
Write-Host ""
Write-Host "  Attente de l'API..." -NoNewline
$ready   = $false
$elapsed = 0
$timeout = 30

while (-not $ready -and $elapsed -lt $timeout) {
    Start-Sleep 1
    $elapsed++
    try {
        $null = Invoke-WebRequest "http://localhost:$API_PORT" -UseBasicParsing -TimeoutSec 2
        $ready = $true
    } catch {
        # Toute reponse HTTP (meme 404/500) = serveur demarre
        if ($_.Exception.Response) { $ready = $true }
    }
    Write-Host "." -NoNewline
}

Write-Host ""

if (-not $ready) {
    Write-Host ""
    Write-Host "  ERREUR : l'API ne repond pas apres $timeout secondes." -ForegroundColor Red
    Write-Host "  Verifie que Laragon tourne et que MySQL est actif." -ForegroundColor Red
    Write-Host ""
    Write-Host "  Appuie sur une touche pour quitter..."
    $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
    $apiProc.Kill()
    $boProc.Kill()
    exit 1
}

# --- Ouverture du navigateur ---
Start-Process "http://localhost:$BO_PORT"
Start-Sleep 1
Start-Process "http://localhost:$API_PORT"

# --- Infos ---
Write-Host ""
Write-Host "  =================================" -ForegroundColor Green
Write-Host "  Tout est lance !" -ForegroundColor Green
Write-Host "  =================================" -ForegroundColor Green
Write-Host ""
Write-Host "  API         : http://localhost:$API_PORT"
Write-Host "  Back-office : http://localhost:$BO_PORT"
Write-Host ""
Write-Host "  Comptes back-office (gestionnaires) :"
Write-Host "    admin  /  admin"
Write-Host "    admin2 /  admin"
Write-Host ""
Write-Host "  Comptes mobile (soignants) :"
Write-Host "    SOI001 / soignant  (Dupont Marie)"
Write-Host "    SOI002 / soignant  (Martin Pierre)"
Write-Host "    SOI003 / soignant  (Durand Sophie)"
Write-Host ""
Write-Host "  Android (emulateur) : http://10.0.2.2:$API_PORT/api/"
Write-Host ""
Write-Host "  Appuie sur une touche pour arreter les serveurs..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

# --- Nettoyage ---
Write-Host ""
Write-Host "  Arret des serveurs..."
$apiProc.Kill()
$boProc.Kill()
Write-Host "  Bye !"
Write-Host ""
