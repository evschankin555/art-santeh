<?php
class ProductDataFormatter {
    protected $meta_keys;
    protected $product_id;
    protected $selectedKeys;
    protected $renamedLabels;

    public function __construct($product_id, $meta_keys, $selectedKeys = [], $renamedLabels = []) {
        $this->product_id = $product_id;
        $this->meta_keys = $meta_keys;
        $this->selectedKeys = $selectedKeys;
        $this->renamedLabels = $renamedLabels;
    }

    protected function getFormattedData($key, $value, $meta_keys) {
        $suffix = $meta_keys[$key]['suffix'];

        if ($key === 'warrantyMonths' && !is_numeric($value)) {
            $suffix = '';
        }

        // Проверка, является ли значение URL-ом
        if (substr($value, 0, 4) === 'http' || substr($value, 0, 5) === 'https') {
            $value = '<a href="' . $value . '" target="_blank" rel="nofollow">Открыть</a>';
        }

        return [
            'label' => $meta_keys[$key]['label'],
            'value' => $value . $suffix
        ];
    }

    protected function formatDimensions(&$data) {
        if (isset($data['_length'], $data['_width'], $data['_height'])) {
            $dimensions = [];

            if (!empty(trim($data['_length']['value'], " см"))) {
                $dimensions[] = trim($data['_length']['value']);
            }

            if (!empty(trim($data['_width']['value'], " см"))) {
                $dimensions[] = trim($data['_width']['value']);
            }

            if (!empty(trim($data['_height']['value'], " см"))) {
                $dimensions[] = trim($data['_height']['value']);
            }

            if (!empty($dimensions)) {
                $data['dimensions'] = [
                    'label' => 'Габариты',
                    'value' => implode(' × ', $dimensions)
                ];
            }

            unset($data['_length'], $data['_width'], $data['_height']);
        }
    }



    /**
     * Фильтрует и возвращает выбранные данные из исходного массива.
     *
     * @param array $data Исходный массив данных.
     * @param array $selectedKeys Массив ключей для выбора.
     * @param array $renamedLabels Массив для переименования меток.
     * @return array Отфильтрованный массив данных.
     */
    protected function filterSelectedData($data, $selectedKeys, $renamedLabels) {
        $selectedData = [];
        foreach ($selectedKeys as $key) {
            if (isset($data[$key]) && !empty($data[$key]['value'])) {
                // Проверка на специальные значения
                if (in_array(strtolower(trim($data[$key]['value'])), ["мм", "см", "кг"])) {
                    continue;
                }

                if (isset($renamedLabels[$key])) {
                    $data[$key]['label'] = $renamedLabels[$key];
                }
                $selectedData[$key] = $data[$key];
            }
        }
        return $selectedData;
    }


    public function getProductData() {
        $data = [];
        foreach ($this->meta_keys as $key => $details) {
            $value = get_post_meta($this->product_id, $key, true);
            if (isset($value)) {
                $data[$key] = $this->getFormattedData($key, $value, $this->meta_keys);
            }
        }
        $this->formatDimensions($data);
        return $data;
    }
    /**
     * Фильтрует и возвращает выбранные данные из исходного массива.
     *
     * @param array $data Исходный массив данных.
     * @param array $excludedKeys Массив ключей для исключения.
     * @param array $renamedLabels Массив для переименования меток.
     * @return array Отфильтрованный массив данных.
     */
    protected function filterExcludedData($data, $excludedKeys, $renamedLabels) {
        $filteredData = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, $excludedKeys) && !empty($value['value'])) {
                // Проверка на специальные значения
                if (in_array(strtolower(trim($value['value'])), ["мм", "см", "кг"])) {
                    continue;
                }

                if (isset($renamedLabels[$key])) {
                    $value['label'] = $renamedLabels[$key];
                }
                $filteredData[$key] = $value;
            }
        }
        return $filteredData;
    }

    public function getSelectedProductData() {
        $data = $this->getProductData();
        return $this->filterExcludedData($data, $this->excludedKeys, $this->renamedLabels);
    }
}