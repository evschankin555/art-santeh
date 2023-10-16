<?php
class ProductDataRetriever extends ProductDataFormatter {
    public function __construct($product_id) {
        $meta_keys = [
            '_regular_price' => ['label' => 'Обычная цена', 'suffix' => ' руб'],
            '_price' => ['label' => 'Цена со скидкой', 'suffix' => ' руб'],
            '_purchase_price' => ['label' => 'Закупочная цена', 'suffix' => ' руб'],
            'instruction_url_1' => ['label' => 'Ссылка на инструкцию', 'suffix' => ''],
            '_weight' => ['label' => 'Вес', 'suffix' => ' кг'],
            '_length' => ['label' => '', 'suffix' => ' см'],
            '_width' => ['label' => '', 'suffix' => ' см'],
            '_height' => ['label' => '', 'suffix' => ' см'],

            'collection' => ['label' => 'Коллекция', 'suffix' => ''],
            'productType' => ['label' => 'Тип продукта', 'suffix' => ''],
            'washingMachineDepth' => ['label' => 'Глубина стиральной машины', 'suffix' => ' мм'],
            'installationMethod' => ['label' => 'Тип установки', 'suffix' => ''],
            'holeCount' => ['label' => 'Количество отверстий', 'suffix' => ''],
            'material' => ['label' => 'Материал', 'suffix' => ''],
            'bowlCount' => ['label' => 'Количество чаш', 'suffix' => ''],
            'shape' => ['label' => 'Форма', 'suffix' => ''],
            'style' => ['label' => 'Стиль', 'suffix' => ''],
            'complianceValue' => ['label' => 'Цвет', 'suffix' => ''],
            'warrantyMonths' => ['label' => 'Гарантия', 'suffix' => ' месяцев'],
            'country' => ['label' => 'Страна', 'suffix' => ''],
            'additionalFunctions' => ['label' => 'Дополнительные функции', 'suffix' => ''],
            'storageSystem' => ['label' => 'Система хранения', 'suffix' => ''],
            'lampType' => ['label' => 'Тип лампы', 'suffix' => ''],

            'illumination' => ['label' => 'Подсветка', 'suffix' => ''],
            'viewIllumination' => ['label' => 'Вид подсветки', 'suffix' => ''],
            'illuminationColor' => ['label' => 'Цвет подсветки', 'suffix' => ''],
            'switchType' => ['label' => 'Тип выключателя', 'suffix' => ''],
            'mirrorHeating' => ['label' => 'Подогрев зеркала', 'suffix' => ''],
            'bodyCoating' => ['label' => 'Покрытие корпуса', 'suffix' => ''],
            'features' => ['label' => 'Характеристики', 'suffix' => ''],
            'mainSchema' => ['label' => 'Основная схема', 'suffix' => ''],
            'additionalSchema' => ['label' => 'Доп. схема', 'suffix' => ''],
            // Новые ключи
            'facadeMaterial' => ['label' => 'Материал фасада', 'suffix' => ''],
            'facadeCoating' => ['label' => 'Покрытие фасада', 'suffix' => ''],
            'sinkMaterial' => ['label' => 'Материал раковины', 'suffix' => ''],
            'countertopMaterial' => ['label' => 'Материал столешницы', 'suffix' => ''],
            'storageSystemNew' => ['label' => 'Новая система хранения', 'suffix' => ''],
            'instructionNew' => ['label' => 'Новая инструкция', 'suffix' => ''],
            'video' => ['label' => 'Видеоролик', 'suffix' => ''],

            //ComfortyRacoviny
            'mixerType' => ['label' => 'Вид смесителя', 'suffix' => ''],
            'spoutType' => ['label' => 'Тип излива', 'suffix' => ''],
            'mixerHeight' => ['label' => 'Высота смесителя/Душевой системы', 'suffix' => ' мм'],
            'spoutProjection' => ['label' => 'Вынос излива', 'suffix' => ' мм'],

            //ABBER
            'modification' => ['label' => 'Модификация', 'suffix' => ''],
            'setup' => ['label' => 'Установка', 'suffix' => ''],
            'volumeLiters' => ['label' => 'Объем', 'suffix' => ' л'],
            'overallLength' => ['label' => 'Длина', 'suffix' => ' см'],

        ];

        $selectedKeys = [
            '_weight',
            'collection',
            'style',
            'productType',
            'dimensions',
            'washingMachineDepth',
            'bowlCount',
            'holeCount',
            'installationMethod',
            'material',
            'complianceValue',
            'shape',
            'warrantyMonths',
            'country',
            'instruction_url_1',
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

            // Новые ключи
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
        ];

        $renamedLabels = [
            'washingMachineDepth' => 'Глубина стиральной машины',
            'instruction_url_1' => 'Ссылка на инструкцию',
            'complianceValue' => 'Цвет'
        ];

        $this->excludedKeys = [
            '_regular_price',
            '_price',
            '_purchase_price',
            '_length',
            '_width',
            '_height',
            'lampType',
            'viewIllumination',
            'illuminationColor',
            'switchType',
            'mirrorHeating',
            'bodyCoating',
            'features',
            'mainSchema',
            'additionalSchema'
        ];

        parent::__construct($product_id, $meta_keys, $selectedKeys, $renamedLabels);
    }
}


