#!/bin/bash

# Укажите путь к папке, в которой хранятся изображения
image_folder="/home/s/slava1983/art-santeh.ru/public_html/wp-content/uploads/2023/10/"

# Укажите размеры превью, которые нужно удалить
sizes="150x150 50x50 768x768 100x100"

# Разделите размеры превью по пробелам и выполните цикл для удаления файлов
for size in $sizes; do
  files_to_delete="${image_folder}"*-"$size".jpg
  for file in $files_to_delete; do
    rm -f "$file"
    echo "Удален файл: $file"
  done
done

echo "Удалены превью-изображения с размерами: $sizes"
