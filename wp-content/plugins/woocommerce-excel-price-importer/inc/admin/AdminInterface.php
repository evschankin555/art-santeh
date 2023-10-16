<?php
/**
 * Файл для административного интерфейса плагина
 *
 * @package WooCommerce Excel Price Importer
 */

class AdminInterface {
    private $lastUploadedExcel = [];
    private $displayFileName = 'Файл не загружен';
    private $hiddenClassForSpan = 'hidden';
    private $hiddenClassForInput = '';
    private $hiddenClassForButton = 'hidden';

    /**
     * Инициализация данных о последнем загруженном файле.
     *
     * @return void
     */
    private function initLastUploadedFileData() {
        $this->lastUploadedExcel = get_option('last_uploaded_excel', []);
        $this->displayFileName = isset($this->lastUploadedExcel['file']) ? basename($this->lastUploadedExcel['file']) : 'Файл не загружен';
        $this->hiddenClassForSpan = empty($this->lastUploadedExcel) ? 'hidden' : '';
        $this->hiddenClassForInput = empty($this->lastUploadedExcel) ? '' : 'hidden';
        $this->hiddenClassForButton = empty($this->lastUploadedExcel) ? 'hidden' : '';
    }

    /**
     * Отрисовка интерфейса администратора.
     *
     * @return void
     */
    public function render() {
        $this->removeNotifications();
        $this->initLastUploadedFileData();
        $this->initializeSettingsModal();
        $this->initializeSettingsEditModal();
        // $this->display_yandex_oauth_status();

        $dataExelAttribute = empty($this->lastUploadedExcel) ? '0' : '1';

        ?>
        <script src="https://yastatic.net/s3/passport-sdk/autofill/v1/sdk-suggest-with-polyfills-latest.js"></script>

        <div class="wrap">
            <h1 class="wp-heading-inline">WooCommerce Excel Price Importer</h1>

            <!-- Кнопка для загрузки файла -->
            <div class="postbox">
                <div class="inside">
                    <h2 class="hndle">Загрузить Excel файл</h2>
                    <input type="file" id="excelFile" name="excelFile" class="regular-text <?php echo $this->hiddenClassForInput; ?>" data-exel="<?php echo $dataExelAttribute; ?>">
                    <?php
                    // Проверяем, есть ли загруженный файл и отображаем его как ссылку для скачивания
                    if (!empty($this->lastUploadedExcel)) {
                        $fileUrl = $this->lastUploadedExcel['url'];
                        echo '<a id="linkExel" href="' . esc_url($fileUrl) . '" download>' . esc_html($this->displayFileName) . '</a>';
                    } else {
                        echo '<span id="uploadedFileName" class="' . $this->hiddenClassForSpan . '">' . esc_html($this->displayFileName) . '</span>';
                    }
                    ?>
                    <button id="resetUpload" type="button" class="button <?php echo $this->hiddenClassForButton; ?>">Сбросить</button>
                    <span id="uploadeExelStatus" style="color: gray;"></span>
                </div>
            </div>

            <div class="settingsDropdownContainer hidden">
                <?php $this->renderSettingsDropdown(); ?>
            </div>

            <div class="importContainer hidden">
                <div class="postbox">
                    <div class="inside">
                        <h2 class="hndle">Импорт</h2>
                        <button id="wepi_startImport" type="button" class="button button-primary">Запустить процесс импорта</button>
                    </div>
                </div>
            </div>

            <div>
                <div class="postbox">
                    <div class="inside">
                        <div id="import_status"></div>
                        <div id="logs"></div>
                    </div>
                </div>
            </div>

        </div>

        <style>
            .hidden {
                display: none !important;
            }
            #uploadedFileName {
                padding: 6px 10px 6px 0px;
                display: inline-block;
                color: #2271b1;
                font-weight: bold;
            }
            #linkExel{
                padding: 0 10px;
                font-weight: bold;
                line-height: 24px;
                display: inline-block;
            }
        </style>
        <?php
    }


    /**
     * Удаление уведомлений других плагинов
     */
    private function removeNotifications() {
        ?>
        <style>
            /* Скрывает все уведомления на странице вашего плагина */
            .wrap .notice {
                display: none !important;
            }
        </style>
        <?php
    }

    /**
     * Отображает HTML разметку для выпадающего списка выбора настроек.
     * В этом списке представлены все доступные настройки для импорта Excel файлов.
     *
     * @private
     */
    private function renderSettingsDropdown() {
        $databaseModel = new DatabaseModel();
        $settings = $databaseModel->getSettings();
        $savedSettingId = get_option('current_selected_setting_id');  // Получаем сохраненный settingId
        $selectedBrandName = '';
        $selectedSettingName = '';

        foreach ($settings as $setting) {
            if ($setting['id'] == $savedSettingId) {
                $selectedBrandName = $setting['brand_name'];
                $selectedSettingName = $setting['setting_name'];
                break;
            }
        }

        ?>
        <div class="postbox">
            <div class="inside">
                <h2 class="hndle">Выбрать настройку</h2>
                <?php if (!empty($settings)): ?>
                    <select id="settingsDropdown" name="settings" class="postform">
                        <?php
                        foreach ($settings as $setting) {
                            $optionValue = $setting['brand_name'] . '_' . $setting['setting_name'];
                            $dataId = $setting['id'];
                            $isSelected = $dataId == $savedSettingId ? 'selected="selected"' : '';
                            echo "<option value='{$optionValue}' data-id='{$dataId}' {$isSelected}>{$setting['brand_name']} - {$setting['setting_name']}</option>";
                        }
                        ?>
                    </select>
                    <button id="createSetting" type="button" class="button">Создать</button>
                    <button id="editSetting" type="button" class="button"
                            data-selected-id="<?php echo $savedSettingId ?? ''; ?>"
                            data-selected-name="<?php echo "{$selectedBrandName} - {$selectedSettingName}" ?? ''; ?>">Редактировать</button>
                    <button id="deleteSetting" type="button" class="button"
                            data-selected-id="<?php echo $savedSettingId ?? ''; ?>">Удалить</button>
                    <span id="selectEditSettingStatus" style="color: gray;"></span>
                <?php else: ?>
                    <p>Пока еще нет доступных настроек, создайте первую:</p>
                    <button id="createSetting" type="button" class="button">Создать</button>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }


    /**
     * Initializes the SettingsModal
     */
    private function initializeSettingsModal() {
        $settingsModal = new SettingsModal();
        $settingsModal->renderModal();
    }

    /**
     * Initializes the SettingsEditModal
     */
    private function initializeSettingsEditModal() {
        $settingsEditModal = new SettingsEditModal();
        $settingsEditModal->renderModal();
    }
    public function display_yandex_oauth_status() {
        $token = get_option('yandex_oauth_token');
        $status = $token ? 'Authenticated' : 'Not authenticated';
        echo '<div id="yandex_oauth_token" data-token="'.$token.'">' . $status . '</div>';
    }
}
