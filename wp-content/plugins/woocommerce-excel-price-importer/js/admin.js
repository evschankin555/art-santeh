/**
 * Класс ExcelImporterManager, отвечающий за загрузку Excel файлов.
 */
class ExcelImporterManager {
    /**
     * Конструктор инициализирует экземпляр ExcelImporterManager.
     */
    constructor() {
        this.init();
    }

    /**
     * Инициализирует атрибуты класса и привязывает обработчики событий.
     */
    init() {
        this.ajaxurl = wcExcelImporter.ajaxurl;
        this.fileInput = document.querySelector('#excelFile');
        this.uploadedFileName = document.querySelector('#uploadedFileName');
        this.uploadeExelStatus = document.querySelector('#uploadeExelStatus');
        this.resetButton = document.querySelector('#resetUpload');
        this.settingsDropdown = document.querySelector('.settingsDropdownContainer');
        this.importContainer = document.querySelector('.importContainer');
        this.toggleVisibilityBasedOnAttribute();
        this.bindEvents();
    }


    /**
     * Привязывает необходимые обработчики событий.
     */
    bindEvents() {
        this.fileInput.addEventListener('change', this.handleFileUpload.bind(this));
        this.resetButton.addEventListener('click', this.resetFileUpload.bind(this));
    }

    /**
     * Обрабатывает ошибку загрузки файла.
     * @param {String} error Сообщение об ошибке.
     */
    handleFileError(error) {
        this.updateUI(`Не удалось загрузить файл - ${error}`, 'red', true);
    }

    /**
     * Отображает сообщение, скрывает или показывает элементы UI.
     * @param {String} message Сообщение для отображения.
     * @param {String} color Цвет сообщения.
     * @param {Boolean} hideInput Флаг для скрытия поля ввода файла.
     */
    updateUI(message, color, hideInput) {
        this.uploadedFileName.innerText = message;
        this.uploadedFileName.style.color = color;

        if (hideInput) {
            this.fileInput.classList.add('hidden');
            this.uploadedFileName.classList.remove('hidden');
            this.resetButton.classList.remove('hidden');
        } else {
            this.fileInput.classList.remove('hidden');
            this.uploadedFileName.classList.add('hidden');
            this.resetButton.classList.add('hidden');
        }
    }

    /**
     * Обрабатывает процесс загрузки файла.
     */
    handleFileUpload() {
        const file = this.fileInput.files[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('action', 'upload_excel_file');
        this.setText('Загружаем файл...');

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.settingsDropdown.style.display = 'block';
                    this.importContainer.style.display = 'block';
                    const fileSize = data.data.size;
                    this.updateUI(`${file.name} (${fileSize})`, '#2271b1', true);
                    this.fileInput.setAttribute('data-exel', '1');  // Установка атрибута в 1
                    this.fetchExcelData();
                } else {
                    this.settingsDropdown.style.display = 'none';
                    this.importContainer.style.display = 'none';
                    this.fileInput.setAttribute('data-exel', '0');  // Установка атрибута в 0
                    this.handleFileError(data.data);
                }
            })
            .catch(error => {
                this.handleFileError(error);
            });
    }

    /**
     * Сбрасывает UI загрузки файла.
     */
    resetFileUpload() {
        this.fileInput.value = '';
        this.settingsDropdown.style.display = 'none';
        this.importContainer.style.display = 'none';
        this.fileInput.setAttribute('data-exel', '0');  // Установка атрибута в 0

        // AJAX-запрос для сброса опции на стороне сервера
        const formData = new FormData();
        formData.append('action', 'reset_excel_upload');
        this.setText('Удаляем файл и связанные с ним данные...');
        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.setText('');
                    this.updateUI('', '#2271b1', false);
                }
            });
    }
    /**
     * Управляет видимостью элементов на основе атрибута data-exel.
     */
    toggleVisibilityBasedOnAttribute() {
        const dataExel = this.fileInput.getAttribute('data-exel');
        if (dataExel === '1') {
            this.settingsDropdown.classList.remove('hidden');
            this.importContainer.classList.remove('hidden');
        } else {
            this.settingsDropdown.classList.add('hidden');
            this.importContainer.classList.add('hidden');
        }
    }
    setSettingsEditModalManager(settingsEditModalManager) {
        this.settingsEditModalManager = settingsEditModalManager;
    }
    fetchExcelData() {
        let formData = new FormData();
        formData.append('action', 'fetch_excel_data');
        this.setText('Читаем листы файла...');

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.data.sheets.length > 0) {
                        // Предполагая, что методы fetchExcelSheetRowCount и fetchExcelSheetData определены в SettingsEditModalManager
                        setTimeout( () => {
                            this.setText('Обновляем страницу...');
                        }, 2220);
                        setTimeout(function (){
                            document.location.reload();
                        }, 3330);
                        this.setText('Читаем кол-во продуктов...');
                        this.settingsEditModalManager.fetchExcelSheetRowCount(data.data.sheets[0]);
                        this.setText('Читаем заголовки...');
                        this.settingsEditModalManager.fetchExcelSheetData(data.data.sheets[0]);
                    }
                } else {
                    // Обработка ошибок
                }
            })
            .catch(error => {
                // Обработка сетевых ошибок
            });
    }
    setText(message){
        this.uploadeExelStatus.innerText = message;
        this.uploadeExelStatus.style.color = 'gray';
    }
}


class YandexAuthManager {
    constructor() {
        this.clientId = '4a0edd999ffe4e66a2c974bbf80d3863';
        this.responseType = 'token';
        this.redirectUri = 'https://mirvann.developing-site.ru/wp-content/themes/cenos-child/yandex-oauth-handler.php';
        this.ajaxUrl = 'https://mirvann.developing-site.ru/wp-admin/admin-ajax.php';
        this.init();
    }

    init() {
        this.initYaAuthSuggest();
        /*this.displayStatus();*/
    }

    initYaAuthSuggest() {
        const statusElementFromDOM = document.getElementById('yandex_oauth_token'); // Получено из DOM
        const tokenStatus = statusElementFromDOM ? statusElementFromDOM.textContent : 'Not authenticated';
        console.log(statusElementFromDOM);
        if (tokenStatus !== 'Authenticated') {
            YaAuthSuggest.init({
                client_id: this.clientId,
                response_type: this.responseType,
                redirect_uri: this.redirectUri
            })
                .then(({ handler }) => handler())
                .then(data => this.sendTokenToServer(data.access_token))
                .catch(error => console.log('Error processing', error));
        }
    }

    displayStatus() {
        const statusElementFromDOM = document.getElementById('yandex_oauth_token'); // Получено из DOM
        const tokenStatus = statusElementFromDOM ? statusElementFromDOM.textContent : 'Not authenticated';

        const statusElement = document.createElement('div');
        statusElement.textContent = `Yandex OAuth Status: ${tokenStatus}`;
        document.body.appendChild(statusElement);

        // Скрываем кнопку если токен сохранен
        if (tokenStatus === 'Authenticated') {
            const authButton = document.getElementById('your_auth_button_id');
            if (authButton) {
                authButton.style.display = 'none';
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const excelImporterManager = new ExcelImporterManager();
    const modalManager = new ModalManager();
    const settingsEditModalManager = new SettingsEditModalManager();
    const excelImporterProccessingManager = new ExcelImporterProccessingManager();
    excelImporterManager.setSettingsEditModalManager(settingsEditModalManager);
    // Удален window.yandexTokenStatus, теперь он получается напрямую из DOM
    //const yandexAuthManager = new YandexAuthManager();
});