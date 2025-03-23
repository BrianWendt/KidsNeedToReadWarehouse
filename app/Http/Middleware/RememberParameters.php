<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RememberParameters
{
    const INPUT_MAP = [
        'inventory.book_condition' => 'book_condition',
        'fulfillment_inventory.book_condition' => 'book_condition',
        'purchase_order_id' => 'purchase_order_id',
        'fulfillment_id' => 'fulfillment_id',
    ];

    const ROUTE_MAP = [
        'fulfillment' => 'fulfillment_id',
        'purchase_order' => 'purchase_order_id',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach(self::INPUT_MAP as $parameter => $mapped) {
            if ($request->has($parameter)) {
                $request->session()->put($mapped, $request->input($parameter));
            }
        }
        foreach(self::ROUTE_MAP as $route => $mapped) {
            if ($request->route($route)) {
                $request->session()->put($mapped, $request->route($route));
            }
        }
        return $next($request);
    }
}
