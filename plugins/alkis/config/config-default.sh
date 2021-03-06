#!/bin/bash

POSTGRES_SCHEMA="alkis"
GFS_TEMPLATE="../config/alkis-schema.gfs"
DATA_PATH="/var/www/data/alkis/ff/eingang"
IMPORT_PATH="/var/www/data/alkis/ff/import"
LOG_PATH="/var/www/data/alkis/ff/logs"
POSTPROCESSING_PATH="/var/www/data/alkis/ff/postprocessing"
LOG_FILE="import.log"
ERROR_FILE="error.log"
declare -i LOG_LEVEL=1 # 0 nicht gelogged, 1 nur auf stdout, 2 nur in datei, 3 stdout und datei

UNZIP_PASSWORD="secret2"

OGR_BINPATH="/usr/local/gdal/bin" # inside the gdal container

EPSG_CODE="25833"