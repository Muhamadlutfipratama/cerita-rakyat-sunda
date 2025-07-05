#!/bin/bash

BACKUP_DIR="./backup"
BACKUP_FILE="mysql-data-$(date +%Y%m%d-%H%M%S).tar.gz"

mkdir -p "$BACKUP_DIR"

echo "📦 Backing up 'mysql-data' volume to $BACKUP_DIR/$BACKUP_FILE ..."

docker run --rm \
  -v mysql-data:/volume \
  -v "$(pwd)/$BACKUP_DIR":/backup \
  alpine \
  tar czf "/backup/$BACKUP_FILE" -C /volume .

echo "✅ Backup complete: $BACKUP_DIR/$BACKUP_FILE"
