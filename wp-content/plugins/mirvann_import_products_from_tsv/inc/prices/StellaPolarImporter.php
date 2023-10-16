<?php



class StellaPolarImporter extends BaseTSVImporter {
    public function __construct($file_path) {
        $this->file_path = $file_path;
        $this->steps[] = "Инициализация свойств продукта начата";

        $mapping = [
            'Раздел' => 'category',
            'Производитель' => 'brand',
            'Основное название товара' => 'mainTitle',
            'Описание' => 'description',
            'Цена продажи' => 'sellingPrice',
            'Закупочная цена' => 'purchasePrice',
            'ссылка на инструкцию' => 'instructionURL',
            'Масса изделия с упаковкой (кг)' => 'massWithPackaging',
            'Размер изделия от задней стенки до лицевой стороны' => 'backToFrontSize',
            'Ширина изделия по крайним точкам' => 'sideToSideWidth',
            'Высота изделия по крайним точкам' => 'topToBottomHeight',
            'Артикул товара поставщика для обновления остатков и цен' => 'sku',

            // Новые поля
            'Коллекция (Серия)' => 'collection',
            'раковина | полупьедестал | пьедестал | кронштейны | ножки и опоры | полотенцедержатель | крепеж' => 'productType',
            'Глубина стиральной машины, мм' => 'washingMachineDepth',
            'на стену | на столешницу | на пол | на мебель' => 'installationMethod',
            'без отверстий | с 1 отверстием | с 2 отверстиями | с 3 отверстиями' => 'holeCount',
            'искусственный камень | искусственный мрамор | стекло | фарфор | фаянс |  сталь' => 'material',
            '1 чаша | 2 чаши' => 'bowlCount',
            'квадратная | круглая | овальная | прямоугольная | округлая | треугольная' => 'shape',
            'классический (ретро) | современный стиль (Hi-Tech) | традиционный' => 'style',
            'значение соответствия' => 'complianceValue',
            ' (месяцев)' => 'warrantyMonths',
            'страна' => 'country',

        ];
        $invertedMapping = array_flip($mapping);

        $this->skuField = 'Артикул товара поставщика для обновления остатков и цен';
        $this->baseUrl = "https://mirvann.developing-site.ru/product_images/Stella Polar/{sku}/";
        $this->mainImageField = 'ФОТО 1';
        $this->imageFields = ["ФОТО 2", "ФОТО 3", ''];
        $this->maxImages = 9;

        $this->initializeProductProperties($invertedMapping, false);
    }

    protected function mapData($header, $line) {
        return array_combine($header, $line);
    }
    protected function getImageName($index) {
        return $this->data["ФОТО ".$index];
    }

}
/*
class StellaPolarImporter {
    private $steps = [];
    private $file_path;
    private $data;
    private $product_id;

    public function __construct($file_path) {
        $this->file_path = $file_path;
    }

    public function import($index) {
        $this->readFile();
        $this->parseLine($index);
        $this->ensureTermsExist();
        $this->importOrUpdateProduct();
        $this->setProductCategories();
        $this->setProductPrices();
        $this->setProductAttributes();
        $this->setProductDimensionsAndWeight();
        $this->setProductDimensionAndWeightAttributes();
        $this->setProductTags();
        $this->setProductImages();

        return ['success' => true, 'steps' => $this->steps];
    }

    private function readFile() {
        $lines = file($this->file_path, FILE_IGNORE_NEW_LINES);
        if (empty($lines)) {
            throw new Exception('TSV файл пуст или не найден.');
        }
        $this->steps[] = "Файл {$this->file_path} успешно прочитан";
    }

    private function parseLine($index) {
        $lines = file($this->file_path, FILE_IGNORE_NEW_LINES);
        $header = explode("\t", array_shift($lines));
        $line = $lines[$index];
        $this->data = array_combine($header, explode("\t", $line));
    }

    private function ensureTermsExist() {
        $this->ensureTermExists($this->data['Раздел'], 'product_cat');
        $this->steps[] = "Раздел '{$this->data['Раздел']}' создан или уже существует";

        $this->ensureTermExists($this->data['Производитель'], 'product_brand');
        $this->steps[] = "Бренд '{$this->data['Производитель']}' создан или уже существует";
    }

    private function ensureTermExists($term, $taxonomy) {
        if (!term_exists($term, $taxonomy)) {
            wp_insert_term($term, $taxonomy);
        }
    }

    private function importOrUpdateProduct() {
        global $wpdb;
        $this->product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $this->data['Артикул товара поставщика для обновления остатков и цен']));
        $args = $this->getProductArgs();

        if ($this->product_id) {
            $args['ID'] = $this->product_id;
            wp_update_post($args);
            $this->steps[] = "Продукт с артикулом '{$this->data['Артикул товара поставщика для обновления остатков и цен']}' обновлен";
        } else {
            $this->product_id = wp_insert_post($args);
            update_post_meta($this->product_id, '_sku', $this->data['Артикул товара поставщика для обновления остатков и цен']);
            $this->steps[] = "Продукт с артикулом '{$this->data['Артикул товара поставщика для обновления остатков и цен']}' создан";
        }
    }

    private function getProductArgs($product_id = null) {
        $args = [
            'post_title'    => $this->data['Основное название товара'],
            'post_content'  => $this->data['Описание'],
            'post_status'   => 'publish',
            'post_type'     => 'product',
            'tax_input'     => [
                'product_cat' => [$this->data['Раздел']],
                'product_brand' => [$this->data['Производитель']]
            ]
        ];

        if ($product_id) {
            $args['ID'] = $product_id;
        }

        return $args;
    }

    private function setProductCategories() {
        $categories = [$this->data['Раздел'], 'Раковины над стиральной машиной'];
        wp_set_object_terms($this->product_id, $categories, 'product_cat');
        $this->steps[] = "Продукт добавлен в категории: " . implode(", ", $categories);
    }
    private function setProductPrices() {
        update_post_meta($this->product_id, '_regular_price', $this->data['Цена продажи']);
        update_post_meta($this->product_id, '_price', $this->data['Цена продажи']);
        update_post_meta($this->product_id, '_purchase_price', $this->data['Закупочная цена']);
        $this->steps[] = "Цены установлены: продажная - {$this->data['Цена продажи']}, закупочная - {$this->data['Закупочная цена']}";
    }


    private function setProductAttributes() {
        $attributes = [
            'instruction_url_1' => $this->data['ссылка на инструкцию']
        ];

        foreach ($attributes as $key => $value) {
            update_post_meta($this->product_id, $key, $value);
        }

        $this->steps[] = "Атрибуты установлены, ссылка на инструкцию: {$this->data['ссылка на инструкцию']}";
    }
    private function setProductDimensionsAndWeight() {
        // Установка мета-данных для размеров и веса
        update_post_meta($this->product_id, '_weight', $this->data['Масса изделия с упаковкой (кг)']);
        update_post_meta($this->product_id, '_length', $this->data['Размер изделия от задней стенки до лицевой стороны']);
        update_post_meta($this->product_id, '_width', $this->data['Ширина изделия по крайним точкам']);
        update_post_meta($this->product_id, '_height', $this->data['Высота изделия по крайним точкам']);
        $this->steps[] = "Размеры и вес установлены.";
    }

    private function setProductDimensionAndWeightAttributes() {
        // Добавление размеров и веса как атрибутов
        $attributes = [
            'weight' => [$this->data['Масса изделия с упаковкой (кг)']],
            'width'  => [$this->data['Ширина изделия по крайним точкам']],
            'size'   => [$this->data['Размер изделия от задней стенки до лицевой стороны'], $this->data['Высота изделия по крайним точкам']]
        ];

        foreach ($attributes as $key => $values) {
            wp_set_object_terms($this->product_id, $values, 'pa_' . $key);
        }
        $this->steps[] = "Атрибуты размеров и веса установлены.";
    }
    private function setProductTags() {
        // Добавление бренда как метки товара
        $result = wp_set_object_terms($this->product_id, $this->data['Производитель'], 'product_tag', true);

        // Проверяем результат
        if (is_wp_error($result)) {
            $this->steps[] = "Не удалось добавить бренд '{$this->data['Производитель']}' как метку товара: " . $result->get_error_message();
        } else {
            $this->steps[] = "Бренд '{$this->data['Производитель']}' добавлен как метка товара.";
        }
    }
    private function setProductImages()
    {
        $sku = $this->data['Артикул товара поставщика для обновления остатков и цен'];
        $base_url = "https://mirvann.developing-site.ru/product_images/Stella Polar/{$sku}/";

        // Загрузка основного изображения
        $main_image_name = $this->data['ФОТО 1'];
        $main_image_url = $base_url . $main_image_name;

        if (!get_post_meta($this->product_id, "_main_image_uploaded_{$main_image_name}", true)) {
            $main_image_id = $this->uploadImage($main_image_url);
            if ($main_image_id) {
                set_post_thumbnail($this->product_id, $main_image_id);
                $this->steps[] = "Основное изображение {$main_image_name} успешно загружено.";
                update_post_meta($this->product_id, "_main_image_uploaded_{$main_image_name}", 'yes');
                return;  // Загрузим только одно изображение за раз
            } else {
                $this->steps[] = "Не удалось загрузить основное изображение {$main_image_name}.";
            }
        }

        // Получаем существующие изображения из галереи
        $existing_gallery = get_post_meta($this->product_id, '_product_image_gallery', true);
        $gallery_images = !empty($existing_gallery) ? explode(',', $existing_gallery) : [];

        // Загрузка дополнительных изображений для галереи
        for ($i = 2; $i <= 9; $i++) {
            $image_name_key = "ФОТО {$i}";
            $image_name = $this->data[$image_name_key];
            $image_url = $base_url . $image_name;

            if (!empty($image_name) && !get_post_meta($this->product_id, "_image_uploaded_{$image_name}", true)) {
                $image_id = $this->uploadImage($image_url);
                if ($image_id) {
                    update_post_meta($this->product_id, "_image_uploaded_{$image_name}", 'yes');
                    $this->steps[] = "Изображение {$image_name} успешно загружено.";

                    // Добавляем новое изображение в массив галереи
                    $gallery_images[] = $image_id;

                    // Обновляем мета-поле галереи новым списком изображений
                    update_post_meta($this->product_id, '_product_image_gallery', implode(',', $gallery_images));

                    break; // Загрузим только одно изображение за раз
                } else {
                    $this->steps[] = "Не удалось загрузить изображение {$image_name}.";
                }
            }
        }
    }

    private function uploadImage($image_url) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attach_id = media_sideload_image($image_url, $this->product_id, $this->data['Основное название товара'], 'id');
        if (is_wp_error($attach_id)) {
            $this->steps[] = "Ошибка загрузки изображения: " . $attach_id->get_error_message();
            return null;
        }
        return $attach_id;
    }
}
*/
add_action('wp_ajax_import_stella_polar', 'import_stella_polar');

function import_stella_polar() {
    $index = $_POST['index'];
    $importer = new StellaPolarImporter(__DIR__ . '/uploads/Stella-Polar.tsv');
    $result = $importer->import($index);

    echo json_encode($result);
    wp_die();
}
