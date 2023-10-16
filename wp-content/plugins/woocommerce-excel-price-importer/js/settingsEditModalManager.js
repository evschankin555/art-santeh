class SettingsEditModalManager {
    constructor() {
        this.init();
    }

    init() {
        this.ajaxurl = wcExcelImporter.ajaxurl;
        this.modal = document.getElementById('wepi_settingsEditModal');
        this.overlay = document.getElementById('wepi_editOverlay');
        this.cancelButton = document.querySelector('#wepi_settingsEditModalCancel');
        this.saveButton = document.querySelector('#wepi_settingsEditModalSave');
        this.editSettingButton = document.querySelector('#editSetting');
        this.settingsDropdown = document.getElementById('settingsDropdown');
        this.firstRowData = [];
        this.settingsId = this.editSettingButton.getAttribute('data-selected-id');
        this.savedSheetName = null;
        this.extendedData = null;
        this.statusSpan = document.getElementById('statusSelectedSettings');
        this.defaultParams = this.getDefaultParams();
        this.labels = this.getLabels();

        if (this.editSettingButton !== null) {
            this.bindEvents();
        }
    }

    bindEvents() {
        if (this.cancelButton !== null) {
            this.cancelButton.addEventListener('click', this.handleCancel.bind(this));
        }

        if (this.saveButton !== null) {
            this.saveButton.addEventListener('click', this.handleSave.bind(this));
        }

        if (this.editSettingButton !== null) {
            this.editSettingButton.addEventListener('click', this.showModal.bind(this));
        }
        if (this.settingsDropdown !== null) {
            this.settingsDropdown.addEventListener('change', this.updateSelectedIdAndName.bind(this));
        }
        document.addEventListener('change', this.handleSheetChange.bind(this));
        const formContainer = document.getElementById('wepi_fieldsContainer');
        if (formContainer !== null) {
            formContainer.addEventListener('change', this.handleSelectChange.bind(this));
        }
    }

    handleCancel() {
        this.hideModal();
    }

    handleSave(event) {
        event.preventDefault();
        this.hideModal();
    }

    hideModal() {
        if (this.modal && this.overlay) {
            this.modal.style.display = 'none';
            this.overlay.style.display = 'none';
        }
    }

    showModal() {
        if (this.modal && this.overlay) {
            const selectedName = this.editSettingButton.getAttribute('data-selected-name');

            const settingsEditModalTitle = document.getElementById("wepi_settingsEditModalTitle");
            if (settingsEditModalTitle) {
                settingsEditModalTitle.innerText = selectedName;
            }

            this.modal.style.display = 'block';
            this.overlay.style.display = 'flex';
            this.statusSpan.innerText = ``;

            console.log('showModal');

            this.fetchExcelData();
            this.fetchSettingData();

        }
    }

    /**
     * Обновляет атрибут data-selected-id для кнопки "Редактировать".
     */
    updateSelectedIdAndName() {
        const selectedOption = this.settingsDropdown.options[this.settingsDropdown.selectedIndex];
        const selectedId = selectedOption.getAttribute('data-id');
        const selectedName = selectedOption.innerText;

        if (this.editSettingButton !== null && selectedId !== null) {
            this.editSettingButton.setAttribute('data-selected-id', selectedId);
            this.editSettingButton.setAttribute('data-selected-name', selectedName);
            this.settingsId = this.editSettingButton.getAttribute('data-selected-id');
        }
    }

    fetchSettingData() {
        let settingId = this.editSettingButton.getAttribute('data-selected-id');
        let formData = new FormData();
        formData.append('setting_id', settingId);
        formData.append('action', 'fetch_setting_data');
        console.log('POST fetch_setting_data');
        this.setModalContentOpacity(0.5);
        this.fetchEditableAllFieldData(settingId);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', this.ajaxurl, false);
        xhr.send(formData);

        if (xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                this.buildFormFields(response);
            } else {
                // Обработка ошибок
                console.error('Error in fetchSettingData');
            }
        } else {
            console.error(`Error: ${xhr.status}`);
        }
    }

    buildFormFields(data) {
        const formContainer = document.getElementById('wepi_fieldsContainer');
        this.extendedData = data.data.extended;

        while (formContainer.firstChild) {
            formContainer.removeChild(formContainer.firstChild);
        }

        this.extendedData.forEach(field => {
            // Используем labels если данных из базы нет
            const matchingField = this.allFieldData.find(f => f.name_param === field.name_param && f.settings_id === this.settingsId) || { field_text: null };

            if (this.defaultParams.includes(field.name_param)) {
                const fieldContainer = document.createElement('div');
                fieldContainer.className = 'field-container';

                let fieldText = matchingField.field_text;

                if (field.name_param.startsWith('field_')) {
                    const editableFieldLabel = new EditableFieldLabel(field.name_param, this.settingsId, this.labels[field.name_param], this.ajaxurl, this.statusSpan, fieldText);
                    fieldContainer.appendChild(editableFieldLabel.getElement());
                } else {
                    const label = document.createElement('label');
                    label.innerText = this.labels[field.name_param];
                    fieldContainer.appendChild(label);
                }

                const select = document.createElement('select');
                select.name = field.name_param;
                select.id = field.id;
                select.className = "SelectParamsSettings";
                select.setAttribute('param', this.labels[field.name_param]);
                select.value = "";

                fieldContainer.appendChild(select);
                formContainer.appendChild(fieldContainer);
            }
        });

        this.setModalContentOpacity(1);
    }

    fetchExcelData() {
        let formData = new FormData();
        formData.append('action', 'fetch_excel_data');

        console.log('POST fetch_excel_data');

        this.setModalContentOpacity(0.5);
        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('response fetch_excel_data');
                    this.buildExcelFields(data.data);
                } else {
                    // Обработка ошибок
                }
            })
            .catch(error => {
                // Обработка сетевых ошибок
            });
    }

    buildExcelFields(data) {
        const formContainer = document.getElementById('wepi_fieldsExelContainer');

        // Очистка контейнера
        while (formContainer.firstChild) {
            formContainer.removeChild(formContainer.firstChild);
        }

        // Создание контейнера для имени файла и списка листов
        const combinedContainer = document.createElement('div');
        combinedContainer.className = 'field-container';
        combinedContainer.style.display = 'flex';
        combinedContainer.style.alignItems = 'center';
        combinedContainer.style.width = '100%';
        combinedContainer.style.marginBottom = '20px';

        // Добавление заголовка для имени файла
        const fileNameLabel = document.createElement('b');
        fileNameLabel.innerText = `Имя файла: ${data.fileName}`;
        fileNameLabel.style.marginRight = '10px'; // Добавление отступа справа
        combinedContainer.appendChild(fileNameLabel);


        // Добавление выпадающего списка для листов
        const sheetSelect = document.createElement('select');
        sheetSelect.id = 'uniqueSheetSelect';
        data.sheets.forEach((sheet, index) => {
            const option = document.createElement('option');
            option.value = sheet;
            option.innerText = sheet;
            if (index === 0) {
                option.selected = true;
            }
            sheetSelect.appendChild(option);
        });

        combinedContainer.appendChild(sheetSelect);

        // Добавление комбинированного контейнера в основной контейнер
        formContainer.appendChild(combinedContainer);

// Добавление кнопки "Сохранить выбор"
        const saveButton = document.createElement('input');
        saveButton.type = 'button';
        saveButton.value = 'Сохранить выбор';
        saveButton.className = 'button button-primary'; // Стилизация под WordPress
        saveButton.addEventListener('click', () => {
            this.saveSelectedSheet(sheetSelect.value);
        });
        combinedContainer.appendChild(saveButton);

// Добавление статусного span
        const statusSpan = document.createElement('span');
        statusSpan.id = 'saveStatus';
        combinedContainer.appendChild(statusSpan);

// Добавление комбинированного контейнера в основной контейнер
        formContainer.appendChild(combinedContainer);

        if (data.sheets.length > 0) {
            this.fetchExcelSheetData(data.sheets[0]);
        }
        this.fetchSavedSettings(this.settingsId);
    }

    fetchExcelSheetData(sheetName) {
        let formData = new FormData();
        formData.append('action', 'fetch_excel_sheet_data');
        formData.append('sheetName', sheetName);
        const statusSpan = document.getElementById('saveStatus');
        statusSpan.innerText = "Загружаем заголовки выбранного листа";

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.firstRowData) {
                    this.firstRowData = data.data.firstRowData;
                    this.updateExcelFields();
                    this.setSelectValuesBasedOnLabel();
                } else {
                    // Обработка ошибок
                }
            });
    }

    updateExcelFields() {
        const formContainer = document.getElementById('wepi_fieldsContainer');
        const selectElements = formContainer.querySelectorAll('select');

        selectElements.forEach(select => {
            // Очищаем существующие опции
            while (select.firstChild) {
                select.removeChild(select.firstChild);
            }

            // Добавляем опцию "Не использовать" в начале
            const defaultOption = document.createElement('option');
            defaultOption.value = 'not_used';  // Изменили значение
            defaultOption.innerText = 'Не использовать';
            select.appendChild(defaultOption);

            // Добавляем новые опции из this.firstRowData
            this.firstRowData.forEach((field) => {
                const option = document.createElement('option');
                option.value = field;
                option.innerText = field;
                select.appendChild(option);
            });

            // Установить значение из this.extendedData
            const correspondingField = this.extendedData.find(field => field.id === select.id || field.name_param === select.name);

            if (correspondingField) {
                // Добавляем атрибут data-header-value
                select.setAttribute('data-header-value', correspondingField.header_value || '');

                if (correspondingField.header_value) {
                    select.value = correspondingField.header_value;
                    if(correspondingField.header_value == ''){
                        select.value = 'not_used';  // Устанавливаем значение по умолчанию
                    }
                } else {
                    select.value = 'not_used';  // Устанавливаем значение по умолчанию
                }
            } else {
                select.value = 'not_used';  // Устанавливаем значение по умолчанию
            }
        });
        console.log('updateExcelFields completed');
    }

    saveSelectedSheet(sheetName) {
        event.preventDefault();

        let formData = new FormData();
        formData.append('action', 'save_selected_sheet');
        formData.append('sheetName', sheetName);
        formData.append('settingsId', this.settingsId);

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const statusSpan = document.getElementById('saveStatus');
                if (data.success) {
                    statusSpan.innerText = 'Успешно сохранено';
                    statusSpan.style.color = 'green';
                } else {
                    statusSpan.innerText = 'Ошибка при сохранении';
                    statusSpan.style.color = 'red';
                }
            })
            .catch(error => {
                const statusSpan = document.getElementById('saveStatus');
                statusSpan.innerText = 'Сетевая ошибка';
                statusSpan.style.color = 'red';
            });
    }
    fetchSavedSettings(settingsId) {
        let formData = new FormData();
        formData.append('action', 'fetch_saved_settings');
        formData.append('settingsId', settingsId);

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const savedSheetName = data.data.sheetName;
                this.updateSheetStatusAndSelection(savedSheetName);
            })
            .catch(error => {
                console.error('Ошибка при запросе сохраненных настроек:', error);
            });
    }

    updateSheetStatusAndSelection(savedSheetName) {
        const statusSpan = document.getElementById('saveStatus');
        const sheetSelect = document.getElementById('uniqueSheetSelect');

        this.savedSheetName = savedSheetName;

        if (savedSheetName === null) {
            statusSpan.innerText = 'Выбор страницы ещё не сохранён!';
            statusSpan.style.color = 'orange';
            return;
        }

        let sheetFound = false;
        for (const option of sheetSelect.options) {
            if (option.value === savedSheetName) {
                sheetSelect.value = savedSheetName;
                sheetFound = true;
                break;
            }
        }

        if (sheetFound) {
            this.fetchExcelSheetData(savedSheetName);
            this.fetchExcelSheetRowCount(savedSheetName);
            statusSpan.innerText = 'Настройки загружены из базы';
            statusSpan.style.color = 'grey';
        } else {
            statusSpan.innerText = `В exel-файле нет листа '${savedSheetName}'!`;
            statusSpan.style.color = 'orange';
        }
    }
    handleSheetChange(event) {
        if (event.target.id === 'uniqueSheetSelect') {
            this.setModalContentOpacity(0.5);
            const selectedSheet = event.target.value;
            const statusSpan = document.getElementById('saveStatus');

            // Обновляем данные с Excel-листа
            this.fetchExcelSheetData(selectedSheet);

            if (this.savedSheetName !== selectedSheet) {
                statusSpan.innerText = "Сохраните настройку выбора листа!";
                statusSpan.style.color = 'orange';
            } else {
                statusSpan.innerText = "Выбран сохраненный лист";
                statusSpan.style.color = 'green';
            }
            this.fetchExcelSheetRowCount(selectedSheet);
        }
    }

    handleSelectChange(event) {
        const target = event.target;

        // Проверяем, что событие произошло на нужном нам элементе
        if (target.classList.contains('SelectParamsSettings')) {
            this.saveSelectedField(target);
        }
    }
    saveSelectedField(targetElement) {
        const selectedValue = targetElement.value;
        const selectName = targetElement.name;
        const settingsExtendedId = targetElement.id;
        const selectLabel = targetElement.getAttribute('name');

        let formData = new FormData();
        formData.append('action', 'save_selected_field');
        formData.append('settingsId', this.settingsId);
        formData.append('settingsExtendedId', settingsExtendedId);
        formData.append('selectedValue', selectedValue);
        formData.append('selectName', selectName);

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                this.statusSpan.innerText = `Значение поля "${selectedValue}" успешно сохранено для параметра "${selectLabel}"`;
                this.statusSpan.style.color = 'green';
            })
            .catch(error => {
                // Обработка ошибок
                this.statusSpan.innerText = `Ошибка при сохранении значения "${selectedValue}" для поля "${selectLabel}"`;
                this.statusSpan.style.color = 'red';
                console.error('Ошибка при сохранении выбранного поля:', error);
            });
    }
    setSelectValuesBasedOnLabel() {
        const formContainer = document.getElementById('wepi_fieldsContainer');
        const selectElements = formContainer.querySelectorAll('select');

        selectElements.forEach(select => {
            const labelValue = select.getAttribute('label');

            // Проходим по всем опциям в select
            for (const option of select.options) {
                if (option.value === labelValue) {
                    select.value = labelValue;
                    break;
                }
            }
        });
        const statusSpan = document.getElementById('saveStatus');
        statusSpan.innerText = "Заголовки загружены из выбранного листа";
        this.setModalContentOpacity(1);
    }

    getDefaultParams() {
        let defaultParams = [
            'category', 'brand', 'sku', 'mainTitle', 'description',
            '_regular_price', '_price', '_purchase_price', 'instruction_url_1',
            '_weight', '_length', '_width', '_height', 'collection',
            'productType', 'washingMachineDepth', 'installationMethod',
            'holeCount', 'material', 'bowlCount', 'shape', 'style',
            'complianceValue', 'warrantyMonths', 'country', 'mainImage'
        ];
        for (let i = 1; i <= 20; i++) defaultParams.push(`additionalImages_${i}`);
        for (let i = 1; i <= 20; i++) defaultParams.push(`field_${i}`);
        return defaultParams;
    }

    getLabels() {
        let labels = {
            'category': 'Категория', 'brand': 'Бренд', 'sku': 'Артикул',
            'mainTitle': 'Наименование', 'description': 'Описание',
            '_regular_price': 'Обычная цена', '_price': 'Цена со скидкой',
            '_purchase_price': 'Закупочная цена', 'instruction_url_1': 'Ссылка на инструкцию',
            '_weight': 'Вес', '_length': 'Длина', '_width': 'Ширина',
            '_height': 'Высота', 'collection': 'Коллекция', 'productType': 'Тип продукта',
            'washingMachineDepth': 'Глубина стиральной машины', 'installationMethod': 'Тип установки',
            'holeCount': 'Количество отверстий', 'material': 'Материал',
            'bowlCount': 'Количество чаш', 'shape': 'Форма', 'style': 'Стиль',
            'complianceValue': 'Цвет', 'warrantyMonths': 'Гарантия', 'country': 'Страна',
            'mainImage': 'Основное картинка'
        };

        for (let i = 1; i <= 20; i++) labels[`additionalImages_${i}`] = `Остальные картинки ${i}`;
        for (let i = 1; i <= 20; i++) labels[`field_${i}`] = `Поле ${i}`;
        return labels;
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
                    const statusSpan = document.getElementById('saveStatus');
                    if(statusSpan){
                        statusSpan.innerHTML += `<br />Кол-во товаров для ${sheetName}: ${data.data.rowCount}`;
                    }
                } else {
                    // Обработка ошибок
                    console.error("Failed to fetch row count or data is invalid.");
                }
            });
    }

    fetchEditableAllFieldData(settingId) {
        let formData = new FormData();
        formData.append('action', 'fetch_all_editable_field_data');
        formData.append('settingsId', settingId);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', this.ajaxurl, false);
        xhr.send(formData);

        if (xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                this.allFieldData = response.data.fields;
            } else {
                // Если данных нет, просто делаем пустой массив
                this.allFieldData = [];
            }
        } else {
            console.error(`Error: ${xhr.status}`);
        }
    }
    setModalContentOpacity(opacity) {
        const modalContent = document.querySelector('.wepi_modal-content');
        modalContent.style.opacity = opacity;
    }

}

class EditableFieldLabel {
    constructor(name_param, settingsId, displayText, ajaxurl, statusSpan, fieldText = null) {
        this.name_param = name_param;
        this.settingsId = settingsId;
        this.ajaxurl = ajaxurl;
        this.statusSpan = statusSpan;
        this.displayText = fieldText ? fieldText : displayText;
        this.labelElement = document.createElement('div');
        this.labelElement.setAttribute('name-param', this.name_param);
        this.labelElement.setAttribute('settings-id', this.settingsId);
        this.labelElement.className = 'editable-field-label';
        this.labelElement.addEventListener('click', this.toggleEditable.bind(this));
        this.labelElement.innerText = this.displayText;
       // this.fetchDataFromDatabase();

    }

    toggleEditable() {
        const inputElement = document.createElement('input');
        inputElement.type = 'text';
        inputElement.value = this.labelElement.innerText;

        const removeInput = () => {
            if (this.labelElement.contains(inputElement)) {
                this.labelElement.removeChild(inputElement);
            }
            this.saveChangedField(inputElement);
        };

        inputElement.addEventListener('blur', () => {
            this.labelElement.innerText = inputElement.value;
            removeInput();
        });

        inputElement.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                this.labelElement.innerText = inputElement.value;
                removeInput();
            }
        });

        this.labelElement.innerHTML = '';
        this.labelElement.appendChild(inputElement);
        inputElement.focus();
    }

    saveChangedField(inputElement) {
        const updatedValue = inputElement.value;
        const name_param = this.labelElement.getAttribute('name-param');
        const settingsId = this.labelElement.getAttribute('settings-id');

        let formData = new FormData();
        formData.append('action', 'wepi_save_changed_field');
        formData.append('name_param', name_param);
        formData.append('settingsId', settingsId);
        formData.append('updatedValue', updatedValue);

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                this.statusSpan.innerText = `Надпись "${updatedValue}" успешно задана для поля.`;
                this.statusSpan.style.color = 'green';
            })
            .catch(error => {
                this.statusSpan.innerText = `Ошибка при сохранении значения "${updatedValue}"`;
                this.statusSpan.style.color = 'red';
                console.error('Ошибка:', error);
            });
    }

    getElement() {
        return this.labelElement;
    }
    fetchDataFromDatabase() {
        let formData = new FormData();
        formData.append('action', 'fetch_field_data');
        formData.append('name_param', this.name_param);
        formData.append('settingsId', this.settingsId);

        fetch(this.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.fieldText) {
                    this.displayText = data.data.fieldText;
                    this.labelElement.innerText = this.displayText;
                    this.statusSpan.innerText = `Надпись поля "${data.data.fieldText}" загружена из базы`;
                    this.statusSpan.style.color = 'gray';
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
    }


}
