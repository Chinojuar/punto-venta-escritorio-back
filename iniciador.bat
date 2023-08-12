@echo off

set EXE_NAME=wampmanager.exe
set VENTA_APP_NAME=VentaAPP.exe

REM Iniciar el proceso de Laravel en segundo plano
cd /d ..\Programación\Laravel\alfa-back 
start /b php artisan serve

REM Iniciar wampmanager si no está en ejecución
tasklist /FI "IMAGENAME eq %EXE_NAME%" 2>NUL | find /I "%EXE_NAME%" >NUL
if not %ERRORLEVEL% equ 0 (
    start "" "C:\wamp64\%EXE_NAME%"
)

REM Iniciar VentaAPP
start /max "" "C:\Program Files (x86)\Punto de Venta\VentaAPP\%VENTA_APP_NAME%"

REM Esperar a que VentaAPP se cierre
:LOOP
tasklist /FI "IMAGENAME eq %VENTA_APP_NAME%" 2>NUL | find /I "%VENTA_APP_NAME%" >NUL
if not %ERRORLEVEL% equ 0 (
    REM Cerrar el proceso de Laravel cuando se cierre VentaAPP
    taskkill /F /IM "php.exe"
    exit
)
timeout /t 5 /nobreak >NUL
goto LOOP
