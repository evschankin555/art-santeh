<?php


/**
 * Класс для импорта данных для продуктов ComfortyRacoviny.
 */
class ComfortyRacovinyImporter extends BaseTSVImporter
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
            'Раздел' => 'category',
            'Производитель ' => 'brand',
            'Артикул' => 'sku',
            'Наименование' => 'mainTitle',
            'РОЗНИЧНАЯ ЦЕНА руб.' => 'sellingPrice',
            'Ширина изделия мм' => 'sideToSideWidth',
            'Высота изделия мм' => 'topToBottomHeight',
            'Глубина изделия мм' => 'backToFrontSize',
            'Материал' => 'material',
            'Цвет' => 'complianceValue',
            'Гарантия' => 'warrantyMonths',
            'Описание' => 'description',
            'Вес общий (кг)' => 'massWithPackaging',
            'Вариант установки ' => 'installationMethod',
            'Схема' => 'mainSchema',
            'Форма чаши ' => 'shape',
        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->maxImages = 11;
        $this->mainImageField = 'Фото 0';
        $this->сonvertToMM = false; // false для мм


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
 * Добавление действия WordPress для импорта данных ComfortyRacoviny.
 */
add_action('wp_ajax_import_comforty_racoviny', 'import_comforty_racoviny');

/**
 * Функция для импорта данных ComfortyRacoviny.
 *
 * Эта функция создает экземпляр ComfortyRacovinyImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_comforty_racoviny()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new ComfortyRacovinyImporter(__DIR__ . '/uploads/ComfortyRacoviny.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
