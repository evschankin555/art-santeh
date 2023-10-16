<?php

class SettingsEditModal {

    /**
     * Отрисовка модального окна для редактирования настроек.
     *
     * @return void
     */
    public function renderModal() {
        $this->renderModalStyle();
        ?>
        <div id="wepi_editOverlay" class="wepi_overlay">
            <div id="wepi_settingsEditModal" class="wepi_modal">
                <div class="wepi_modal-content">
                    <h2 id="wepi_settingsEditModalTitle">Редактировать настройку</h2>
                    <form id="wepi_settingsEditForm">
                        <div id="wepi_fieldsExelContainer">

                        </div>
                        <div id="wepi_fieldsContainer">
                            <!-- Здесь будут добавляться поля -->
                        </div>
                        <div class="wepi_button-container">
                            <span id="statusSelectedSettings"></span>
                            <input id="wepi_settingsEditModalCancel" type="button" class="button button-cancel" value="Закрыть">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Стили модального окна для редактирования настроек.
     *
     * @return void
     */

    public function renderModalStyle() {
        ?>
        <style>
            .wepi_overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: calc(100% - 80px);
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                z-index: 999;
                align-items: center;
                justify-content: center;
                margin-left: 80px;
            }

            .wepi_modal {
                background-color: white;
                display: none;
                width: calc(100% - 160px);
                margin-left: 80px;
                max-height: calc(100% - 160px);
            }

            .wepi_modal-content {
                padding: 20px;
            }

            .wepi_button-container {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
            }

            .wepi_form-field input[type=text] {
                margin: 7px 0;
            }
            #wepi_settingsEditForm select{
                margin: 0px 10px 0 10px;
                max-width: 180px;
            }
            #wepi_fieldsContainer{
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
            }
            #wepi_settingsEditForm .field-container{
                width: 25%;
                display: inline-grid;
                margin: 0 0 10px 0;
            }
            #wepi_settingsEditForm label{
                cursor: default;
                font-weight: bold;
            }
            /* Ваши существующие стили ... */

            .editable-field-label {
                color: #0073aa;
                cursor: pointer;
            }


            #wepi_settingsEditModal{
                overflow-y: auto;
            }
        </style>
        <?php
    }

}
