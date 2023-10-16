#!/bin/bash

# Имя папки, из которой выполняется скрипт
DIR_NAME=$(basename $(pwd))

# Дата и время для добавления в имя файла
DATE_TIME=$(date '+%Y-%m-%d_%H-%M-%S')

# Получение данных для подключения к базе данных из файла wp-config.php
DB_NAME=$(grep DB_NAME wp-config.php | awk -F \' '{print $4}')
DB_USER=$(grep DB_USER wp-config.php | awk -F \' '{print $4}')
DB_PASSWORD=$(grep DB_PASSWORD wp-config.php | awk -F \' '{print $4}')
DB_HOST=$(grep DB_HOST wp-config.php | awk -F \' '{print $4}')

# Путь для сохранения бэкапов
BACKUP_PATH="../${DIR_NAME}_${DATE_TIME}"

# Создание директории для бэкапа
mkdir -p $BACKUP_PATH

# Архивация файлов WordPress
tar -czf "${BACKUP_PATH}/${DIR_NAME}_${DATE_TIME}_files.tar.gz" .

# Создание дампа базы данных
mysqldump --no-tablespaces -h $DB_HOST -u $DB_USER -p$DB_PASSWORD $DB_NAME > "${BACKUP_PATH}/${DIR_NAME}_${DATE_TIME}_db.sql"

echo "Бэкап создан в ${BACKUP_PATH}"
