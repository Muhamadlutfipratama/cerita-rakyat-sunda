#!/bin/bash

BACKUP_FILE="$1"

if [ -z "$BACKUP_FILE" ]; then
  echo "❌ Usage: ./restore.sh backup/mysql-data-<timestamp>.tar.gz"
  exit 1
fi

echo "⚠️  Restoring volume 'mysql-data' from $BACKUP_FILE ..."
echo "❗ This will overwrite existing data in the volume."

read -p "Are you sure? (y/N): " confirm
if [[ "$confirm" != "y" ]]; then
  echo "❌ Cancelled."
  exit 1
fi

# Create temp container to restore the volume
docker run --rm \
  -v mysql-data:/volume \
  -v "$(pwd)/$(dirname "$BACKUP_FILE")":/backup \
  alpine \
  sh -c "rm -rf /volume/* && tar xzf /backup/$(basename "$BACKUP_FILE") -C /volume"

echo "✅ Restore complete!"
