<?php

namespace App\Exports\Entity;

use OpenSpout\Common\Entity\Style\CellAlignment;

/**
 * @property \OpenSpout\Common\Entity\Style\Style $Style
 */
class Cell
{
    public $text = '';
    public $font_size = 12;
    public $bold = false;
    public $italic = false;
    public $alignment = 'left';
    public $wrap_text = false;
    public $Style;

    public static function make($text)
    {
        return new static($text);
    }

    public function __construct($text)
    {
        $this->text = $text;
        $this->Style = new \OpenSpout\Common\Entity\Style\Style();
    }

    public function fontSize(int $size)
    {
        $this->Style->setFontSize($size);
        return $this;
    }

    public function alignCenter()
    {
        $this->Style->setCellAlignment(CellAlignment::CENTER);
        return $this;
    }

    public function alignRight()
    {
        $this->Style->setCellAlignment(CellAlignment::RIGHT);
        return $this;
    }

    public function wrapText()
    {
        $this->Style->setShouldWrapText(true);
        return $this;
    }

    public function bold()
    {
        $this->Style->setFontBold(true);
        return $this;
    }

    public function italic()
    {
        $this->Style->setFontItalic(true);
        return $this;
    }

    public function formatMoney()
    {
        $this->Style->setFormat('$#,##0.00');
        return $this;
    }

    public function build(): \OpenSpout\Common\Entity\Cell
    {
        return \OpenSpout\Common\Entity\Cell::fromValue($this->text, $this->Style);
    }
}
