<?php

abstract class BaseTSVImporter {
    protected $steps = [];
    protected $file_path;
    protected $data;
    protected $product_id;
    protected $productProperties;

    protected $skuField = null;
    protected $baseUrl = null;
    protected $fileDirPathTemplate;

    protected $mainImageField = null;
    protected $imageFields = [];

    protected $maxImages = 9; // По умолчанию
    protected $setImages = true; // По умолчанию
    protected $fullImage = false; // По умолчанию

    protected $pdfFiles = [];
    protected $imageFiles = [];

    protected $convertToMM = true; // по умолчанию для см, если false - для мм



    public function __construct($file_path) {
        $this->file_path = $file_path;
    }
    protected function initializeProductProperties(array $mapping, $logging = true)
    {
        $this->productProperties = new ProductProperties();
        $this->productProperties->loggingEnabled = $logging;
        $this->productProperties->setPropertiesFromArray($mapping, $this->steps);
        if ($logging) {
            $this->steps[] = "Инициализация свойств продукта завершена";
            $this->steps[] = "Свойство SKU: " . $this->productProperties->sku;
        }
    }

    abstract protected function mapData($header, $line);
    public function import($index) {
        try {
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
            $this->setNewMetaFields();
            //$this->setAdditionalMetaFields();

            if ($this->setImages) {
                $this->setProductImages();
            }
            return ['success' => true, 'steps' => $this->steps];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'steps' => $this->steps];
        }
    }
    public function getSteps() {
            return ['success' => true, 'steps' => $this->steps];
    }

    protected function readFile() {
        $lines = file($this->file_path, FILE_IGNORE_NEW_LINES);
        if (empty($lines)) {
            throw new Exception('TSV файл пуст или не найден.');
        }
        $this->steps[] = "Файл {$this->file_path} успешно прочитан";
    }
    protected function parseLine($index) {
        $lines = file($this->file_path, FILE_IGNORE_NEW_LINES);
        $header = explode("\t", array_shift($lines));
        $line = $lines[$index];
        $this->data = $this->mapData($header, explode("\t", $line));
    }
    protected function ensureTermsExist() {
        if (isset($this->productProperties->category) && isset($this->data[$this->productProperties->category])) {
            // Удаление всех старых категорий
            wp_remove_object_terms($this->product_id, get_terms(['taxonomy' => 'product_cat', 'fields' => 'ids']), 'product_cat');

            // Добавление и применение новой категории
            $category = $this->data[$this->productProperties->category];
            if (mb_strtolower($category, 'UTF-8') === $category) {
                $category = mb_convert_case($category, MB_CASE_TITLE, "UTF-8");
            }
            $this->ensureTermExists($category, 'product_cat');
            wp_set_object_terms($this->product_id, $category, 'product_cat');

            $this->steps[] = "Раздел '{$category}' создан или уже существует";
        }

        if (isset($this->productProperties->brand) && isset($this->data[$this->productProperties->brand])) {
            $this->ensureTermExists($this->data[$this->productProperties->brand], 'product_brand');
            $this->steps[] = "Бренд '{$this->data[$this->productProperties->brand]}' создан или уже существует";
        }
    }

    protected function ensureTermExists($term, $taxonomy) {
        if (!term_exists($term, $taxonomy)) {
            wp_insert_term($term, $taxonomy);
        }
    }
    protected function importOrUpdateProduct() {
        global $wpdb;
        if (empty($this->productProperties->sku)) {
            $this->steps[] = "Свойство поля SKU отсутствует.";
            throw new Exception("Прерывание: свойство поля SKU отсутствует.");
        }
        $this->steps[] = "Читаем свойство поля '" . $this->productProperties->sku . "'";

        $this->product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $this->data[$this->productProperties->sku]));
        $args = $this->getProductArgs();
        $product_link = get_permalink($this->product_id);

        if ($this->product_id) {
            $args['ID'] = $this->product_id;
            wp_update_post($args);
            $this->steps[] = "Продукт с артикулом '{$this->data[$this->productProperties->sku]}' обновлен. <a href='{$product_link}' target='_blank'>ID: {$this->product_id}</a>";
        } else {
            $this->product_id = wp_insert_post($args, true); // Второй аргумент true вернет WP_Error объект в случае ошибки
            if (is_wp_error($this->product_id)) {
                $this->steps[] = "Ошибка создания продукта: " . $this->product_id->get_error_message();
                return;
            }
            update_post_meta($this->product_id, '_sku', $this->data[$this->productProperties->sku]);
            $product_link = get_permalink($this->product_id);
            $this->steps[] = "Продукт с артикулом '{$this->data[$this->productProperties->sku]}' создан. <a href='{$product_link}' target='_blank'>ID: {$this->product_id}</a>";
        }
    }


    protected function getProductArgs($product_id = null) {
        $defaultDescription = 'Описание отсутствует'; // Значение по умолчанию

        $args = [
            'post_title'    => $this->data[$this->productProperties->mainTitle],
            'post_content'  => $this->data[$this->productProperties->description] ?: $defaultDescription, // Если описание отсутствует, используем значение по умолчанию
            'post_status'   => 'publish',
            'post_type'     => 'product',
            'tax_input'     => [
                'product_cat' => [$this->data[$this->productProperties->category]],
                'product_brand' => [$this->data[$this->productProperties->brand]]
            ]
        ];

        if ($product_id) {
            $args['ID'] = $product_id;
        }

        return $args;
    }

    protected function updateMetaFields($fields) {
        foreach ($fields as $key => $value) {
            update_post_meta($this->product_id, $key, $value);

            // Проверка сохраненного мета-тега
            $saved_value = get_post_meta($this->product_id, $key, true);
            if ($saved_value == $value) {
                $this->steps[] = "Мета-тег {$key} успешно сохранен с значением: {$value}.";
            } else {
                $this->steps[] = "Ошибка сохранения мета-тега {$key}. Ожидалось: {$value}, Получено: {$saved_value}.";
            }
        }
    }


    protected function setObjectTerms($tax, $terms) {
        wp_set_object_terms($this->product_id, $terms, $tax);
    }

    protected function addStep($message) {
        $this->steps[] = $message;
    }

    protected function getSku() {
        return $this->data[$this->skuField];
    }

    /**
     * Возвращает SKU для изображений, возможно урезанный.
     * Удаляет последний символ SKU, если это буква.
     *
     * @return string Урезанный или оригинальный SKU
     */
    protected function getSkuForImages() {
        $sku = $this->getSku();
        // Удаляем последний символ, если это буква
        if (preg_match('/[A-Za-z]$/', $sku)) {
            return substr($sku, 0, -1);
        }
        return $sku;
    }

    /**
     * Возвращает базовый URL для изображений.
     * Заменяет {sku} в базовом URL на урезанный или оригинальный SKU.
     *
     * @return string URL с замененным SKU
     */
    protected function getBaseUrl() {
        return str_replace('{sku}', $this->getSkuForImages(), $this->baseUrl);
    }

    protected function getMainImageName() {
        return $this->data[$this->mainImageField];
    }

    private function getImageUrl($base, $imageName) {
        if ($this->fullImage) {
            return $imageName;
        } else {
            return $base . $imageName;
        }
    }


    protected function getImageName($index) {
        return $this->data[$this->imageFields[$index - 2]];  // 2 потому что начинаем с "ФОТО 2"
    }
    /**
     * Задаёт изображения для продукта
     *
     * @return void
     */
    public function setProductImages() {
        $sku = $this->getSku();
        $base_url = $this->getBaseUrl();

        $this->setMainImage($base_url);
        $this->setGalleryImages($base_url);
    }

    /**
     * Задаёт основное изображение продукта
     *
     * @param string $base_url Базовый URL для изображений
     * @return void
     */
    private function setMainImage($base_url) {
        $main_image_name = $this->getMainImageName();
        $main_image_url = $this->getImageUrl($base_url, $main_image_name);
        $is_main_valid = $this->isImageUrlValid($main_image_url);

        if ($is_main_valid) {
            $this->steps[] = "Основное изображение валидно, переходим к загрузке.";
        } else {
            $this->steps[] = "Основное изображение невалидно, переходим к запасному.";
        }

        if ($is_main_valid) {
            $this->handleMainImageUpload($main_image_url, $main_image_name, $base_url);
        } else {
            $this->setFallbackMainImage($base_url);
        }
    }

    /**
     * Обрабатывает загрузку основного изображения
     *
     * @param string $main_image_url URL основного изображения
     * @param string $main_image_name Имя файла основного изображения
     * @return void
     */
    private function handleMainImageUpload($main_image_url, $main_image_name, $base_url) {
        $is_already_uploaded = get_post_meta($this->product_id, "_main_image_uploaded_{$main_image_name}", true);
        if ($is_already_uploaded) {
            $this->steps[] = "Основное изображение уже было загружено ранее.";
        } else {
            $main_image_id = $this->uploadImage($main_image_url);
            if ($main_image_id) {
                set_post_thumbnail($this->product_id, $main_image_id);
                $this->steps[] = "Основное изображение {$main_image_name} успешно загружено.";
                update_post_meta($this->product_id, "_main_image_uploaded_{$main_image_name}", 'yes');
            } else {
                $this->steps[] = "Не удалось загрузить основное изображение {$main_image_name}. Переходим к вызову setFallbackMainImage.";
                $this->setFallbackMainImage($base_url);
            }
        }
    }

    /**
     * Задаёт изображения для галереи продукта
     *
     * @param string $base_url Базовый URL для изображений
     * @return void
     */
    private function setGalleryImages($base_url) {
        $existing_gallery = get_post_meta($this->product_id, '_product_image_gallery', true);
        $gallery_images = !empty($existing_gallery) ? explode(',', $existing_gallery) : [];

        for ($i = 2; $i <= $this->maxImages; $i++) {
            $image_name = $this->getImageName($i);
            $this->handleGalleryImageUpload($base_url, $image_name, $gallery_images);
        }
    }

    /**
     * Обрабатывает загрузку изображения для галереи
     *
     * @param string $base_url Базовый URL для изображений
     * @param string $image_name Имя файла изображения
     * @param array $gallery_images Массив с уже загруженными изображениями
     * @return void
     */
    private function handleGalleryImageUpload($base_url, $image_name, &$gallery_images) {
        if (empty($image_name)) {
            $this->steps[] = "Имя изображения пустое, пропускаем.";
            return;
        }

        $image_url = $this->getImageUrl($base_url, $image_name);

        if (!$this->isImageUrlValid($image_url)) {
            $this->steps[] = "URL изображения недействителен {$image_name}.";
            return;
        }

        if (get_post_meta($this->product_id, "_image_uploaded_{$image_name}", true)) {
            $this->steps[] = "Изображение уже было загружено {$image_name}.";
            return;
        }

        $image_id = $this->uploadImage($image_url);
        if ($image_id) {
            update_post_meta($this->product_id, "_image_uploaded_{$image_name}", 'yes');
            $this->steps[] = "Изображение {$image_name} успешно загружено.";
            $gallery_images[] = $image_id;
            update_post_meta($this->product_id, '_product_image_gallery', implode(',', $gallery_images));
        }
    }

    /**
     * Устанавливает первое доступное изображение как основное, если основное изображение не доступно
     * @param string $base_url Базовый URL для изображений
     */

    private function setFallbackMainImage($base_url) {
        $this->steps[] = "Попытка установить запасное основное изображение.";

        for ($i = 2; $i <= $this->maxImages; $i++) {
            $fallback_image_name = $this->getImageName($i);
            if (empty($fallback_image_name)) {
                continue;
            }
            $fallback_image_url = $this->getImageUrl($base_url, $fallback_image_name);
            $is_fallback_valid = $this->isImageUrlValid($fallback_image_url);

            if ($is_fallback_valid) {
                $this->steps[] = "Запасное изображение валидно, переходим к загрузке.";
            } else {
                $this->steps[] = "Запасное изображение невалидно, пропускаем.";
                continue;
            }

            if ($is_fallback_valid && !get_post_meta($this->product_id, "_main_image_uploaded_{$fallback_image_name}", true)) {
                $fallback_image_id = $this->uploadImage($fallback_image_url);
                if ($fallback_image_id) {
                    set_post_thumbnail($this->product_id, $fallback_image_id);
                    $this->steps[] = "Запасное основное изображение {$fallback_image_name} успешно загружено.";
                    update_post_meta($this->product_id, "_main_image_uploaded_{$fallback_image_name}", 'yes');
                    break;
                }
            }
        }
    }


    /**
     * Проверяет доступность изображения по URL.
     * @param string $url URL изображения
     * @return bool Возвращает true, если изображение доступно, иначе false
     */
    private function isImageUrlValid($url) {
        $headers = @get_headers($url);  // Suppress warnings
        if (!$headers) {
            $this->steps[] = "Не удалось получить HTTP заголовки для {$url}.";
            return false;
        }
        $this->steps[] = "HTTP заголовки для {$url}: " . json_encode($headers);
        return stripos($headers[0], "200 OK") ? true : false;
    }


    private function uploadImage($image_url) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attach_id = media_sideload_image($image_url, $this->product_id, $this->data[$this->productProperties->mainTitle], 'id');
        if (is_wp_error($attach_id)) {
            $this->steps[] = "Ошибка загрузки изображения: " . $attach_id->get_error_message() . " (URL изображения: {$image_url})";
            return null;
        }
        return $attach_id;
    }

    /**
     * Устанавливает категории для продукта.
     */
    protected function setProductCategories() {
        $this->addStep("Поле категории '{$this->productProperties->category}'");
        $this->addStep("Значение категории '{$this->data[$this->productProperties->category]}'");

        $categories = [$this->data[$this->productProperties->category]];
        $this->setObjectTerms('product_cat', $categories);
        $this->addStep("Продукт добавлен в категории: " . implode(", ", $categories));
    }

    /**
     * Устанавливает метки для продукта.
     */
    protected function setProductTags() {
        $result = $this->setObjectTerms('product_tag', [$this->data[$this->productProperties->brand]]);
        if (is_wp_error($result)) {
            $this->addStep("Не удалось добавить бренд '{$this->data[$this->productProperties->brand]}' как метку товара: " . $result->get_error_message());
        } else {
            $this->addStep("Бренд '{$this->data[$this->productProperties->brand]}' добавлен как метка товара.");
        }
    }

    /**
     * Устанавливает цены для продукта.
     */
    protected function setProductPrices() {
        $regular_price = $this->sanitizePrice($this->data[$this->productProperties->sellingPrice]);
        $purchase_price = $this->sanitizePrice($this->data[$this->productProperties->purchasePrice]);
        $this->updateMetaFields([
            '_regular_price' => $regular_price,
            '_price' => $regular_price,
            '_purchase_price' => $purchase_price
        ]);

        $this->addStep("Цены установлены: продажная - {$regular_price}, закупочная - {$purchase_price}");
    }

    /**
     * Санитизирует строку с ценой, преобразуя её в числовое значение.
     *
     * @param string $priceString Строка с ценой.
     * @return float Преобразованное числовое значение.
     */
    protected function sanitizePrice($priceString) {
        // Удаление всех нечисловых символов, кроме точки и запятой
        $sanitized = preg_replace("/[^0-9,.]/", "", $priceString);
        // Замена запятой на точку
        $sanitized = str_replace(",", ".", $sanitized);
        // Преобразование строки в float
        return floatval($sanitized);
    }


    /**
     * Устанавливает атрибуты для продукта.
     */
    protected function setProductAttributes() {
        $this->updateMetaFields([
            'instruction_url_1' => $this->data[$this->productProperties->instructionURL]
        ]);
        $this->addStep("Атрибуты установлены, ссылка на инструкцию: {$this->data[$this->productProperties->instructionURL]}");
    }

    /**
     * Устанавливает размеры и вес для продукта.
     */
    protected function setProductDimensionsAndWeight() {
        $fields = [
            '_weight' => $this->productProperties->massWithPackaging,
            '_length' => $this->productProperties->backToFrontSize,
            '_width' => $this->productProperties->sideToSideWidth,
            '_height' => $this->productProperties->topToBottomHeight
        ];
        $updateFields = [];

        foreach ($fields as $meta_key => $property) {
            $value = $this->data[$property];

            if (empty($value)) {
                $this->addStep("Пустое значение для {$property}.");
                continue;
            }

            // Преобразование строки в числовое значение
            $value = preg_replace("/[^0-9,.]/", "", $value);
            $value = str_replace(",", ".", $value);

            if (!is_numeric($value)) {
                $this->addStep("Нечисловое значение для {$property}: {$value}.");
                continue;
            }

            $valueToSet = ($this->convertToMM || $meta_key !== '_weight') ? $value : $value / 10;

            $updateFields[$meta_key] = $valueToSet;
        }

        $this->updateMetaFields($updateFields);

        if (!empty($updateFields)) {
            $this->addStep("Размеры и вес установлены: " . json_encode($updateFields));
        }
    }

    /**
     * Устанавливает атрибуты размеров и веса для продукта.
     */
    protected function setProductDimensionAndWeightAttributes() {
        $fields = [
            'weight' => $this->productProperties->massWithPackaging,
            'width' => $this->productProperties->sideToSideWidth,
            'size' => [$this->productProperties->backToFrontSize, $this->productProperties->topToBottomHeight]
        ];
        $attributes = [];

        foreach ($fields as $attribute_key => $property) {
            $values = is_array($property) ? $property : [$property];
            $transformed_values = [];

            foreach ($values as $value_key) {
                $value = $this->data[$value_key];

                if (empty($value)) {
                    $this->addStep("Пустое значение для {$value_key}.");
                    continue;
                }

                // Преобразование строки в числовое значение
                $value = preg_replace("/[^0-9,.]/", "", $value);
                $value = str_replace(",", ".", $value);

                if (!is_numeric($value)) {
                    $this->addStep("Нечисловое значение для {$value_key}: {$value}.");
                    continue;
                }

                $valueToSet = ($this->convertToMM || $attribute_key !== 'weight') ? $value : $value / 10;

                $transformed_values[] = $valueToSet;
            }

            if (!empty($transformed_values)) {
                $attributes[$attribute_key] = $transformed_values;
            }
        }

        foreach ($attributes as $key => $values) {
            $this->setObjectTerms('pa_' . $key, $values);
        }

        if (!empty($attributes)) {
            $this->addStep("Атрибуты размеров и веса установлены: " . json_encode($attributes));
        }
    }

    /**
     * Устанавливает новые мета-данные для продукта.
     */
    protected function setNewMetaFields()
    {
        $newFields = $this->getNewFields();
        $fieldsToUpdate = $this->getFieldsToUpdate($newFields);

        if (!empty($fieldsToUpdate)) {
            $this->updateMetaFields($fieldsToUpdate);
            $this->addStep("Новые мета-данные установлены: " . implode(', ', array_keys($fieldsToUpdate)) . ".");
        }

    }

    /**
     * Получает массив новых мета-полей для продукта.
     *
     * @return array Массив новых мета-полей.
     */
    protected function getNewFields()
    {
        return [
            'collection',
            'productType',
            'washingMachineDepth',
            'installationMethod',
            'holeCount',
            'material',
            'bowlCount',
            'shape',
            'style',
            'complianceValue',
            'warrantyMonths',
            'country',
            'additionalFunctions',
            'storageSystem',
            'lampType',

            'illumination',
            'viewIllumination',
            'illuminationColor',
            'switchType',
            'mirrorHeating',
            'bodyCoating',
            'features',
            'mainSchema',
            'additionalSchema',

            'facadeMaterial',
            'facadeCoating',
            'sinkMaterial',
            'countertopMaterial',
            'storageSystemNew',
            'instructionNew',
            'video',

            //ComfortyRacoviny
            'mixerType',
            'spoutType',
            'mixerHeight',
            'spoutProjection',

            'modification',
            'setup',
            'volumeLiters',
            'overallLength'
        ];
    }

    /**
     * Получает поля для обновления из данных продукта.
     *
     * @param array $fields Массив мета-полей для проверки.
     * @return array Массив полей для обновления.
     */
    protected function getFieldsToUpdate($fields)
    {
        $fieldsToUpdate = [];
        $logMessage = [];

        foreach ($fields as $field) {
            if (isset($this->data[$this->productProperties->$field]) && !empty($this->data[$this->productProperties->$field])) {
                $fieldsToUpdate[$field] = $this->data[$this->productProperties->$field];
                $logMessage[] = "{$field}: {$this->data[$this->productProperties->$field]}";
                if ($field == 'complianceValue') {
                    $this->setComplianceValueAsColorAttribute();
                }
                if ($field == 'collection') {
                    $this->setCollectionAsProductAttribute();
                }
            }
        }

        return $fieldsToUpdate;
    }

    /**
     * Устанавливает дополнительные мета-данные для продукта, которых нет в методе setNewMetaFields.
     */
    protected function setAdditionalMetaFields()
    {
        $existingFields = $this->getNewFields();
        $allFields = array_keys($this->data);

        $additionalFields = array_diff($allFields, $existingFields);
        $fieldsToUpdate = $this->getFieldsToUpdate($additionalFields);

        if (!empty($fieldsToUpdate)) {
            $this->updateMetaFields($fieldsToUpdate);
            $this->addStep("Дополнительные мета-данные установлены: " . implode(', ', array_keys($fieldsToUpdate)) . ".");
        }
    }

    /**
     * Загружает файлы по SKU.
     *
     * Метод сканирует директорию, указанную в $dirPath, и заполняет массивы $pdfFiles и $imageFiles
     * соответствующими файлами. Массивы затем сортируются по возрастанию.
     */
    public function loadFilesBySku() {
        $sku = $this->getSkuForImages();
        $dirPath = str_replace('{sku}', $sku, $this->fileDirPathTemplate);
        $this->steps[] = "Директория {$dirPath} найдена, перебираем.";

        if (is_dir($dirPath)) {
            $files = scandir($dirPath);

            foreach ($files as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($ext, ['pdf'])) {
                    $this->pdfFiles[] = $file;
                } elseif (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                    $this->imageFiles[] = $file;
                }
            }

            // Сортировка массивов по возрастанию
            sort($this->pdfFiles);
            sort($this->imageFiles);
        } else {
            $this->steps[] = "Директория {$dirPath} не найдена.";
        }
    }

    /**
     * Устанавливает complianceValue как атрибут цвета продукта.
     *
     * Метод проверяет наличие термина в таксономии 'pa_color'. Если термина не существует,
     * он создаёт его. Затем устанавливает этот термин для текущего продукта.
     */
    protected function setComplianceValueAsColorAttribute() {
        // Проверка наличия термина
        $term = term_exists($this->data[$this->productProperties->complianceValue], 'pa_color');

        // Если термин не существует, создаем его
        if (0 === $term || null === $term) {
            wp_insert_term(
                $this->data[$this->productProperties->complianceValue], // Название термина
                'pa_color'  // Таксономия
            );
        }

        // Устанавливаем термин для продукта
        $this->setObjectTerms('pa_color', [$this->data[$this->productProperties->complianceValue]]);
    }

    /**
     * Устанавливает collection как атрибут коллекции продукта.
     *
     * Метод проверяет наличие термина в таксономии 'pa_collections'. Если термина не существует,
     * он создаёт его. Затем устанавливает этот термин для текущего продукта.
     */
    protected function setCollectionAsProductAttribute() {
        $term = term_exists($this->data[$this->productProperties->collection], 'pa_collections');
        $name = $this->data[$this->productProperties->brand] . ' ' . $this->data[$this->productProperties->collection];
        $result = null;
        if (0 === $term || null === $term) {
            $result = wp_insert_term($name, 'pa_collections');
            if (is_wp_error($result)) {
                $errorMessage = $result->get_error_message();
                $this->steps[] = "Для бренда и коллекции {$name} вылезла ошибка {$errorMessage}.";
            }
        }

        $setTermsResult = $this->setObjectTerms('pa_collections', [$name]);

        if (!$setTermsResult && is_wp_error($result)) {
            $errorMessage = $result->get_error_message();
            $this->steps[] = "Для бренда и коллекции {$name} вылезла ошибка {$errorMessage}.";
        }
    }





}