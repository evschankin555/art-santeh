<?php


/**
 * Класс для импорта данных для продуктов ComfortyUnitaz.
 */
class ComfortyUnitazImporter extends BaseTSVImporter
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
            'Категория' => 'category',
            'Бренд' => 'brand',
            'Артикул' => 'sku',//
            'Наименование' => 'mainTitle',//
            'РОЗНИЧНАЯ ЦЕНА руб.' => 'sellingPrice',//
            'Ширина в коробке мм' => 'sideToSideWidth',//
            'Высота в коробке мм' => 'topToBottomHeight',//
            'Глубина в коробке мм' => 'backToFrontSize',//
            'Материал' => 'material',//
            'Цвет' => 'complianceValue',//
            'Гарантия' => 'warrantyMonths',//
            'Описание' => 'description',//
            'Вес общий (кг)' => 'massWithPackaging',//
            'Тип конструкции' => 'installationMethod',//
            'Схема ' => 'mainSchema',//
            'Тип чаши' => 'shape',//Форма

        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->maxImages = 8;
        $this->mainImageField = 'Фото 0';
        $this->сonvertToMM = false; // false для мм
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
 * Добавление действия WordPress для импорта данных ComfortyUnitaz.
 */
add_action('wp_ajax_import_comforty_unitaz', 'import_comforty_unitaz');

/**
 * Функция для импорта данных ComfortyUnitaz.
 *
 * Эта функция создает экземпляр ComfortyUnitazImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_comforty_unitaz()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new ComfortyUnitazImporter(__DIR__ . '/uploads/ComfortyUnitaz.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
