@echo off
echo Descargando DomPDF...

REM Crear directorio vendor si no existe
if not exist "vendor" mkdir vendor
cd vendor

REM Descargar DomPDF desde GitHub
echo Descargando desde GitHub...
curl -L -o dompdf.zip https://github.com/dompdf/dompdf/archive/refs/tags/v2.0.3.zip

REM Extraer el archivo
echo Extrayendo archivos...
tar -xf dompdf.zip

REM Renombrar directorio
if exist "dompdf-2.0.3" (
    if exist "dompdf" rmdir /s /q dompdf
    ren dompdf-2.0.3 dompdf
)

REM Descargar dependencias necesarias
cd dompdf

REM Descargar php-font-lib
echo Descargando php-font-lib...
curl -L -o lib.zip https://github.com/dompdf/php-font-lib/archive/refs/tags/0.5.4.zip
tar -xf lib.zip
if exist "php-font-lib-0.5.4" (
    if exist "lib" rmdir /s /q lib
    ren php-font-lib-0.5.4 lib
)

REM Descargar php-svg-lib
echo Descargando php-svg-lib...
curl -L -o svg-lib.zip https://github.com/dompdf/php-svg-lib/archive/refs/tags/v0.5.0.zip
tar -xf svg-lib.zip
if exist "php-svg-lib-0.5.0" (
    if exist "svg-lib" rmdir /s /q svg-lib
    ren php-svg-lib-0.5.0 svg-lib
)

REM Limpiar archivos zip
del *.zip
cd ..\..\

echo.
echo DomPDF instalado correctamente en vendor/dompdf
echo.
pause
