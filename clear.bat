@echo off
echo ============================================
echo  Clear Cache Laravel (PHP 8.4)
echo ============================================
C:\laragon\bin\php\php-8.4\php.exe artisan view:clear
C:\laragon\bin\php\php-8.4\php.exe artisan config:clear
C:\laragon\bin\php\php-8.4\php.exe artisan route:clear
C:\laragon\bin\php\php-8.4\php.exe artisan cache:clear
echo.
echo ============================================
echo  Selesai! Cache sudah bersih.
echo ============================================
pause