<?php
set_time_limit(0); // Отключение времени выполнения
/**
 * Класс для конвертации изображений в формат WebP
 */
class ImageToWebPConverter {
    private $mainDir;
    private $webpDir;
    /**
     * Конструктор класса.
     * @param string $mainDir - Основная директория, откуда берутся изображения для конвертации.
     */
    public function __construct($mainDir) {
        $this->mainDir = $mainDir;
        $this->webpDir = $mainDir . '_webp';
    }
    /**
     * Метод для конвертации изображений.
     * @return array - Возвращает массив с логами выполнения.
     */
    public function convertImages() {
        $steps = [];

        if (!file_exists($this->webpDir)) {
            mkdir($this->webpDir, 0777, true);
            $steps[] = "Создана новая папка: {$this->webpDir}.";
        }

        $dirIterator = new RecursiveDirectoryIterator($this->mainDir);
        $iterator = new RecursiveIteratorIterator($dirIterator);

        foreach ($iterator as $file) {
            $path = $file->getPath();
            $newPath = str_replace($this->mainDir, $this->webpDir, $path);

            if (!file_exists($newPath)) {
                mkdir($newPath, 0777, true);
                $steps[] = "Создана новая папка: {$newPath}.";
            }

            if ($file->isFile()) {
                $filePath = $file->getPathname();
                $fileInfo = pathinfo($filePath);
                $ext = strtolower($fileInfo['extension']);
                $newFilePath = $newPath . '/' . $fileInfo['filename'] . '.webp';

                if (!file_exists($newFilePath)) {
                    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        $result = $this->convertImageToWebP($filePath, $newFilePath);

                        if ($result) {
                            $steps[] = "Изображение {$filePath} было сконвертировано в {$newFilePath}.";
                        } else {
                            $steps[] = "Не удалось сконвертировать изображение {$filePath}.";
                        }
                    } else {
                        copy($filePath, $newPath . '/' . $fileInfo['basename']);
                        $steps[] = "Файл {$filePath} был скопирован.";
                    }
                }
            }
        }

        return $steps;
    }
    /**
     * Вспомогательный метод для конвертации одного изображения в WebP.
     * @param string $imagePath - Путь к исходному изображению.
     * @param string $webpPath - Путь для сохранения конвертированного изображения.
     * @return bool - Возвращает true, если конвертация прошла успешно.
     */
    private function convertImageToWebP($imagePath, $webpPath) {
        $imageInfo = getimagesize($imagePath);
        $type = $imageInfo[2];

        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imagePath);
                break;
            default:
                return false;
        }

        return imagewebp($image, $webpPath);
    }
    /**
     * Метод для перемещения файлов на один уровень выше, если есть дублированные папки.
     */
    public function moveFilesUpIfNested() {
        if (!is_dir($this->mainDir)) {
            return;
        }

        try {
            $dirIterator = new RecursiveDirectoryIterator($this->mainDir, FilesystemIterator::SKIP_DOTS);
        } catch (UnexpectedValueException $e) {
            return;
        }

        try {
            $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);
        } catch (Exception $e) {
            return;
        }

        // Первый проход: перемещаем файлы
        foreach ($iterator as $file) {
            if (!$file->isDir()) continue;

            $dirname = $file->getFilename();
            $parentDir = $file->getPath();
            $grandParentDir = dirname($parentDir);

            if ($dirname === basename($parentDir) && $parentDir !== $this->mainDir) {
                if (is_dir($file->getPathname())) {
                    $innerFiles = new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS);
                    foreach ($innerFiles as $innerFile) {
                        $targetPath = $parentDir . '/' . $innerFile->getFilename();
                        rename($innerFile->getPathname(), $targetPath);
                    }
                }
            }
        }
    }
}

add_action('wp_ajax_convert_images_to_webp', 'convert_images_to_webp');
/**
 * Функция для обработки AJAX-запроса на конвертацию изображений.
 */
function convert_images_to_webp() {
    $converter = new ImageToWebPConverter('/var/www/mirvann/product_images/DREJA/sanovit');
    //$converter->moveFilesUpIfNested();
    $result = $converter->convertImages();

    echo json_encode($result);
    wp_die();
}
