<?php


/**
 * Класс для импорта данных для продуктов Sintesi.
 */
class SintesiImporter extends BaseTSVImporter
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
            'Бренд' => 'brand',
            'Артикул' => 'sku',
            'Название' => 'mainTitle',
            'Описание' => 'description',
            'Цена РРЦ, руб.' => 'sellingPrice',
            'Вес брутто, кг' => 'massWithPackaging',
            'Ширина, мм.' => 'sideToSideWidth',
            'Высота, мм.' => 'topToBottomHeight',
            'Нименование колекции SinteSi' => 'collection',
            'Тип' => 'productType',
            'Способ монтажа' => 'installationMethod',//-
            'Материал корпуса' => 'material',
            'Гарантийный срок' => 'warrantyMonths',
            'Страна-изготовитель' => 'country',
            'Цвет корпуса' => 'complianceValue',

            // Дополнительные поля из вашего нового списка
            'Подсветка' => 'illumination',
            'Вид подсветки' => 'viewIllumination',
            'Цвет подсветки' => 'illuminationColor',
            'Тип выключателя' => 'switchType',
            'Подогрев зеркала' => 'mirrorHeating',
            'Форма' => 'shape',
            'Покрытие корпуса' => 'bodyCoating',
            'Характеристики' => 'features',
            'Основная схема' => 'mainSchema',
            'Доп. схема' => 'additionalSchema'
        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->baseUrl = "https://mirvann.developing-site.ru/product_images/Sintesi/{sku}/";
        $this->fileDirPathTemplate = ABSPATH . "product_images/Sintesi/{sku}/";
        $this->maxImages = 15;
        $this->mainImageField = 'Фото 0';
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
 * Добавление действия WordPress для импорта данных Sintesi.
 */
add_action('wp_ajax_import_sintesi', 'import_sintesi');

/**
 * Функция для импорта данных Sintesi.
 *
 * Эта функция создает экземпляр SintesiImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_sintesi()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new SintesiImporter(__DIR__ . '/uploads/DREJA/Sintesi.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
