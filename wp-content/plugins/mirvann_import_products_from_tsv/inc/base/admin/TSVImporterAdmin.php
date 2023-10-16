<?php

class TSVImporterAdmin {
    protected  $formKeywords = [];

    public function __construct() {
        add_action('admin_menu', [$this, 'addMenuPage']);
    }

    public function addMenuPage() {
        add_menu_page('Импорт продуктов', 'Импорт продуктов', 'manage_options', 'tsv_importer', [$this, 'renderPage'], 'dashicons-upload', 6);
    }

    public function renderPage() {
        ?>
        <h1>Импорт продуктов</h1>
        <div id="import_status"></div>
        <?php
        $this->renderCheckImagesButton();
        $this->renderConvertImagesButton();

        foreach ($this->formKeywords as $keyword) {
            $title = "Импорт " . str_replace("_", " ", ucfirst($keyword));
            $formId = $keyword . '_import_form';
            $currentIndex = 'current_index_' . $keyword;
            $totalProducts = 'total_products_' . $keyword;
            $maxIterations = 'max_iterations_' . $keyword;
            $buttonText = 'Запустить импорт ' . str_replace("_", " ", ucfirst($keyword));

            $this->renderForm($title, $formId, $currentIndex, $totalProducts, $maxIterations, $buttonText);
        }
        ?>
        <div id="logs" style="margin: 20px 40px 0 0; border: 1px solid #fff; padding: 10px;"></div> <!-- Логи -->
        <?php
        $this->renderJavascript();
    }

    private function renderForm($title, $formId, $currentIndexId, $totalProductsId, $maxIterationsId, $buttonLabel) {
        ?>
        <form id="<?php echo $formId; ?>" method="post">
            <h2><?php echo $title; ?></h2>
            <label for="<?php echo $currentIndexId; ?>">Текущий индекс:</label>
            <input type="number" id="<?php echo $currentIndexId; ?>" name="<?php echo $currentIndexId; ?>">
            <label for="<?php echo $totalProductsId; ?>">Всего продуктов:</label>
            <input type="number" id="<?php echo $totalProductsId; ?>" name="<?php echo $totalProductsId; ?>">
            <label for="<?php echo $maxIterationsId; ?>">Максимальное количество итераций:</label>
            <input type="number" id="<?php echo $maxIterationsId; ?>" name="<?php echo $maxIterationsId; ?>">
            <input type="submit" name="start_import" class="button button-primary" value="<?php echo $buttonLabel; ?>">
        </form>
        <?php
    }

    private function renderJavascript() {
        ?>
        <script type="text/javascript">
            class Importer {
                static importNextProduct(actionName, currentIndex, totalProducts, maxIterations, currentIteration, retryCount = 0) {
                    document.getElementById("import_status").innerText = `Импорт ${currentIndex} из ${totalProducts} (Итерация ${currentIteration + 1} из ${maxIterations})`;
                    document.getElementById("import_status").style.color = "black";

                    const maxRetryCount = 3; // Максимальное число попыток
                    const retryDelay = 3000; // Задержка перед повторением (в миллисекундах)

                    jQuery.post(ajaxurl, {
                        action: actionName,
                        index: currentIndex,
                    }, function(response) {
                        let result = JSON.parse(response);
                        if (result.success) {
                            document.getElementById("logs").innerHTML = "";

                            currentIndex++;
                            if (currentIndex <= totalProducts) {
                                Importer.importNextProduct(actionName, currentIndex, totalProducts, maxIterations, currentIteration);
                            } else if (++currentIteration < maxIterations) {
                                currentIndex = 0;
                                Importer.importNextProduct(actionName, currentIndex, totalProducts, maxIterations, currentIteration);
                            } else {
                                document.getElementById("import_status").innerText = "Импорт завершен!";
                                document.getElementById("import_status").style.color = "green";
                            }

                            if (result.steps) {
                                let logsDiv = document.getElementById("logs");
                                logsDiv.innerHTML += result.steps.join('<br>') + '<hr>';
                            }
                        } else {
                            document.getElementById("import_status").innerText = `Ошибка: ${result.message}`;
                            document.getElementById("import_status").style.color = "red";
                        }
                    }).fail(function(jqXHR) {
                        if (jqXHR.status === 524 && retryCount < maxRetryCount) {
                            setTimeout(() => {
                                Importer.importNextProduct(actionName, currentIndex, totalProducts, maxIterations, currentIteration, retryCount + 1);
                            }, retryDelay);
                        } else {
                            document.getElementById("import_status").innerText = "Ошибка сервера";
                            document.getElementById("import_status").style.color = "red";
                        }
                    });
                }
            }


            jQuery(document).ready(function($) {
                const formKeywords = <?php echo json_encode($this->formKeywords); ?>;

                const forms = formKeywords.map(keyword => {
                    return {
                        formId: `${keyword}_import_form`,
                        actionName: `import_${keyword}`,
                        indexInput: `current_index_${keyword}`,
                        totalInput: `total_products_${keyword}`,
                        maxInput: `max_iterations_${keyword}`
                    };
                });

                forms.forEach(({ formId, actionName, indexInput, totalInput, maxInput }) => {
                    $(`#${formId}`).submit(function(e) {
                        e.preventDefault();
                        document.getElementById("logs").innerHTML = "";

                        let currentIndex = $(`#${indexInput}`).val() || 0;
                        let totalProducts = $(`#${totalInput}`).val() || 0;
                        let maxIterations = $(`#${maxInput}`).val() || 1;
                        let currentIteration = 0;

                        Importer.importNextProduct(actionName, currentIndex, totalProducts, maxIterations, currentIteration);
                    });
                });


                document.getElementById("check_images_btn").addEventListener("click", function() {
                    document.getElementById("logs").innerHTML = "";
                    jQuery.post(ajaxurl, {
                        action: 'check_missing_images'
                    }, function(response) {
                        let result = JSON.parse(response);
                        if (result) {
                            let logsDiv = document.getElementById("logs");
                            logsDiv.innerHTML += result.join('<br>') + '<hr>';
                        }
                    });
                });

                document.getElementById("convert_images_btn").addEventListener("click", function() {
                    document.getElementById("logs").innerHTML = "";
                    jQuery.post(ajaxurl, {
                        action: 'convert_images_to_webp'
                    }, function(response) {
                        let result = JSON.parse(response);
                        if (result) {
                            let logsDiv = document.getElementById("logs");
                            logsDiv.innerHTML += result.join('<br>') + '<hr>';
                        }
                    });
                });
            });

        </script>
        <?php
    }

    private function renderCheckImagesButton() {
        ?>
        <button id="check_images_btn" class="button button-secondary">Проверить основные картинки</button>
        <?php
    }


    private function renderConvertImagesButton() {
        ?>
        <button id="convert_images_btn" class="button button-secondary">Конвертировать изображения в WebP</button>
        <?php
    }

}

