<?php


/**
 * Класс для импорта данных для продуктов Aquame.
 */
class AquameImporter extends BaseTSVImporter
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
            'Изделие' => 'category',//--
            'Бренд' => 'brand',//+--
            'Артикул' => 'sku',//+--
            'Наименование в прайсе' => 'mainTitle',//+--
            'Серия' => 'sellingPrice',//--нет цен
            'Ширина товара с упаковкой, мм' => 'sideToSideWidth',//+--
            'Высота товара с упаковкой, мм' => 'topToBottomHeight',//+--
            'Глубина товара с упаковкой, мм' => 'backToFrontSize',//+--
            'Материал' => 'material',//+--
            'Цвет' => 'complianceValue',//+--
            'Гарантия' => 'warrantyMonths',//+--
            //'Описание' => 'description',//
            //'Масса, кг (вес товара вместе с упаковкой)' => 'massWithPackaging',//+
            'Страна бренда' => 'country',//+--
            'Ссылка на схемы' => 'mainSchema',//--
        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->maxImages = 3;
        $this->mainImageField = 'ссылка на фото 1';
        $this->convertToMM = true; // false для мм
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
        return $this->data["ссылка на фото ".($index-1)];
    }
}

/**
 * Добавление действия WordPress для импорта данных Aquame.
 */
add_action('wp_ajax_import_aquame', 'import_aquame');

/**
 * Функция для импорта данных Aquame.
 *
 * Эта функция создает экземпляр AquameImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_aquame()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new AquameImporter(__DIR__ . '/uploads/Aquame-Смесители.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
