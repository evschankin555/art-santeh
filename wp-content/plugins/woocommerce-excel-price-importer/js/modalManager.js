/**
 * Класс ModalManager, отвечающий за управление модальными окнами.
 */
class ModalManager {
    /**
     * Конструктор инициализирует экземпляр ModalManager.
     */
    constructor() {
        this.init();
    }

    /**
     * Инициализирует атрибуты класса и привязывает обработчики событий.
     */
    init() {
        this.ajaxurl = wcExcelImporter.ajaxurl;
        this.modal = document.getElementById('settingsModal');
        this.overlay = document.getElementById('overlay');
        this.createSettingButton = document.querySelector('#createSetting');
        this.cancelButton = document.querySelector('#settingsModalCancel');
        this.createButton = document.querySelector('#settingsModalCreate');
        this.deleteSettingButton = document.querySelector('#deleteSetting');
        this.settingsDropdown = document.getElementById('settingsDropdown');
        this.settingId = this.settingsDropdown.getAttribute('data-id');


        if (this.createSettingButton !== null) {
            this.bindEvents();
        } else {
            console.error("Элемент с id 'createSetting' не найден");
        }
    }

    /**
     * Привязывает необходимые обработчики событий.
     */
    bindEvents() {
        if (this.deleteSettingButton !== null) {
            this.deleteSettingButton.addEventListener('click', this.handleDelete.bind(this));
        }

        if (this.createSettingButton !== null) {
            this.createSettingButton.addEventListener('click', this.showModal.bind(this));
        }

        if (this.cancelButton !== null) {
            this.cancelButton.addEventListener('click', this.handleCancel.bind(this));
        }

        if (this.createButton !== null) {
            this.createButton.addEventListener('click', this.handleCreate.bind(this));
        }
        if (this.settingsDropdown !== null) {
            this.settingsDropdown.addEventListener('change', this.updateSelectedId.bind(this));
        }
    }

    /**
     * Отображает модальное окно.
     */
    showModal() {
        if (this.modal && this.overlay) {
            this.modal.style.display = 'block';
            this.overlay.style.display = 'block';
        }
    }

    /**
     * Скрывает модальное окно.
     */
    hideModal() {
        if (this.modal && this.overlay) {
            this.modal.style.display = 'none';
            this.overlay.style.display = 'none';
        }
    }

    /**
     * Обрабатывает нажатие на кнопку "Отмена" в модальном окне.
     */
    handleCancel() {
        this.hideModal();
    }

    /**
     * Обрабатывает нажатие на кнопку "Создать" в модальном окне.
     */
    handleCreate(event) {
        event.preventDefault();  // Отменяем стандартное поведение

        // Инициализация переменных
        let settingBrand = document.getElementById('settingBrand').value;
        let settingName = document.getElementById('settingName').value;

        // Создание FormData и добавление в него данных
        let formData = new FormData();
        formData.append('setting_brand', settingBrand);
        formData.append('setting_name', settingName);
        formData.append('action', 'handle_create_setting');  // WordPress action hook

        // AJAX-запрос на wp ajax
        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Получаем ID созданной настройки
                    const settingId = data.data.setting_id;

                    // Добавляем новый элемент в список
                    const settingsDropdown = document.getElementById('settingsDropdown');
                    const newOption = document.createElement('option');
                    newOption.value = `${settingBrand}_${settingName}`;
                    newOption.textContent = `${settingBrand} - ${settingName}`;
                    newOption.setAttribute('data-id', settingId);
                    newOption.selected = true;
                    settingsDropdown.appendChild(newOption);

                    // Устанавливаем атрибут data-selected-id с помощью отдельного метода
                    this.setDeleteButtonAttribute(settingId);

                    // Дальнейшие действия
                } else {
                    // Обработка ошибок
                }
            })
            .catch(error => {
                // Обработка сетевых ошибок
            });

        this.hideModal();
    }

    /**
     * Обрабатывает нажатие на кнопку "Удалить" в модальном окне.
     */
    handleDelete() {
        const confirmed = window.confirm("Вы уверены, что хотите удалить эту запись?");

        if (confirmed) {
            let deleteSettingButton = document.getElementById('deleteSetting');
            let settingId = deleteSettingButton.getAttribute('data-selected-id');

            // Создание FormData и добавление в него данных
            let formData = new FormData();
            formData.append('setting_id', settingId);
            formData.append('action', 'handle_delete_setting');  // WordPress action hook

            // AJAX-запрос на wp ajax
            fetch(this.ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        // Обработка ошибок
                    }
                })
                .catch(error => {
                    // Обработка сетевых ошибок
                });
        }
    }

    /**
     * Обновляет атрибут data-selected-id для кнопки "Удалить".
     */
    updateSelectedId() {
        const selectEditSettingStatus = document.getElementById('selectEditSettingStatus');
        selectEditSettingStatus.innerText = '...';
        const selectedOption = this.settingsDropdown.options[this.settingsDropdown.selectedIndex];
        const selectedId = selectedOption.getAttribute('data-id');

        if (selectedId !== null) {
            this.settingId = selectedId;
            this.setDeleteButtonAttribute(selectedId);
            this.saveSelectedSettingId();

            //this.fetchFileDataBySettingId();---доработать сохранение и отображение загруженных файлов
        }
    }


    setDeleteButtonAttribute(settingId) {
        const deleteSettingButton = document.getElementById('deleteSetting');
        deleteSettingButton.setAttribute('data-selected-id', settingId);
    }

    fetchFileDataBySettingId() {
        let formData = new FormData();
        formData.append('action', 'fetch_file_data_by_setting_id');
        formData.append('settingId', this.settingId);

        const statusSpan = document.getElementById('saveStatus');
        statusSpan.innerText = "Загружаем данные для выбранного элемента";

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.fileInfo) {
                    // Обрабатываем полученные данные
                    this.fileInfo = data.data.fileInfo;
                    this.updateUIBasedOnFileInfo();
                } else {
                    // Обработка ошибок
                }
            })
            .catch(error => {
                // Обработка сетевых ошибок
            });
    }
    updateUIBasedOnFileInfo(){

    }
    saveSelectedSettingId() {
        let formData = new FormData();
        formData.append('action', 'save_selected_setting_id');
        formData.append('settingId', this.settingId);

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                } else {
                    // Обработка ошибок
                }
            })
            .catch(error => {
                // Обработка сетевых ошибок
            });
    }
}
