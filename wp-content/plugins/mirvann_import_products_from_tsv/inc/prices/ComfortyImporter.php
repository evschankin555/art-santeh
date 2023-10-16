<?php


/**
 * Класс для импорта данных для продуктов Comforty.
 */
class ComfortyImporter extends BaseTSVImporter
{
    /**
     * Конструктор класса.
     *
     * @param {string} $file_path - Путь к файлу для импорта.
     */
    public function __construct($file_path)
    {
        $this->file_path = $file_path;
        $this->steps[] = "Инициализация свойств продукта начата";

        $mapping = [
            'Тип изделия' => 'category',
            'Бренд' => 'brand',
            'Артикул' => 'sku',
            'Наименование' => 'mainTitle',
            'Дополнительное описание' => 'description',
            'РОЗНИЧНАЯ ЦЕНА руб.' => 'sellingPrice',
            'Вес брутто (коробка №1), кг ' => 'massWithPackaging',
            'Глубина основного изднлия .мм' => 'backToFrontSize',
            'Ширина основного изделия .мм' => 'sideToSideWidth',
            'Высота основного изделия .мм' => 'topToBottomHeight',
            'Коллекция' => 'collection',
            //'Тип изделия' => 'productType',
            'Способ монтажа' => 'installationMethod',
            'Материал корпуса' => 'material',
            'Гарантия' => 'warrantyMonths',
            'Страна происхождения' => 'country',
            'Цвет' => 'complianceValue',
            'Подсветка' => 'illumination',
            'Тип лампы' => 'viewIllumination',
            'Покрытие корпуса' => 'bodyCoating',
            'Схема' => 'mainSchema',
            'Схема подключения' => 'additionalSchema',

            //новые
            'Материал фасада' => 'facadeMaterial',
            'Покрытие фасада' => 'facadeCoating',
            'Материал раковины' => 'sinkMaterial',
            'Материал столешницы' => 'countertopMaterial',
            'Новая система хранения' => 'storageSystemNew',
            'Новая инструкция' => 'instructionNew',
            'Видеоролик' => 'video'
        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->baseUrl = "https://mirvann.developing-site.ru/product_images/Comforty/{sku}/";
        $this->fileDirPathTemplate = ABSPATH . "product_images/Comforty/{sku}/";
        $this->maxImages = 15;
        $this->mainImageField = 'Доп. Фотография 0';
        $this->сonvertToMM = false;
        $this->fullImage = true;


        $this->initializeProductProperties($invertedMapping, false);
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

    protected function getImageName($index) {
        return $this->data["Доп. Фотография ".($index-1)];
    }
}

/**
 * Добавление действия WordPress для импорта данных Comforty.
 */
add_action('wp_ajax_import_comforty', 'import_comforty');

/**
 * Функция для импорта данных Comforty.
 *
 * Эта функция создает экземпляр ComfortyImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_comforty()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new ComfortyImporter(__DIR__ . '/uploads/DREJA/Comforty.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
