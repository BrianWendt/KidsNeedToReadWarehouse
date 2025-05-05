<?php

/**
 * Puts a string into a HtmlString object to prevent escaping
 *
 * @param  string  $html
 */
function raw_html($html): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString($html);
}

/**
 * Format a number as money $0.00
 *
 * @param  float  $amount
 */
function money_format($amount, int $decimals = 2): string
{
    $number = number_format($amount, $decimals);

    return "<div class='d-flex justify-content-between'><span>$</span><span>$number</span></div>";
}

/**
 * Convery line breaks to paragraphs
 *
 * @param  string  $string
 */
function nl2p($string): string
{
    return '<p>' . str_replace("\n", '</p><p>', $string) . '</p>';
}

/**
 * Get the taxable value of a book based on its condition
 *
 * @param  \App\Models\Book  $Book
 * @return float
 */
function conditionPrice($Book, string $condition)
{
    if ($Book->fixed_value > 0) {
        return $Book->fixed_value;
    }
    switch ($condition) {
        case 'new':
            return $Book->retail_price;
        case 'like_new':
            return $Book->retail_price * 0.8;
        case 'used':
            return $Book->retail_price * 0.5;
        case 'rb_petsmart':
            return $Book->retail_price * 1;
        case 'rb_purchased':
            return $Book->retail_price * 1;
        case 'rb_handmade':
            return $Book->retail_price * 1;
        default:
            return $Book->retail_price;
    }
}

/**
 * Returns a list of used book codes and their descriptions
 * Used for quick links on the purchase order view screen
 */
function usedBookCodes(): array
{
    return [
        '2020100' => '$1.00',
        '2020150' => '$1.50',
        '2020200' => '$2.00',
        '2020250' => '$2.50',
        '2020300' => '$3.00',
        '2020350' => '$3.50',
        '2020400' => '$4.00',
        '2020450' => '$4.50',
        '2020500' => '$5.00',
        '2020550' => '$5.50',
        '2020600' => '$6.00',
        '2020650' => '$6.50',
        '2020700' => '$7.00',
        '2020750' => '$7.50',
        '2020800' => '$8.00',
        '2020850' => '$8.50',
        '2020900' => '$9.00',
        '2020950' => '$9.50',
        '20201000' => '$10.00',
    ];
}

/**
 * Create a closure for a custom validation rule
 * Used for custom validation rules
 */
function not_empty(string $key): Closure
{
    return function (\Illuminate\Support\Fluent $fluent) use ($key) {
        $value = $fluent->get($key);

        return ! empty(trim($value));
    };
}

/**
 * Create a closure
 *
 * @return void
 */
function renderMoney(): Closure
{
    exit('renderMoney in helpers.php');

    return function () {
        $value = $this->get('value');
        if ($value == 0) {
            $this->set('value', '-');
        } else {
            $this->set('value', money_format($value));
        }
    };
}
