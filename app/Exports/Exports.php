<?php

namespace App\Exports;

abstract class Exports
{
    public $filename = 'export';

    public $format = 'xlsx';

    public $data = [];

    abstract public function beforeExport(\OpenSpout\Writer\AbstractWriter $writer): void;

    public function beforeExportXLSX(\OpenSpout\Writer\XLSX\Writer $writer): void
    {
        $sheet = $writer->getCurrentSheet();
        $sheet->setColumnWidthForRange(16, 1, count($this->data['columns']));
    }

    public function filename(): string
    {
        return $this->filename . '.' . $this->format;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function setFormat($format)
    {
        $this->format = strtolower($format);

        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function response()
    {
        return response()->stream([$this, 'openInBrowser']);
    }

    public function openInBrowser()
    {
        $writer = $this->makeWriter($this->format);

        $writer->openToBrowser($this->filename());
        // if $writer is XLSX
        if ($writer instanceof \OpenSpout\Writer\XLSX\Writer) {
            $this->beforeExportXLSX($writer);
        }
        $this->beforeExport($writer);
        $writer->close();
        exit; // this is required to prevent the Laravel application from continuing to run
    }

    public function makeWriter(string $format): \OpenSpout\Writer\AbstractWriter
    {
        switch ($format) {
            case 'xlsx':
                return new \OpenSpout\Writer\XLSX\Writer;
            case 'csv':
                return new \OpenSpout\Writer\CSV\Writer;
            default:
                throw new \Exception('Unsupported export format');
        }
    }

    public function createRowFromArray(array $data): \OpenSpout\Common\Entity\Row
    {
        $cells = [];
        foreach ($data as $value) {
            $cells[] = \OpenSpout\Common\Entity\Cell::fromValue($value);
        }

        return new \OpenSpout\Common\Entity\Row($cells);
    }

    public function makeStyle()
    {
        return new \OpenSpout\Common\Entity\Style\Style;
    }

    public function makeHeaderStyle(): \OpenSpout\Common\Entity\Style\Style
    {
        return $this->makeStyle()
            ->setFontBold()
            ->setFontSize(12)
            ->setShouldWrapText();
    }
}
