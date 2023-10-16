<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelReader
{
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function readExcel()
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        foreach ($worksheet->toArray() as $row) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function readFirstRow($sheetName = null)
    {
        $spreadsheet = IOFactory::load($this->filePath);

        // Установка активного листа по имени, если указан
        if ($sheetName) {
            $spreadsheet->setActiveSheetIndexByName($sheetName);
        }

        $worksheet = $spreadsheet->getActiveSheet();

        return $worksheet->toArray()[0];
    }

    public function readSheetNames() {
        $spreadsheet = IOFactory::load($this->filePath);
        $listNames = $spreadsheet->getSheetNames();
        return $listNames;
    }

    public function setActiveSheetByName($sheetName) {
        $spreadsheet = IOFactory::load($this->filePath);
        $worksheet = $spreadsheet->setActiveSheetIndexByName($sheetName);
        $this->worksheet = $worksheet;
    }
    public function countRowsInSheet($sheetName)
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $spreadsheet->setActiveSheetIndexByName($sheetName);
        $worksheet = $spreadsheet->getActiveSheet();
        $count = 0;

        foreach ($worksheet->toArray() as $index => $row) {
            if ($index === 0) {
                continue; // Skip header row
            }
            if (!empty($row[0])) {
                $count++;
            }
        }

        return $count;
    }

    public function readExcelBySheetName($sheetName)
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $spreadsheet->setActiveSheetIndexByName($sheetName);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        foreach ($worksheet->toArray() as $row) {
            if (!empty($row[0])) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
