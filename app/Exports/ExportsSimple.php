<?php

namespace App\Exports;

/**
 * @property array $data
 */
class ExportsSimple extends Exports
{
    public function beforeExport(\OpenSpout\Writer\AbstractWriter $writer): void
    {
        $row = $this->createRowFromArray($this->data['columns']);
        $row->setStyle($this->makeHeaderStyle());
        $writer->addRow($row);
        foreach ($this->data['rows'] as $row_data) {
            $columns = [];
            foreach ($this->data['columns'] as $column => $label) {
                $columns[] = $row_data->getContent($column);
            }
            $row = $this->createRowFromArray($columns);
            $writer->addRow($row);
        }
    }
}
