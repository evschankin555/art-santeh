<?php


/**
 * Класс для импорта данных для продуктов Berges.
 */
class BergesImporter extends BaseTSVImporter
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
            'Раздел' => 'category',//-
            'Бренд' => 'brand',//-
            'Артикул' => 'sku',//-
            'Наименование' => 'mainTitle',//-
            ' Цена' => 'sellingPrice',//-
            'Ширина, см' => 'sideToSideWidth',//-
            'Высота, см' => 'topToBottomHeight',//-
            'Длина, см' => 'backToFrontSize',//-
           // 'Материал' => 'material',//
            'Цвет' => 'complianceValue',//-
            'ОПИСАНИЕ' => 'description',//-
            'Масса, кг (вес товара вместе с упаковкой)' => 'massWithPackaging',//-
            'Страна' => 'country',//-
            'Серия' => 'collection',//-
            'Гарантия' => 'warrantyMonths',//-
            'Чертёж' => 'additionalSchema',//-

        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->maxImages = 12;
        $this->mainImageField = 'Главное фото';
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
        return $this->data["Изображение ".($index-1)];
    }
    protected function sanitizePrice($priceString) {
        // Удаление всех нечисловых символов, кроме точки, запятой и пробела
        $sanitized = preg_replace("/[^0-9,. ]/", "", $priceString);
        // Замена пробелов на пустую строку
        $sanitized = str_replace(" ", "", $sanitized);
        // Замена запятой на точку
        $sanitized = str_replace(",", ".", $sanitized);
        // Преобразование строки в float
        return floatval($sanitized);
    }

}

/**
 * Добавление действия WordPress для импорта данных Berges.
 */
add_action('wp_ajax_import_berges', 'import_berges');

/**
 * Функция для импорта данных berges.
 *
 * Эта функция создает экземпляр BergesImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_berges()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new BergesImporter(__DIR__ . '/uploads/Berges.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
