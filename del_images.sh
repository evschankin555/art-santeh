#!/bin/bash

# Укажите путь к вашему CSV-файлу
csv_file="/home/s/slava1983/art-santeh.ru/public_html/csv/result8_images.csv"

# Разделитель CSV
delimiter=","

# Используйте awk для обработки CSV-файла и извлечения путей к изображениям
awk -F"$delimiter" '{
  for (i=1; i<=NF; i++) {
    gsub(/^[[:space:]]*/, "", $i)   # Удалить начальные пробелы, если они есть
    gsub(/ /, "", $i)    # Удалить пробелы внутри адресов
    gsub(/"/, "", $i)    # Удалить двойные кавычки, если они есть
    print $i
  }
}' "$csv_file" | while IFS= read -r image
do
    echo "Удаляю оригинал: $image"

    # Определение и удаление вариаций на основе оригинала
    base_path="${image%.*}"

    # Удаляем вариации на основе оригинала и выводим адреса
    echo "Удаляю вариации для: $base_path"
    echo "Удаляю: ${base_path}-100x100.jpg"
    rm "${base_path}-100x100.jpg"
    echo "Удаляю: ${base_path}-300x300.jpg"
    rm "${base_path}-300x300.jpg"
    echo "Удаляю: ${base_path}-600x600.jpg"
    rm "${base_path}-600x600.jpg"
    echo "Удаляю: ${base_path}-150x150.jpg"
    rm "${base_path}-150x150.jpg"
    echo "Удаляю: ${base_path}-768x768.jpg"
    rm "${base_path}-768x768.jpg"

    rm "$image"
    echo "Удалено: $image"
done
