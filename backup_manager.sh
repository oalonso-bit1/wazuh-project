#!/bin/bash

# Ubicación absoluta del proyecto
REPO_DIR="/home/isard/wazuh-project"
REPO_URL="https://github.com/oalonso-bit1/wazuh-project.git"
DATE=$(date +%Y-%m-%d)
DAY_WEEK=$(date +%u) # 5 es Viernes
TIME=$(date +%H%M)   # Formato HHMM
LAST_BACKUP_FILE="$REPO_DIR/.last_backup"

cd $REPO_DIR

# --- LOGICA DE AUTO-CREACIÓN ---
if [ ! -d ".git" ]; then
    echo "[$(date)] Proyecto no inicializado. Creando repositorio local..."
    git init
    git checkout -b main
    git add .
    git commit -m "Initial commit (Auto-created)"
    git remote add origin "$REPO_URL"
    git push -u origin main
fi

# Asegurar que estamos en una rama válida (corregir error de 'main')
git branch -M main

# --- LOGICA DE BACKUP DIARIO ---
if [ ! -f "$LAST_BACKUP_FILE" ] || [ "$(cat $LAST_BACKUP_FILE)" != "$DATE" ]; then
    echo "[$(date)] Iniciando backup diario..."
    
    # Crear rama diaria y subirla
    git checkout -b "backup-$DATE"
    git add .
    git commit -m "Auto-backup $DATE"
    git push origin "backup-$DATE"
    
    # Volver a la rama principal (ahora sí existe)
    git checkout main
    
    # Guardar la fecha para no repetir hoy
    echo "$DATE" > "$LAST_BACKUP_FILE"
    echo "[$(date)] Backup diario finalizado."
fi

# --- LOGICA DEL VIERNES (MERGE A MAIN) ---
if [ "$DAY_WEEK" -eq 5 ] && [ "$TIME" -ge "0830" ]; then
    echo "[$(date)] Es Viernes 08:30+: Sincronizando con MAIN..."
    git checkout main
    git merge "backup-$DATE" --no-edit
    git push origin main
fi
