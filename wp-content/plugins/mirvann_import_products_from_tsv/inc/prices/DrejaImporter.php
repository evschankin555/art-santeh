<?php
/**
 * Класс для импорта данных для продуктов Dreja.
 */
class DrejaImporter extends BaseTSVImporter {
    /**
     * Конструктор класса.
     *
     * @param {string} $file_path - Путь к файлу для импорта.
     */
    public function __construct($file_path) {
        $this->file_path = $file_path;
        $this->steps[] = "Инициализация свойств продукта начата";

        $mapping = [
            'Раздел' => 'category',
            'Производитель' => 'brand',
            'Артикул' => 'sku',
            'Наименование' => 'mainTitle',
            'Описание' => 'description',
            'РРЦ' => 'sellingPrice',
            'Вес, кг Брутто' => 'massWithPackaging',
            'Глубина' => 'backToFrontSize',
            'Ширина' => 'sideToSideWidth',
            'Высота' => 'topToBottomHeight',
            'Коллекция' => 'collection',
            'Тип' => 'productType',
            'Монтаж' => 'installationMethod',//-
            'Материал корпуса' => 'material',
            'Фурнитура' => 'style',
            'Гарантия' => 'warrantyMonths',
            'Страна сборки' => 'country',
            'Цвет' => 'complianceValue',

            'Доп. функции' => 'additionalFunctions',
            'Система хранения' => 'storageSystem',
            'Тип лампы' => 'lampType',
        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->baseUrl = "https://mirvann.developing-site.ru/product_images/DREJA/{sku}/";
        $this->fileDirPathTemplate = ABSPATH . "product_images/DREJA/{sku}/";
        $this->maxImages = 30;
        $this->setImages = false;

        $this->initializeProductProperties($invertedMapping, false);
    }
    /**
     * Отображение данных из строки в ассоциативный массив.
     *
     * @param {array} $header - Заголовок файла.
     * @param {array} $line - Одна строка данных.
     * @return {array} - Ассоциативный массив данных.
     */
    protected function mapData($header, $line) {
        return array_combine($header, $line);
    }
    /**
     * Получение имени изображения по индексу.
     *
     * @param {int} $index - Индекс изображения.
     * @return {string|null} - Имя изображения или null, если не найдено.
     */
    protected function getImageName($index) {
        return isset($this->imageFiles[$index - 1]) ? $this->imageFiles[$index - 1] : null;
    }
    /**
     * Получение имени основного изображения.
     *
     * @return {string|null} - Имя основного изображения или null, если не найдено.
     */

    protected function getMainImageName() {
        return $this->getImageName(1);
    }

    /**
     * Загружает файлы по SKU с учётом сортировки.
     */
    public function loadFilesBySku() {
        parent::loadFilesBySku();  // Вызов родительского метода
        // Сортировка с учетом языка и специфичного слова
        usort($this->imageFiles, [$this, 'customSort']);
    }

    /**
     * Пользовательская функция сортировки.
     */
    protected function customSort($a, $b) {
        // Слово "Чертёж" в конец списка
        if (strpos($a, "Чертёж") !== false) return 1;
        if (strpos($b, "Чертёж") !== false) return -1;

        // Слово "Схема" в конец списка
        if (strpos($a, "Схема") !== false) return 1;
        if (strpos($b, "Схема") !== false) return -1;

        // Сначала английский язык, потом русский
        $pattern = '/[а-яА-ЯёЁ]/u';
        $isARussian = preg_match($pattern, $a);
        $isBRussian = preg_match($pattern, $b);

        if ($isARussian && !$isBRussian) return 1;
        if (!$isARussian && $isBRussian) return -1;

        // По возрастанию
        return strcmp($a, $b);
    }

    /**
     * Возвращает SKU для изображений, возможно урезанный.
     * Удаляет последний символ SKU, если это буква.
     *
     * @return string Урезанный или оригинальный SKU
     */
    protected function getSkuForImages() {
        $sku = $this->getSku();

        // Исключения для SKU
        if ($sku === '100120R' || $sku === '100120L') {
            return $sku;
        }

        // Удаляем последний символ, если это буква
        if (preg_match('/[A-Za-z]$/', $sku)) {
            return substr($sku, 0, -1);
        }

        return $sku;
    }

}

/**
 * Добавление действия WordPress для импорта данных Dreja.
 */
add_action('wp_ajax_import_dreja', 'import_dreja');

/**
 * Функция для импорта данных Dreja.
 *
 * Эта функция создает экземпляр DrejaImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_dreja() {
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new DrejaImporter(__DIR__ . '/uploads/DREJA.tsv');
    $importer->import($index);

    // Загрузка файлов по SKU
    $importer->loadFilesBySku();
    $importer->setProductImages();
    $result = $importer->getSteps();
    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
