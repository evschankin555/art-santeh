<?php

class UniversalExcelImporter extends BaseExcelImporter
{
    /**
     * Конструктор класса.
     *
     * @param {string} $file_path - Путь к файлу для импорта.
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->steps[] = "Инициализация свойств продукта начата";

        $invertedMapping = $this->generateInvertedMapping();
        $this->initializeProductProperties($invertedMapping);
    }

    /**
     * Отображение данных из строки в ассоциативный массив.
     *
     * @param {array} $header - Заголовок файла.
     * @param {array} $line - Одна строка данных.
     * @return {array} - Ассоциативный массив данных.
     */
    protected function mapData($header, $line)
    {
        return array_combine($header, $line);
    }
}

