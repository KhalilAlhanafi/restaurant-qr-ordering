@echo off
chcp 65001 >nul
title Restaurant QR Server
color 0A

echo ==========================================
echo   Restaurant QR Ordering System
echo   Auto-Start Script
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
echo [OK] Starting Laravel server on all network interfaces...
echo [OK] Server will be available at: http://YOUR_COMPUTER_IP:8000
echo.
echo Press Ctrl+C to stop the server
echo.

REM Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8000

REM If server crashes, wait and restart
if errorlevel 1 (
    echo.
    echo [WARNING] Server stopped unexpectedly. Restarting in 5 seconds...
    timeout /t 5 /nobreak >nul
    goto :start
)
