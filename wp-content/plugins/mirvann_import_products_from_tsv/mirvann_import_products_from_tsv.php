<?php
/**
 * Plugin Name: WooCommerce Импорт продуктов
 * Description: Плагин для импорта и обновления товаров в WooCommerce из TSV файла.
 * Version: 1.0
 * Author: Евгений
 * Author URI: https://t.me/evsch999
 */
ini_set('display_errors', 1);
include_once 'inc/base/admin/TSVImporterAdmin.php';
include_once 'inc/base/admin/ExtendedTSVImporterAdmin.php';
include_once 'inc/base/ProductProperties.php';
include_once 'inc/base/BaseTSVImporter.php';
include_once 'inc/base/product/ProductDataFormatter.php';
include_once 'inc/base/product/ProductDataRetriever.php';
include_once 'inc/base/ImageToWebPConverter.php';
include_once 'inc/base/MissingImageChecker.php';


include_once 'inc/prices/StellaPolarImporter.php';
include_once 'inc/prices/DrejaImporter.php';
include_once 'inc/prices/DrejaImporterSanovit.php';
include_once 'inc/prices/SintesiImporter.php';

include_once 'inc/prices/ComfortyImporter.php';
include_once 'inc/prices/Comforty/ComfortyRacovinyImporter.php';
include_once 'inc/prices/Comforty/ComfortySmestitelImporter.php';
include_once 'inc/prices/Comforty/ComfortyUnitazImporter.php';

include_once 'inc/prices/Aquame/AquameImporter.php';
include_once 'inc/prices/ABBERImporter.php';
include_once 'inc/prices/BemetaImporter.php';
include_once 'inc/prices/BergesImporter.php';
