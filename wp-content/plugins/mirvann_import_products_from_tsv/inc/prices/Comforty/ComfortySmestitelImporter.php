<?php


/**
 * Класс для импорта данных для продуктов ComfortySmestitel.
 */
class ComfortySmestitelImporter extends BaseTSVImporter
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
            'Назначение ' => 'category',
            'Производитель ' => 'brand',
            'Артикул' => 'sku',
            'Наименование' => 'mainTitle',
            'РОЗНИЧНАЯ ЦЕНА МСК .руб' => 'sellingPrice',
            'Ширина в коробке мм' => 'sideToSideWidth',
            'Высота в коробке мм' => 'topToBottomHeight',
            'Глубина в коробке мм' => 'backToFrontSize',
            'Материал корпуса ' => 'material',
            'Цвет' => 'complianceValue',
            'Гарантия' => 'warrantyMonths',
            'Описание' => 'description',
            'Вес общий (кг)' => 'massWithPackaging',
            'По месту установки ' => 'installationMethod',
            'Схема ' => 'mainSchema',

            'Вид смесителя ' => 'mixerType',
            'Тип излива ' => 'spoutType',
            'Высота смесителя/Душевой системы мм' => 'mixerHeight',
            'Вынос излива мм' => 'spoutProjection',
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
 * Добавление действия WordPress для импорта данных ComfortySmestitel.
 */
add_action('wp_ajax_import_comforty_smestitel', 'import_comforty_smestitel');

/**
 * Функция для импорта данных ComfortySmestitel.
 *
 * Эта функция создает экземпляр ComfortySmestitelImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_comforty_smestitel()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new ComfortySmestitelImporter(__DIR__ . '/uploads/ComfortySmestitel.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
