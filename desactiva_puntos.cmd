@echo off
SET CURHOUR=%TIME:~0,2%
SET CURHOUR=%CURHOUR: =0%
set year=%date:~6,4%
set yr=%date:~8,2%
set month=%date:~3,2%
set day=%date:~0,2%
C:\xampp\php\php.exe C:\xampp\htdocs\admin_puntos\desactiva_puntos.php >> C:\xampp\htdocs\admin_puntos\logs\desactiva_puntos_%day%%month%%year%.txt