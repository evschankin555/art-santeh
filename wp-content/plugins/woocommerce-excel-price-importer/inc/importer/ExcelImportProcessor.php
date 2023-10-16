<?php
class ExcelImportProcessor {
    /**
     * @var DatabaseModel
     */
    private $databaseModel;

    /**
     * @var array
     */
    private $currentSettings;

    /**
     * @var array
     */
    private $uploadedExcel;

    public function __construct(DatabaseModel $databaseModel) {
        $this->databaseModel = $databaseModel;
        add_action('wp_ajax_excel_import', [$this, 'handleExcelImport']);
        add_action('wp_ajax_get_excel_import_setting', [$this, 'handleGetExcelImportSetting']);
    }


    public function fetchExcelData() {
        $this->uploadedExcel = get_option('last_uploaded_excel');

        if (!$this->uploadedExcel) {
            wp_send_json_error(['message' => 'Excel file not found']);
            wp_die();
        }
    }

    private function loadSettingsById($settingId) {
        $this->currentSettings = [];

        // Загружаем основные настройки
        $this->currentSettings['main'] = $this->databaseModel->getSettingById($settingId);

        // Загружаем расширенные настройки
        $this->currentSettings['extended'] = $this->databaseModel->getExtendedSettingById($settingId);

        // Загружаем информацию о файле
        $this->currentSettings['fileData'] = $this->databaseModel->getFileDataBySettingId($settingId);
        // Загружаем редактируемые поля
        $editableFields = [];
        foreach ($this->currentSettings['extended'] as $extendedSetting) {
            $nameParam = $extendedSetting['name_param'];
            $fieldValue = $this->databaseModel->getEditableField($settingId, $nameParam);
            if ($fieldValue !== null) {  // добавляем условие для исключения null значений
                $editableFields[$nameParam] = $fieldValue;
            }
        }
        $this->currentSettings['editableFields'] = $editableFields;
    }


    public function checkAndLoadSheet() {
        // Загружаем информацию о последнем загруженном Excel-файле и текущих настройках
        $this->fetchExcelData();
        $settingId = $this->currentSettings['main']['id'];
        $this->loadSettingsById($settingId);

        $filePath = $this->uploadedExcel['file'];
        $sheetNameFromSettings = $this->currentSettings['fileData']['sheet_name'];

        $excelReader = new ExcelReader($filePath);
        $sheets = $excelReader->readSheetNames();

        if (in_array($sheetNameFromSettings, $sheets)) {
            // Лист с нужным именем существует
            return true;
        } else {
            // Лист с нужным именем не найден
            return false;
        }
    }
    public function handleGetExcelImportSetting() {
        // Получение settingId из POST данных
        $settingId = sanitize_text_field($_POST['settingId']);

        // Загрузка настроек и данных Excel
        $this->loadSettingsById($settingId);
        $this->fetchExcelData();
        $sheetLoaded = $this->checkAndLoadSheet();

        if ($sheetLoaded) {
            $skuExists = $this->checkSKUExists();
            if ($skuExists) {
                wp_send_json_success([
                    'settings' => $this->currentSettings,
                    'message' => 'Настройки и лист успешно загружены',
                    'skuExists' => true,
                    'sheetExists' => true
                ]);
            } else {
                wp_send_json_success([
                    'settings' => $this->currentSettings,
                    'message' => 'Поле Артикула обязательно должно быть задано!',
                    'skuExists' => false,
                    'sheetExists' => true
                ]);
            }
        } else {
            wp_send_json_error([
                'message' => 'В выбранном файле Excel нет листа, который сохранен в настройках',
                'sheetExists' => false
            ]);
        }

        wp_die();
    }


    public function checkSKUExists() {
        foreach ($this->currentSettings['extended'] as $extendedSetting) {
            if ($extendedSetting['name_param'] === 'sku' && !empty($extendedSetting['header_value'])) {
                return true;
            }
        }
        return false;
    }

    public function handleExcelImport() {
        $currentIndex = sanitize_text_field($_POST['index']);
        $settingId = sanitize_text_field($_POST['settingId']);
        $this->loadSettingsById($settingId);
        // Создание экземпляра UniversalExcelImporter и выполнение нужных операций
        $importer = new UniversalExcelImporter($this->currentSettings);

        $importer->import($currentIndex);

        $result = $importer->getSteps();

        wp_send_json_success($result);

        wp_die();
    }

}
