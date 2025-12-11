@echo off
REM Batch file para i-start ang CodeIgniter 4 Server
REM I-double click lang ang file na ito

echo ========================================
echo   ITE311-DIGA Server Starter
echo ========================================
echo.

REM Pumunta sa project directory
cd /d C:\xampp\htdocs\ITE311-DIGA

if exist "spark" (
    echo [OK] Nasa project directory na
    echo.
    echo [INFO] Sinisimulan ang server...
    echo [INFO] Buksan ang: http://localhost:8080
    echo [INFO] Para i-stop: Press Ctrl+C
    echo.
    echo ========================================
    echo.
    
    REM I-start ang server
    php spark serve
) else (
    echo [ERROR] Hindi mahanap ang project directory!
    echo [ERROR] Path: C:\xampp\htdocs\ITE311-DIGA
    echo.
    pause
)

