#!/bin/bash
# ============================================================
# docker-entrypoint.sh
# Railway (y otras plataformas) asignan un puerto dinamico en
# la variable de entorno PORT. Este script ajusta Apache para
# escuchar en ese puerto antes de arrancar el servidor.
# ============================================================
set -e

: "${PORT:=80}"

sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

exec "$@"
