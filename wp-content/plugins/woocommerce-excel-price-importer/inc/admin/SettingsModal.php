<?php
class SettingsModal {

    /**
     * Отрисовка модального окна для обработки настроек.
     *
     * @return void
     */
    public function renderModal() {
        $this->renderModalStyle();
        ?>
        <div id="overlay" class="overlay"></div>
        <div id="settingsModal" class="modal">
            <div class="modal-content">
                <h2>Задайте имя настройке</h2>
                <form id="settingsForm">
                    <div class="form-field">
                        <label for="settingBrand">Бренд:</label>
                        <input type="text" id="settingBrand" name="settingBrand" required>
                    </div>
                    <div class="form-field">
                        <label for="settingName">Имя настройки:</label>
                        <input type="text" id="settingName" name="settingName" required>
                    </div>
                    <div class="button-container">
                        <input id="settingsModalCancel" type="button" class="button button-cancel" value="Отмена">
                        <input id="settingsModalCreate" type="submit" class="button button-primary" value="Создать">
                    </div>
                </form>
            </div>
        </div>


        <?php
    }

    /**
     * Стили модального окна для обработки настроек.
     *
     * @return void
     */
    public function renderModalStyle() {
        ?>
        <style>
            .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
                z-index: 999;
            }

            .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: calc(50% + 80px);
                transform: translate(-50%, -50%);
                max-width: 600px;
                background-color: white;
                z-index: 1000;
            }

            .modal-content {
            padding: 20px;
            }

            .button-container {
            display: flex;
            justify-content: flex-end;
                margin-top: 10px;
            }

            .wp-core-ui .button, .wp-core-ui .button-secondary.button-cancel {
            margin-right: 10px;
            }

            @media screen and (min-width: 782px) {
            .modal {
                left: calc(50% + 160px);
                }
            }
            .form-field input[type=text]{
        margin: 7px 0;
            }
        </style>
        <?php
    }


}
