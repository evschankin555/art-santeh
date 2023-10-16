class ExcelImporterProccessingManager {
    constructor() {
        this.currentIndex = 0;
        this.totalProducts = 1;
        this.settingId = null;
        this.selectedName = '';
        this.statusElement = document.getElementById("import_status");
        this.logsElement = document.getElementById("logs");
        this.editSettingButton = jQuery('#editSetting');
        this.init();
        console.log('start');
    }


    /**
     * Инициализирует атрибуты класса и привязывает обработчики событий.
     */
    init() {
        this.ajaxurl = wcExcelImporter.ajaxurl;
        this.startImport = document.querySelector('#wepi_startImport');
        this.sheetName = '';
        this.bindEvents();
    }


    /**
     * Привязывает необходимые обработчики событий.
     */
    bindEvents() {
        if (this.startImport !== null) {
            this.startImport.addEventListener('click', this.handleStartImport.bind(this));
        }
    }

    /**
     * Обрабатывает процесс.
     */
    handleStartImport() {
        const settingId = jQuery('#editSetting').attr('data-selected-id');
        const selectedName = jQuery('#editSetting').attr('data-selected-name');
        this.currentIndex = 0;
        this.logsElement.innerHTML = '';
        this.statusElement.innerHTML = '';
        this.statusElement.innerHTML = `Начало импорта для <b>${selectedName}</b> (id: ${settingId})`;
        this.statusElement.style.color = "black";

        jQuery.post(wcExcelImporter.ajaxurl, {
            action: 'get_excel_import_setting',
            settingId: settingId
        }, (result) => {
            if (result.success) {
                if (result.data.sheetExists) {
                    if (result.data.skuExists) {
                        this.sheetName = result.data.settings.fileData.sheet_name;
                        this.logsElement.innerHTML += '<p>Настройки и лист успешно загружены, продолжаем импорт...</p>';
                        this.logsElement.innerHTML += `<p>Читаем страницу <b>${this.sheetName}</b></p>`;
                            console.log(result.data);
                        this.fetchExcelSheetRowCount(this.sheetName);
                    } else {
                        this.logsElement.innerHTML = "<p style='color: red'>Поле Артикула обязательно должно быть задано!</p>";
                    }
                } else {
                    this.logsElement.innerHTML = "<p style='color: red'>В выбранном файле Excel нет листа, который сохранен в настройках</p>";
                }
            } else {
                // Прекращение импорта и вывод сообщения об ошибке
                this.logsElement.innerHTML = "<p style='color: red'>В выбранном файле Excel нет листа, который сохранен в настройках</p>";
            }
        }).fail((jqXHR) => {
            // Обработка ошибок сервера
            this.statusElement.innerText = "Ошибка сервера при загрузке настроек";
            this.statusElement.style.color = "red";
        });
    }


    fetchExcelSheetRowCount(sheetName) {
        let formData = new FormData();
        formData.append('action', 'fetch_excel_sheet_row_count');
        formData.append('sheetName', sheetName);

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && typeof data.data.rowCount !== 'undefined') {
                    this.totalProducts = data.data.rowCount;
                    this.logsElement.innerHTML += `<p>Найдено товаров <b>${this.totalProducts}</b></p>`;
                    this.importNextProduct();
                } else {
                    this.logsElement.innerHTML += `<p>Тованы не найдены!</p>`;
                    console.error("Failed to fetch row count or data is invalid.");
                }
            })
            .catch(error => {
                // Обработка сетевых ошибок
                console.error(`Network error: ${error}`);
            });
    }

    importNextProduct(retryCount = 0) {
        this.statusElement.innerText = `Импорт ${this.currentIndex} из ${this.totalProducts}`;
        this.statusElement.style.color = "black";
        const settingId = jQuery('#editSetting').attr('data-selected-id');

        const maxRetryCount = 3;
        const retryDelay = 3000;

        jQuery.post(wcExcelImporter.ajaxurl, {
            action: 'excel_import',
            index: this.currentIndex,
            settingId: settingId
        }, (result) => {

            if (result.success) {
                this.logsElement.innerHTML = "";

                this.currentIndex++;
                if (this.currentIndex <= this.totalProducts) {
                    this.importNextProduct();
                } else {
                    this.statusElement.innerText = "Импорт завершен!";
                    this.statusElement.style.color = "green";
                }

                if (result.data.steps) {
                    this.logsElement.innerHTML += result.data.steps.join('<br>') + '<hr>';
                }
            } else {
                this.logsElement.innerText = `Ошибка: ${result.data.message}`;
                this.statusElement.style.color = "red";
            }
        }).fail((jqXHR) => {
            if (jqXHR.status === 524 && retryCount < maxRetryCount) {
                setTimeout(() => {
                    this.importNextProduct(retryCount + 1);
                }, retryDelay);
            } else {
                this.statusElement.innerText = "Ошибка сервера";
                this.statusElement.style.color = "red";
            }
        });
    }
}