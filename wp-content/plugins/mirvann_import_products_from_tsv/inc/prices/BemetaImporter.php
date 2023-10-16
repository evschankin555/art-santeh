<?php


/**
 * Класс для импорта данных для продуктов Bemeta.
 */
class BemetaImporter extends BaseTSVImporter
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
            'Раздел' => 'category',//
            'Бренд' => 'brand',//
            'Артикул' => 'sku',//
            'Название на русском' => 'mainTitle',//
            'Цвет' => 'sellingPrice',//
            'Ширина' => 'sideToSideWidth',//
            'Высота' => 'topToBottomHeight',//
            'Глубина, см' => 'backToFrontSize',//
            'Материал' => 'material',//
            'Цвет' => 'complianceValue',//
            'Глубина' => 'warrantyMonths',//
            //'Описание' => 'description',//
            'Вес, кг Брутто' => 'massWithPackaging',//
            'Страна сборки' => 'country',//
            'Коллекция' => 'collection',
            'Материал корпуса 1' => 'material',
            'Гарантия' => 'warrantyMonths',
            'технический рисунок' => 'additionalSchema',

        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->maxImages = 0;
        $this->mainImageField = 'Фото';
        $this->сonvertToMM = true; // false для мм
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
        return $this->data["Фото ".($index-1)];
    }
}

/**
 * Добавление действия WordPress для импорта данных Bemeta.
 */
add_action('wp_ajax_import_bemeta', 'import_bemeta');

/**
 * Функция для импорта данных Bemeta.
 *
 * Эта функция создает экземпляр BemetaImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_bemeta()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new BemetaImporter(__DIR__ . '/uploads/Bemeta-Beta.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
