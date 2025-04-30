<div class="row">
    <div class="col-6">@include('fulfillment.print.left')</div>
    <div class="col-6">@include('fulfillment.print.right')</div>
</div>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>{{ __('Item') }}</th>
            <th width="200px">{{ __('Condition') }}</th>
            <th class="text-end" width="100px">{{ __('Item Value') }}</th>
            <th class="text-center" width="100px">{{ __('Quantity') }}</th>
            <th class="text-end" width="100px">{{ __('Total') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fulfillment->inventory as $item)
            <tr>
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
            <td colspan="3" class="text-end">{{ __('Sub Total') }}:</td>
            <td class="text-center">{{ $quantity }}</td>
            <td class="text-end">{!! money_format($total, 2) !!}</td>

        </tr>
    </tbody>
</table>
