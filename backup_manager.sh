#!/bin/bash

# Ubicación absoluta del proyecto
REPO_DIR="/home/isard/wazuh-project"
DATE=$(date +%Y-%m-%d)
DAY_WEEK=$(date +%u) # 5 es Viernes
TIME=$(date +%H%M)   # Formato HHMM (ej: 0830)
LAST_BACKUP_FILE="$REPO_DIR/.last_backup"

cd $REPO_DIR

# 1. Comprobar si ya se hizo backup hoy
if [ ! -f "$LAST_BACKUP_FILE" ] || [ "$(cat $LAST_BACKUP_FILE)" != "$DATE" ]; then
    echo "[$(date)] Iniciando backup diario..."
    
    # Crear rama diaria y subirla
    git checkout -b "backup-$DATE"
    git add .
    git commit -m "Auto-backup $DATE"
    git push origin "backup-$DATE"
    
    # Volver a la rama principal
    git checkout main
    
    # Guardar la fecha para no repetir hoy
    echo "$DATE" > "$LAST_BACKUP_FILE"
    echo "[$(date)] Backup diario finalizado."
fi

# 2. Lógica del Viernes (Merge a Main)
# Si es viernes (5) y la hora es igual o mayor a 08:30
if [ "$DAY_WEEK" -eq 5 ] && [ "$TIME" -ge "0830" ]; then
    echo "[$(date)] Es Viernes 08:30+: Sincronizando con MAIN..."
    git checkout main
    git merge "backup-$DATE" --no-edit
    git push origin main
fi
