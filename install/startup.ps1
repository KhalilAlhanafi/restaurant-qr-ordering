# Restaurant QR System - Auto Startup Script
# This script runs when Windows starts and launches the server

$ProjectPath = "PROJECT_PATH_PLACEHOLDER"
$LogPath = "$ProjectPath\storage\logs\startup.log"

function Write-Log {
    param($Message)
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    "$timestamp - $Message" | Out-File -FilePath $LogPath -Append
    Write-Host $Message
}

# Ensure log directory exists
if (!(Test-Path "$ProjectPath\storage\logs")) {
    New-Item -ItemType Directory -Path "$ProjectPath\storage\logs" -Force | Out-Null
}

Write-Log "=== Restaurant QR System Startup ==="

# Wait for network to be available
Write-Log "Waiting for network..."
$maxWait = 60
$waited = 0
while ($waited -lt $maxWait) {
    $network = Test-Connection -ComputerName localhost -Count 1 -Quiet
    if ($network) {
        Write-Log "Network is ready"
        break
    }
    Start-Sleep -Seconds 1
    $waited++
}

# Wait for MySQL (if using XAMPP)
Write-Log "Checking MySQL..."
$mysqlReady = $false
$waited = 0
while ($waited -lt 30) {
    try {
        $tcp = New-Object System.Net.Sockets.TcpClient
        $tcp.Connect("127.0.0.1", 3306)
        $tcp.Close()
        $mysqlReady = $true
        Write-Log "MySQL is ready"
        break
    } catch {
        Start-Sleep -Seconds 1
        $waited++
    }
}

if (!$mysqlReady) {
    Write-Log "WARNING: MySQL not detected. Starting anyway..."
}

# Check if server is already running
$existingProcess = Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object {
    $_.CommandLine -like "*artisan serve*"
}

if ($existingProcess) {
    Write-Log "Server is already running (PID: $($existingProcess.Id))"
    exit 0
}

# Start the Laravel server
Write-Log "Starting Laravel server..."
Set-Location $ProjectPath

# Start PHP server in background
$process = Start-Process -FilePath "php" -ArgumentList "artisan serve --host=0.0.0.0 --port=8000" -WindowStyle Minimized -PassThru

Write-Log "Server started with PID: $($process.Id)"
Write-Log "Server available at: http://localhost:8000"

# Keep script running to monitor (optional)
# In production, Task Scheduler will handle restarting if needed
