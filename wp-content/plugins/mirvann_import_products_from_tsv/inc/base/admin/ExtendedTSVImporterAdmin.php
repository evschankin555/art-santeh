<?php
/**
 * Расширенный класс для управления импортом TSV.
 *
 * @class ExtendedTSVImporterAdmin
 * @extends {TSVImporterAdmin}
 */
class ExtendedTSVImporterAdmin extends TSVImporterAdmin {
    /**
     * Конструктор для ExtendedTSVImporterAdmin.
     * Инициализирует ключевые слова и вызывает родительский конструктор.
     *
     * @constructor
     */
    public function __construct() {
        $this->formKeywords = [
            'stella_polar',
            'dreja',
            'dreja_sanovit',
            'sintesi',
            'comforty',
            'comforty_racoviny',
            'comforty_smestitel',
            'comforty_unitaz',
            'aquame',
            'abber',
            'bemeta',
            'berges',
        ];
        parent::__construct();
    }
}

/**
 * Инициализация дочернего класса.
 * Создает экземпляр расширенного класса для управления импортом.
 */
new ExtendedTSVImporterAdmin();
