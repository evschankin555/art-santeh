<?php
/**
 * Класс для работы с базой данных в контексте Excel Price Importer
 */
class DatabaseModel {
    /**
     * @private
     * @var wpdb Экземпляр wpdb для взаимодействия с базой данных
     */
    private $wpdb;

    /**
     * @private
     * @var string Имя таблицы для хранения настроек
     */
    private $table_name = 'wp_excel_price_settings';
    private $extended_table_name = 'wp_excel_price_settings_extended';
    private $file_table_name = 'wp_excel_file_settings';
    private $editable_field_table_name = 'wp_editable_field_settings';

    /**
     * Конструктор класса. Инициализирует экземпляр wpdb и создаёт таблицу, если её нет.
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->initTable();
        $this->initExtendedTable();
        $this->initFileTable();
        $this->initEditableFieldTable();
    }

    /**
     * Создаёт таблицу для хранения настроек, если таковая не существует.
     *
     * @private
     */
    private function initTable() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT(9) NOT NULL AUTO_INCREMENT,
            brand_name VARCHAR(255) NOT NULL,
            setting_name VARCHAR(255) NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private function initExtendedTable() {
        $tableExists = $this->wpdb->get_var("SHOW TABLES LIKE '{$this->extended_table_name}'") === $this->extended_table_name;

        if (!$tableExists) {
            $charset_collate = $this->wpdb->get_charset_collate();
            $sql = "CREATE TABLE {$this->extended_table_name} (
            id INT(9) NOT NULL AUTO_INCREMENT,
            settings_id INT(9) NOT NULL,
            name_param VARCHAR(64) NOT NULL,
            header_value TEXT NOT NULL,
            FOREIGN KEY (settings_id) REFERENCES {$this->table_name}(id),
            PRIMARY KEY  (id)
        ) $charset_collate;";

            if ($this->wpdb->query($sql) === false) {
                echo "Ошибка создания расширенной таблицы";
                return false;
            }
        }

        return true;
    }

    /**
     * Создаёт таблицу для хранения данных о файле и выборе страницы, если таковая не существует.
     *
     * @private
     */
    private function initFileTable() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $this->file_table_name (
            id INT(9) NOT NULL AUTO_INCREMENT,
            settings_id INT(9) NOT NULL,
            file_path TEXT NOT NULL,
            sheet_name VARCHAR(255) NOT NULL,
            FOREIGN KEY (settings_id) REFERENCES $this->table_name(id),
            PRIMARY KEY  (id)
        ) $charset_collate;";

        if ($this->wpdb->query($sql) === false) {
            echo "Ошибка создания таблицы для хранения данных о файлах";
            return false;
        }

        return true;
    }

    private function initEditableFieldTable() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $this->editable_field_table_name (
        id INT(9) NOT NULL AUTO_INCREMENT,
        settings_id INT(9) NOT NULL,
        name_param VARCHAR(64) NOT NULL,
        field_text TEXT NOT NULL,
        FOREIGN KEY (settings_id) REFERENCES $this->table_name(id),
        PRIMARY KEY  (id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    /**
     * Возвращает все настройки из базы данных.
     *
     * @public
     * @return array Массив настроек
     */
    public function getSettings() {
        return $this->wpdb->get_results("SELECT * FROM $this->table_name", ARRAY_A);
    }

    public function createSetting($settingBrand, $settingName) {
        $result = $this->wpdb->insert($this->table_name, [
            'brand_name' => $settingBrand,
            'setting_name' => $settingName,
        ]);

        if ($result) {
            return $this->wpdb->insert_id;
        }

        return false;
    }
    public function deleteSetting($settingId) {
        // Удаляем связанные строки из расширенной таблицы
        $this->wpdb->delete($this->extended_table_name, ['settings_id' => $settingId]);

        // Удаляем зависимые строки из wp_excel_file_settings
        $this->wpdb->delete('wp_excel_file_settings', ['settings_id' => $settingId]);

        // Удаляем связанные строки из таблицы editable_field
        $this->wpdb->delete($this->editable_field_table_name, ['settings_id' => $settingId]);

        // Удаляем основную настройку
        $result = $this->wpdb->delete($this->table_name, ['id' => $settingId]);

        return $result !== false;
    }


    public function createDefaultExtendedSettings($settingId) {
        $defaultParams = [
            'category',
            'brand',
            'sku',
            'mainTitle',
            'description',
            '_regular_price',
            '_price',
            '_purchase_price',
            'instruction_url_1',
            '_weight',
            '_length',
            '_width',
            '_height',
            'collection',
            'material',
            'style',
            'complianceValue',
            'warrantyMonths',
            'country',
            'mainImage'
        ];

        for ($i = 1; $i <= 20; $i++) {
            $defaultParams[] = "additionalImages_" . $i;
        }

        for ($i = 1; $i <= 20; $i++) {
            $defaultParams[] = "field_" . $i;
        }


        foreach ($defaultParams as $param) {
            $result = $this->wpdb->insert($this->extended_table_name, [
                'settings_id' => $settingId,
                'name_param' => $param,
                'header_value' => ''
            ]);

            if ($result === false || $result === 0) {
                echo "Ошибка вставки: " . $this->wpdb->last_error;
                return false;
            }
        }

        return true;
    }

    public function getSettingById($settingId) {
        $query = $this->wpdb->prepare("SELECT * FROM $this->table_name WHERE id = %d", $settingId);
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function getExtendedSettingById($settingId) {
        $query = $this->wpdb->prepare("SELECT * FROM $this->extended_table_name WHERE settings_id = %d", $settingId);
        return $this->wpdb->get_results($query, ARRAY_A);
    }
    public function saveOrUpdateFileData($settingsId, $filePath, $sheetName) {
        $table = $this->file_table_name;
        $row = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $table WHERE settings_id = %d", $settingsId), ARRAY_A);

        if ($row) {
            // Обновление
            $updated = $this->wpdb->update(
                $table,
                ['file_path' => $filePath, 'sheet_name' => $sheetName],
                ['settings_id' => $settingsId]
            );
            return $updated !== false;
        } else {
            // Вставка новой записи
            return $this->wpdb->insert(
                $table,
                ['settings_id' => $settingsId, 'file_path' => $filePath, 'sheet_name' => $sheetName]
            );
        }
    }
    public function getFileDataBySettingId($settingsId) {
        $table = $this->file_table_name;
        $query = $this->wpdb->prepare("SELECT * FROM $table WHERE settings_id = %d", $settingsId);
        return $this->wpdb->get_row($query, ARRAY_A);
    }
    public function saveOrUpdateSelectedField($settingsId, $selectName, $selectedValue) {
        $table = $this->extended_table_name;
        $row = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM $table WHERE settings_id = %d AND name_param = %s",
            $settingsId, $selectName
        ), ARRAY_A);

        if ($row) {
            // Обновление
            $updated = $this->wpdb->update(
                $table,
                ['header_value' => $selectedValue],
                ['settings_id' => $settingsId, 'name_param' => $selectName]
            );
            return $updated !== false;
        } else {
            // Вставка новой записи
            return $this->wpdb->insert(
                $table,
                ['settings_id' => $settingsId, 'name_param' => $selectName, 'header_value' => $selectedValue]
            );
        }
    }
    public function saveOrUpdateEditableField($settingsId, $nameParam, $fieldText) {
        $table = $this->editable_field_table_name;
        $row = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM $table WHERE settings_id = %d AND name_param = %s",
            $settingsId, $nameParam
        ), ARRAY_A);
        if ($row) {
            // Обновление
            $updated = $this->wpdb->update(
                $table,
                ['field_text' => $fieldText],
                ['settings_id' => $settingsId, 'name_param' => $nameParam]
            );
            return $updated !== false;
        } else {
            // Вставка новой записи
            $inserted = $this->wpdb->insert(
                $table,
                ['settings_id' => $settingsId, 'name_param' => $nameParam, 'field_text' => $fieldText]
            );
            return $inserted !== false;
        }
    }

    public function getEditableField($settingsId, $nameParam) {
        $table = $this->editable_field_table_name;
        $row = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM $table WHERE settings_id = %d AND name_param = %s",
            $settingsId, $nameParam
        ), ARRAY_A);
        if ($row) {
            return $row['field_text'];
        } else {
            return null;
        }
    }


    public function getAllEditableFields($settingsId) {
        $table = $this->editable_field_table_name;
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare("SELECT * FROM $table WHERE settings_id = %d", $settingsId),
            ARRAY_A
        );
        if ($results) {
            return $results;
        } else {
            throw new Exception("No fields found");
        }
    }

}
?>
