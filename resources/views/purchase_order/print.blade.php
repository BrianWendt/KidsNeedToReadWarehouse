<h1 class="print-top-right text-end">Purchase Order #{{ $purchase_order->id }}</h1>

<div class="row pt-4">
    <div class="col-6">@include('purchase_order.print.left')</div>
    <div class="col-6">@include('purchase_order.print.right')</div>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="140px">{{ __('ISBN') }}</th>
            <th>{{ __('Item') }}</th>
            <th width="140px">{{ __('Condition') }}</th>
            <th class="text-end" width="100px">{{ __('Item Value') }}</th>
            <th class="text-center" width="100px">{{ __('Quantity') }}</th>
            <th class="text-end" width="100px">{{ __('Total') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchase_order->inventory as $item)
            <tr>
                <td>{{ $item->isbn }}</td>
                @if ($item->book)
                    <td>{{ $item->book_label }}</td>
                    <td>{{ $item->book_condition_display }}</td>
                    <td class="text-end">{!! money_format($item->price, 2) !!}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">{!! money_format($item->price * $item->quantity, 2) !!}</td>
                @else
                    <td class="text-danger" colspan="5">
                        Item not found
                    </td>
                @endif
            </tr>
        @endforeach
        <tr class="table-info fw-bold">
            <td colspan="4" class="text-end">{{ __('Sub Total') }}:</td>
            <td class="text-center">{{ $quantity }}</td>
            <td class="text-end">{!! money_format($total, 2) !!}</td>

        </tr>
    </tbody>
</table>

<link rel="stylesheet" href="{{ asset('css/print.css') }}">
