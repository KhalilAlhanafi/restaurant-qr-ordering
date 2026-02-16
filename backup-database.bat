@echo off
chcp 65001 >nul
title Restaurant QR - Database Backup
color 0B

echo ==========================================
echo   Restaurant QR - Database Backup
echo ==========================================
echo.

REM Change to project directory
cd /d "%~dp0"

REM Check if PHP is available
php -v >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP is not found in PATH
    echo Please ensure XAMPP or PHP is installed and in PATH
    pause
    exit /b 1
)

echo [OK] PHP found
echo [OK] Starting database backup...
echo.

REM Run backup command
php artisan db:backup

if errorlevel 1 (
    echo.
    echo [ERROR] Backup failed!
    echo Check the error message above.
    pause
    exit /b 1
)

echo.
echo [SUCCESS] Backup completed successfully!
echo.
echo Backup files are saved in: storage\app\backups\
echo.

REM Show recent backups
echo Recent backups:
echo ----------------
if exist "storage\app\backups\*.sql.gz" (
    dir /b /o-d "storage\app\backups\*.sql.gz" 2>nul | findstr /n "^" | findstr "^[1-5]:"
) else (
    echo No backup files found.
)

echo.
pause
