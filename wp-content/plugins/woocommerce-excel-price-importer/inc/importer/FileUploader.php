<?php
class FileUploader {

    public function __construct() {
        add_action('wp_ajax_upload_excel_file', [$this, 'uploadExcelFile']);
        add_action('wp_ajax_reset_excel_upload', [$this, 'resetExcelUpload']);
    }

    /**
     * Uploads the Excel file via AJAX.
     *
     * @return void
     */
    public function uploadExcelFile() {
        if (!empty($_FILES['file'])) {
            $overrides = ['test_form' => false];
            $upload = wp_handle_upload($_FILES['file'], $overrides);

            if ($upload && !isset($upload['error'])) {
                $fileSize = $_FILES['file']['size']; // размер файла в байтах

                // Округляем и конвертируем размер
                if ($fileSize >= 1024 * 1024) {
                    $fileSize = round($fileSize / (1024 * 1024), 1) . 'MB';
                } else {
                    $fileSize = round($fileSize / 1024, 1) . 'KB';
                }

                // Сохраняем информацию о файле
                update_option('last_uploaded_excel', $upload);
                setcookie('last_uploaded_excel', json_encode($upload), time() + (86400 * 30), "/");

                $excelReader = new ExcelReader($upload['file']);
                $headers = $excelReader->readFirstRow();

                // Возвращаем информацию о файле и его размере
                $upload['size'] = $fileSize;
                $upload['headers'] = $headers;
                wp_send_json_success($upload);
            } else {
                wp_send_json_error($upload['error']);
            }
        }

        wp_die();
    }


    /**
     * Resets the Excel file upload via AJAX.
     *
     * @return void
     */
    public function resetExcelUpload() {
        // Получение списка всех имен листов
        $uploadedExcel = get_option('last_uploaded_excel');
        if ($uploadedExcel) {
            $filePath = $uploadedExcel['file'];

            // Проверка на существование файла и его доступность для чтения
            if (!file_exists($filePath) || !is_readable($filePath)) {
                wp_send_json_error('File does not exist or is not readable');
                wp_die();
            }

            try {
                $excelReader = new ExcelReader($filePath);
                $sheetNames = $excelReader->readSheetNames();

                // Удаление кешированных данных для каждого листа
                foreach ($sheetNames as $sheetName) {
                    delete_option("cached_row_count_$sheetName");
                    delete_option("cached_first_row_data_$sheetName");
                }
            } catch (\Exception $e) {
                // Если возникло исключение, этот блок кода выполнится
                // и пропустит операции, связанные с ExcelReader и sheetNames
            }

            // Удаление кешированных данных для fetchExcelData
            delete_option('cached_excel_data');

            delete_option('last_uploaded_excel');
            wp_send_json_success('Excel upload reset successful');
            wp_die();
        }
    }


}
