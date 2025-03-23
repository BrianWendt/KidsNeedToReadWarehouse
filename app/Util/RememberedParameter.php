<?php

namespace App\Util;

class RememberedParameter
{
    /**
     * Get the value of a parameter from the request or session stored by middleware
     *
     * @see \App\Http\Middleware\RememberParameters
     *
     * @return mixed
     */
    public static function get(string $parameter, $default = null)
    {
        if (request()->has($parameter)) {
            return request()->input($parameter);
        }
        if (session()->has($parameter)) {
            return session()->get($parameter);
        }

        return $default;
    }

    public static function getBookCondition($default = null): ?string
    {
        return self::get('book_condition', $default);
    }

    public static function getPurchaseOrderId($default = null): ?int
    {
        return self::get('purchase_order_id', $default);
    }

    public static function getFulfillmentId($default = null): ?int
    {
        return self::get('fulfillment_id', $default);
    }
}
