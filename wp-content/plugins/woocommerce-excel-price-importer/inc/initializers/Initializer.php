<?php

/**
 * Class Initializer
 *
 * Initializes the plugin
 */
class Initializer
{
    /**
     * @var DatabaseModel Экземпляр класса для работы с БД
     */
    private $databaseModel;
    private $settingHandler;
    private $excelImportProcessor;

    public function __construct()
    {
        $this->loadIncludes();

        if (is_admin()) {
            $this->initializeDatabase();
            $this->initializeSettingHandler();
            $this->initializeExcelImportProcessor();
            $this->initializeFileUploader();
            $this->enqueueAdminScripts();
            $this->initializeAdminInterface();
        }

       // $this->initializeYandexOAuthHandler();
    }

    /**
     * Includes necessary files
     */
    private function loadIncludes()
    {
        require_once MY_PLUGIN_PATH . 'vendor/autoload.php';
        include_once plugin_dir_path(__FILE__) . '../admin/AdminInterface.php';
        include_once plugin_dir_path(__FILE__) . '../database/DatabaseModel.php';
        include_once plugin_dir_path(__FILE__) . '../importer/BaseExcelImporter.php';
        include_once plugin_dir_path(__FILE__) . '../importer/ExcelReader.php';
        include_once plugin_dir_path(__FILE__) . '../importer/ExcelImporter.php';
        include_once plugin_dir_path(__FILE__) . '../importer/FileUploader.php';
        include_once plugin_dir_path(__FILE__) . '../importer/UniversalExcelImporter.php';
        include_once plugin_dir_path(__FILE__) . '../importer/ExcelImportProcessor.php';
        include_once plugin_dir_path(__FILE__) . '../helpers/HelperFunctions.php';
        include_once plugin_dir_path(__FILE__) . '../admin/SettingsModal.php';
        include_once plugin_dir_path(__FILE__) . '../admin/SettingsEditModal.php';
        include_once plugin_dir_path(__FILE__) . '../SettingHandler.php';
        include_once plugin_dir_path(__FILE__) . '../YandexOAuthHandler.php';
    }

    /**
     * Initializes the database
     */
    private function initializeDatabase()
    {
        $this->databaseModel = new DatabaseModel();
    }

    /**
     * Initializes the SettingHandler
     */
    private function initializeSettingHandler()
    {
        $this->settingHandler = new SettingHandler($this->databaseModel);
    }

    /**
     * Initializes the admin interface
     */
    private function initializeAdminInterface()
    {
        $adminInterface = new AdminInterface();
        add_action('admin_menu', function () use ($adminInterface) {
            add_menu_page(
                'WooCommerce Excel Price Importer',
                'Excel Importer',
                'manage_options',
                'wc_excel_importer',
                [$adminInterface, 'render']
            );
        });
    }

    /**
     * Enqueues admin scripts
     */
    private function enqueueAdminScripts()
    {
        add_action('admin_enqueue_scripts', function () {
            $script_url = MY_PLUGIN_URL . 'js/admin.js';
            $script_path = MY_PLUGIN_PATH . 'js/admin.js';
            $modal_url = MY_PLUGIN_URL . 'js/modalManager.js';
            $modal_path = MY_PLUGIN_PATH . 'js/modalManager.js';
            $settingsEditModal_url = MY_PLUGIN_URL . 'js/settingsEditModalManager.js';
            $settingsEditModal_path = MY_PLUGIN_PATH . 'js/settingsEditModalManager.js';
            $excelImportManager_url = MY_PLUGIN_URL . 'js/excelImportManager.js';
            $excelImportManager_path = MY_PLUGIN_PATH . 'js/excelImportManager.js';


            $version = file_exists($script_path) ? filemtime($script_path) : '1.0';
            $modal_version = file_exists($modal_path) ? filemtime($modal_path) : '1.0';
            $settingsEditModal_version = file_exists($settingsEditModal_path) ? filemtime($settingsEditModal_path) : '1.0';
            $excelImportManager_version = file_exists($excelImportManager_path) ? filemtime($excelImportManager_path) : '1.0';


            wp_enqueue_script('wc-excel-importer-admin', $script_url, ['jquery'], $version, true);
            wp_enqueue_script('wc-excel-importer-modal', $modal_url, ['jquery'], $modal_version, true);
            wp_enqueue_script('wc-excel-importer-settingsEditModal', $settingsEditModal_url, ['jquery'], $settingsEditModal_version, true);
            wp_enqueue_script('wc-excel-importer-excelImportManager', $excelImportManager_url, ['jquery'], $excelImportManager_version, true);

            wp_localize_script('wc-excel-importer-admin', 'wcExcelImporter', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            ));
        });
    }


    /**
     * Initializes the FileUploader
     */
    private function initializeFileUploader()
    {
        new FileUploader();
    }

    private function initializeExcelImportProcessor()
    {
        $this->excelImportProcessor = new ExcelImportProcessor($this->databaseModel);
    }

    /**
     * Initializes the YandexOAuthHandler
     */
    private function initializeYandexOAuthHandler()
    {
        new YandexOAuthHandler();
    }
}
