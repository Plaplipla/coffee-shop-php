#!/bin/bash
echo "Descargando DomPDF..."

# Crear directorio vendor si no existe
mkdir -p vendor
cd vendor

# Descargar DomPDF desde GitHub
echo "Descargando desde GitHub..."
curl -L -o dompdf.zip https://github.com/dompdf/dompdf/archive/refs/tags/v2.0.3.zip

# Extraer el archivo
echo "Extrayendo archivos..."
unzip -q dompdf.zip

# Renombrar directorio
if [ -d "dompdf-2.0.3" ]; then
    rm -rf dompdf
    mv dompdf-2.0.3 dompdf
fi

# Descargar dependencias necesarias
cd dompdf

# Descargar php-font-lib
echo "Descargando php-font-lib..."
curl -L -o lib.zip https://github.com/dompdf/php-font-lib/archive/refs/tags/0.5.4.zip
unzip -q lib.zip
if [ -d "php-font-lib-0.5.4" ]; then
    rm -rf lib
    mv php-font-lib-0.5.4 lib
fi

# Descargar php-svg-lib
echo "Descargando php-svg-lib..."
curl -L -o svg-lib.zip https://github.com/dompdf/php-svg-lib/archive/refs/tags/v0.5.0.zip
unzip -q svg-lib.zip
if [ -d "php-svg-lib-0.5.0" ]; then
    rm -rf svg-lib
    mv php-svg-lib-0.5.0 svg-lib
fi

# Limpiar archivos zip
rm -f *.zip
cd ../..

echo ""
echo "DomPDF instalado correctamente en vendor/dompdf"
echo ""
