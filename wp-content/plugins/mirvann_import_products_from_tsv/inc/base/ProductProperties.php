<?php
class ProductProperties
{
    public $category = null;
    public $brand = null;
    public $mainTitle = null;
    public $description = null;
    public $sellingPrice = null;
    public $purchasePrice = null;
    public $instructionURL = null;
    public $massWithPackaging = null;
    public $backToFrontSize = null;
    public $sideToSideWidth = null;
    public $topToBottomHeight = null;
    public $sku = null;
    public $loggingEnabled = true;
    public $collection = null; // Коллекция (Серия)
    public $productType = null; // Тип продукта (раковина, полупьедестал, etc.)
    public $washingMachineDepth = null; // Глубина стиральной машины, мм
    public $installationMethod = null; // Метод установки (на стену, на столешницу, etc.)
    public $holeCount = null; // Количество отверстий
    public $material = null; // Материал
    public $bowlCount = null; // Количество чаш
    public $shape = null; // Форма
    public $style = null; // Стиль
    public $complianceValue = null; // Значение соответствия
    public $warrantyMonths = null; // Гарантия (в месяцах)
    public $country = null; // Страна

    //Dreja
    public $additionalFunctions = null; // Доп. функции
    public $storageSystem = null; // Система хранения
    public $lampType = null; // Тип лампы

    //Sintesi
    public $illumination = null; // Подсветка
    public $viewIllumination = null; // Вид подсветки
    public $illuminationColor = null; // Цвет подсветки
    public $switchType = null; // Тип выключателя
    public $mirrorHeating = null; // Подогрев зеркала
    public $bodyCoating = null; // Покрытие корпуса
    public $features = null; // Характеристики
    public $mainSchema = null; // Основная схема
    public $additionalSchema = null; // Доп. схема

    //Comforty
    public $facadeMaterial = null; // Материал фасада
    public $facadeCoating = null; // Покрытие фасада
    public $sinkMaterial = null; // Материал раковины
    public $countertopMaterial = null; // Материал столешницы
    public $storageSystemNew = null; // Система хранения (новая)
    public $instructionNew = null; // Инструкция (новая)
    public $video = null; // Видеоролик

    //ComfortyRacoviny
    public $mixerType = null;
    public $spoutType = null;
    public $mixerHeight = null;
    public $spoutProjection = null;

    //ABBER
    public $modification = null;
    public $setup = null;
    public $volumeLiters = null;
    public $overallLength = null;

    public $mainImage = null;
    public $additionalImages_1 = null;
    public $additionalImages_2 = null;
    public $additionalImages_3 = null;
    public $additionalImages_4 = null;
    public $additionalImages_5 = null;
    public $additionalImages_6 = null;
    public $additionalImages_7 = null;
    public $additionalImages_8 = null;
    public $additionalImages_9 = null;
    public $additionalImages_10 = null;
    public $additionalImages_11 = null;
    public $additionalImages_12 = null;
    public $additionalImages_13 = null;
    public $additionalImages_14 = null;
    public $additionalImages_15 = null;
    public $additionalImages_16 = null;
    public $additionalImages_17 = null;
    public $additionalImages_18 = null;
    public $additionalImages_19 = null;
    public $additionalImages_20 = null;
    public $field_1 = null;
    public $field_2 = null;
    public $field_3 = null;
    public $field_4 = null;
    public $field_5 = null;
    public $field_6 = null;
    public $field_7 = null;
    public $field_8 = null;
    public $field_9 = null;
    public $field_10 = null;
    public $field_11 = null;
    public $field_12 = null;
    public $field_13 = null;
    public $field_14 = null;
    public $field_15 = null;
    public $field_16 = null;
    public $field_17 = null;
    public $field_18 = null;
    public $field_19 = null;
    public $field_20 = null;
    public $fieldLabel_1 = null;
    public $fieldLabel_2 = null;
    public $fieldLabel_3 = null;
    public $fieldLabel_4 = null;
    public $fieldLabel_5 = null;
    public $fieldLabel_6 = null;
    public $fieldLabel_7 = null;
    public $fieldLabel_8 = null;
    public $fieldLabel_9 = null;
    public $fieldLabel_10 = null;
    public $fieldLabel_11 = null;
    public $fieldLabel_12 = null;
    public $fieldLabel_13 = null;
    public $fieldLabel_14 = null;
    public $fieldLabel_15 = null;
    public $fieldLabel_16 = null;
    public $fieldLabel_17 = null;
    public $fieldLabel_18 = null;
    public $fieldLabel_19 = null;
    public $fieldLabel_20 = null;

    public function setPropertiesFromArray(array $data, &$steps)
    {
        // Отображение старых ключей на новые
        $keyMapping = [
            '_regular_price' => 'sellingPrice',
            '_weight' => 'massWithPackaging',
            '_length' => 'backToFrontSize',
            '_width' => 'sideToSideWidth',
            '_height' => 'topToBottomHeight'
        ];

        foreach ($data as $key => $value) {
            // Проверяем, существует ли ключ в $keyMapping
            $mappedKey = $keyMapping[$key] ?? $key;

            if (property_exists($this, $mappedKey)) {
                $this->$mappedKey = $value;
                if ($this->loggingEnabled) {
                    $steps[] = "Свойство {$mappedKey} установлено: {$value}";
                }
            } else {
                if ($this->loggingEnabled) {
                    $steps[] = "Свойство {$mappedKey} не установлено: нет такого свойства в классе";
                }
            }
        }

        if ($this->loggingEnabled) {
            $steps[] = "Инициализация свойств продукта завершена";
        }
    }
}