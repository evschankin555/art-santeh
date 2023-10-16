#!/bin/bash

# Укажите путь к папке, в которой хранятся изображения
image_folder="/home/s/slava1983/art-santeh.ru/public_html/old/wp-content/uploads/2021/08/"

# Укажите размеры превью, которые нужно удалить
sizes="100x100 300x300 600x600 150x150 768x768"

# Разделите размеры превью по пробелам и выполните цикл для удаления файлов
for size in $sizes; do
  files_to_delete="${image_folder}"*-"$size".jpg
  for file in $files_to_delete; do
    rm -f "$file"
    echo "Удален файл: $file"
  done
done

echo "Удалены превью-изображения с размерами: $sizes"
