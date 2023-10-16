<?php

class SettingHandler {

    /**
     * @var DatabaseModel
     */
    private $databaseModel;

    public function __construct(DatabaseModel $databaseModel) {
        $this->databaseModel = $databaseModel;
        add_action('wp_ajax_handle_create_setting', [$this, 'handleRequest']);
        add_action('wp_ajax_handle_delete_setting', [$this, 'handleDelete']);
        add_action('wp_ajax_fetch_setting_data', [$this, 'fetchSettingData']);
        add_action('wp_ajax_fetch_excel_data', [$this, 'fetchExcelData']);
        add_action('wp_ajax_fetch_excel_sheet_data', [$this, 'fetchExcelSheetData']);
        add_action('wp_ajax_save_selected_sheet', [$this, 'saveSelectedSheet']);
        add_action('wp_ajax_fetch_saved_settings', [$this, 'fetchSavedSettings']);
        add_action('wp_ajax_save_selected_field', [$this, 'saveSelectedField']);
        add_action('wp_ajax_wepi_save_changed_field', [$this, 'saveEditableField']);
        add_action('wp_ajax_fetch_field_data', [$this, 'fetchEditableField']);
        add_action('wp_ajax_fetch_excel_sheet_row_count', [$this, 'fetchExcelSheetRowCount']);
        add_action('wp_ajax_fetch_all_editable_field_data', [$this, 'fetchAllEditableFieldData']);
        add_action('wp_ajax_save_selected_setting_id', [$this, 'saveSelectedSettingId']);

    }

    public function handleRequest() {
        $settingBrand = sanitize_text_field($_POST['setting_brand']);
        $settingName = sanitize_text_field($_POST['setting_name']);

        // Проверки на валидность

        $settingId = $this->databaseModel->createSetting($settingBrand, $settingName);

        if ($settingId) {
            $this->databaseModel->createDefaultExtendedSettings($settingId);

            wp_send_json_success([
                'setting_id' => $settingId,
                'setting_brand' => $settingBrand,
                'setting_name' => $settingName
            ]);
        } else {
            wp_send_json_error(['message' => 'Не удалось создать настройку']);
        }
        wp_die();
    }


    public function handleDelete() {
        $settingId = sanitize_text_field($_POST['setting_id']);

        // Проверки на валидность

        $result = $this->databaseModel->deleteSetting($settingId);

        if ($result) {
            wp_send_json_success(['message' => 'Удалено успешно']);
        } else {
            wp_send_json_error(['message' => 'Не удалось удалить настройку']);
        }
        wp_die();
    }
    public function fetchSettingData() {
        $settingId = sanitize_text_field($_POST['setting_id']);

        if (empty($settingId)) {
            wp_send_json_error(['message' => 'Не указан ID настройки']);
            wp_die();
        }

        $settingData = $this->databaseModel->getSettingById($settingId);

        if ($settingData) {
            $extendedData = $this->databaseModel->getExtendedSettingById($settingId);
            $settingData['extended'] = $extendedData;
            wp_send_json_success($settingData);
        } else {
            wp_send_json_error(['message' => 'Не удалось получить данные']);
        }
        wp_die();
    }
    public function fetchExcelData() {
        // Попытка извлечь кешированные данные
        $cachedData = get_option('cached_excel_data');

        if ($cachedData) {
            wp_send_json_success($cachedData);
            wp_die();
        }

        $uploadedExcel = get_option('last_uploaded_excel');

        if (!$uploadedExcel) {
            wp_send_json_error(['message' => 'Excel file not found']);
            wp_die();
        }

        $filePath = $uploadedExcel['file'];

        // Получение имени файла из URL и замена подстроки
        $urlParts = explode('/', $uploadedExcel['url']);
        $originalFileName = end($urlParts);
        $modifiedFileName = str_replace('_file_for_upload_', '-', $originalFileName);

        $excelReader = new ExcelReader($filePath);
        $sheets = $excelReader->readSheetNames();

        $data = ['fileName' => $modifiedFileName, 'sheets' => $sheets];

        // Кеширование данных
        update_option('cached_excel_data', $data);

        wp_send_json_success($data);
        wp_die();
    }


    public function fetchExcelSheetData() {
        ini_set('memory_limit', '512M');

        $sheetName = sanitize_text_field($_POST['sheetName']);

        $cachedFirstRowData = get_option("cached_first_row_data_$sheetName");
        if ($cachedFirstRowData !== false) {
            wp_send_json_success(['firstRowData' => $cachedFirstRowData]);
            wp_die();
        }

        $uploadedExcel = get_option('last_uploaded_excel');
        if (!$uploadedExcel) {
            wp_send_json_error(['message' => 'Excel file not found']);
            wp_die();
        }

        $filePath = $uploadedExcel['file'];
        $excelReader = new ExcelReader($filePath);

        try {
            $excelReader->setActiveSheetByName($sheetName);
            $firstRowData = $excelReader->readFirstRow($sheetName);
            update_option("cached_first_row_data_$sheetName", $firstRowData);
            wp_send_json_success(['firstRowData' => $firstRowData]);
        } catch (Exception $e) {
            wp_send_json_error(['message' => 'Could not read the sheet']);
        }
        wp_die();
    }
    public function saveSelectedSheet() {
        $sheetName = sanitize_text_field($_POST['sheetName']);
        $settingsId = intval($_POST['settingsId']);

        $uploadedExcel = get_option('last_uploaded_excel');
        if (!$uploadedExcel) {
            wp_send_json_error(['message' => 'Excel file not found']);
            wp_die();
        }
        $filePath = $uploadedExcel['file'];

        // Здесь логика для сохранения в базу данных
        $result = $this->databaseModel->saveOrUpdateFileData($settingsId, $filePath, $sheetName);

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => 'Failed to save']);
        }
        wp_die();
    }
    public function fetchSavedSettings() {
        $settingsId = intval($_POST['settingsId']);

        // Получение данных из новой таблицы в базе данных
        $fileData = $this->databaseModel->getFileDataBySettingId($settingsId);

        if ($fileData) {
            // Если значение 'sheet_name' найдено в базе данных
            $sheetName = $fileData['sheet_name'];
            wp_send_json_success(['sheetName' => $sheetName]);
        } else {
            // Если значение не найдено, возвращаем особый ответ
            wp_send_json_success(['sheetName' => null, 'message' => 'No value set']);
        }
        wp_die();
    }
    public function saveSelectedField() {
        $selectedValue = sanitize_text_field($_POST['selectedValue']);
        $settingsId = intval($_POST['settingsId']);
        $selectName = sanitize_text_field($_POST['selectName']);

        // Здесь логика для сохранения в базу данных
        $result = $this->databaseModel->saveOrUpdateSelectedField($settingsId, $selectName, $selectedValue);

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => 'Failed to save']);
        }
        wp_die();
    }
    public function saveEditableField() {
        $fieldText = sanitize_text_field($_POST['updatedValue']);
        $settingsId = intval($_POST['settingsId']);
        $nameParam = sanitize_text_field($_POST['name_param']);

        $result = $this->databaseModel->saveOrUpdateEditableField($settingsId, $nameParam, $fieldText);

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => 'Failed to save']);
        }
        wp_die();
    }
    public function fetchEditableField() {
        $nameParam = sanitize_text_field($_POST['name_param']);
        $settingsId = intval($_POST['settingsId']);

        $fieldText = $this->databaseModel->getEditableField($settingsId, $nameParam);

        if ($fieldText) {
            wp_send_json_success(['fieldText' => $fieldText]);
        } else {
            wp_send_json_error(['message' => 'Field not found']);
        }
        wp_die();
    }
    public function fetchExcelSheetRowCount() {
        $sheetName = sanitize_text_field($_POST['sheetName']);

        $uploadedExcel = get_option('last_uploaded_excel');
        if (!$uploadedExcel) {
            wp_send_json_error(['message' => 'Excel file not found']);
            wp_die();
        }

        $filePath = $uploadedExcel['file'];
        $uniqueCacheKey = md5($filePath . '_' . $sheetName);

        $cachedRowCount = get_option("cached_row_count_$uniqueCacheKey");
        if ($cachedRowCount !== false) {
            wp_send_json_success(['rowCount' => $cachedRowCount]);
            wp_die();
        }

        $excelReader = new ExcelReader($filePath);

        try {
            $rowCount = $excelReader->countRowsInSheet($sheetName);
            update_option("cached_row_count_$uniqueCacheKey", $rowCount);
            wp_send_json_success(['rowCount' => $rowCount]);
        } catch (Exception $e) {
            wp_send_json_error(['message' => 'Could not count the rows in the sheet']);
        }
        wp_die();
    }


    public function fetchAllEditableFieldData() {
        try {
            $settingsId = intval($_POST['settingsId']);
            $fieldsData = $this->databaseModel->getAllEditableFields($settingsId);
            wp_send_json_success(['fields' => $fieldsData]);
        } catch (Exception $e) {
            wp_send_json_error(['message' => 'Could not fetch the editable fields']);
        }
        wp_die();
    }

    public function saveSelectedSettingId() {
        try {
            // Получение settingId из POST данных
            $settingId = intval($_POST['settingId']);

            // Сохраняем выбранный settingId как опцию в базе данных
            update_option('current_selected_setting_id', $settingId);

            // Отправляем успех
            wp_send_json_success(['message' => 'Setting ID успешно сохранен']);
        } catch (Exception $e) {
            // Отправляем ошибку
            wp_send_json_error(['message' => 'Не удалось сохранить Setting ID']);
        }
        wp_die();
    }


}
