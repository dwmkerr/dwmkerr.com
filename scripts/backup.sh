#!/usr/bin/env bash

# Create a name and path for a backup file.
backup_file="$(date -u +"%Y-%m-%d")-dwmkerr.com.tar.gz"
remote_path="~/backups/${backup_file}"
local_path="./backups/${backup_file}"

# Create the backup file.
ssh dwmkerr.com tar czf ${remote_path} /var/www/ghost
scp dwmkerr.com:${remote_path} ${local_path}
