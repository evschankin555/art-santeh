<?php


/**
 * Класс для импорта данных для продуктов ABBER.
 */
class ABBERImporter extends BaseTSVImporter
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
            'Категория' => 'category',//
            'Бренд' => 'brand',//
            'Артикул' => 'sku',//
            'Наименование' => 'mainTitle',//
            'РРЦ ₽' => 'sellingPrice',//
            'Ширина, см' => 'sideToSideWidth',//
            'Высота, см' => 'topToBottomHeight',//
            'Глубина, см' => 'backToFrontSize',//
            'Материал' => 'material',//
            'Цвет' => 'complianceValue',//
            'Гарантия' => 'warrantyMonths',//
            //'Описание' => 'description',//
            'Вес в упаковке, кг' => 'massWithPackaging',//
            'СХЕМА' => 'mainSchema',//
            'Страна' => 'country',//
            'Форма' => 'shape',//

            //новые

            'Модификация' => 'modification',
            'Установка' => 'setup',
            'Объем, л' => 'volumeLiters',
            'Длина, см' => 'overallLength'

        ];

        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул';
        $this->maxImages = 8;
        $this->mainImageField = 'Фото 0';
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
 * Добавление действия WordPress для импорта данных ABBER.
 */
add_action('wp_ajax_import_abber', 'import_abber');

/**
 * Функция для импорта данных ABBER.
 *
 * Эта функция создает экземпляр ABBERImporter и вызывает его методы для выполнения импорта.
 * Она также отправляет результат в формате JSON.
 */
function import_abber()
{
    $index = $_POST['index'];  // Индекс для импорта

    // Создание экземпляра импортера и импорт данных
    $importer = new ABBERImporter(__DIR__ . '/uploads/ABBER.tsv');
    $result = $importer->import($index);

    // Отправка результата в формате JSON
    echo json_encode($result);

    // Завершение выполнения скрипта
    wp_die();
}
