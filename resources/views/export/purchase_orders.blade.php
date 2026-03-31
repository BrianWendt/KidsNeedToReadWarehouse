@extends('export.layout')

@section('title', 'Purchase Orders Overview Export')

@php
    $total_books = 0;
    $total_other = 0;
    $total_amount = 0;
@endphp

@section('content')
    <div class="letter landscape">
        <table class="table table-striped my-4">
            <thead>
                <tr>
                    <th>PO Number</th>
                    <th>Contact/Organization</th>
                    <th>Date Created</th>
                    <th class="text-center">Books</th>
                    <th class="text-center">Other</th>
                    <th class="text-end">Total Amount</th>
                </tr>
                @foreach ($purchase_orders as $purchase_order)
                    @php
                        $amount = $purchase_order->total_amount;
                        $total_amount += $amount;
                        $total_books += $purchase_order->books;
                        $total_other += $purchase_order->non_books;
                    @endphp
                    <tr>
                        <td>{{ $purchase_order->id }}</td>
                        <td>
                            @if ($purchase_order->contact_id)
                                {{ $purchase_order->contact->display }}
                            @else
                                {{ $purchase_order->organization->display }}
                            @endif
                        </td>
                        <td>{{ $purchase_order->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">{{ $purchase_order->books }}</td>
                        <td class="text-center">{{ $purchase_order->non_books }}</td>
                        <td class="text-end">${{ number_format($amount, 2) }}</td>
                    </tr>
                @endforeach
            <tfoot>
                <tr>
                    <th colspan="3" >Totals:</th>
                    <th class="text-center">{{ $total_books }}</th>
                    <th class="text-center">{{ $total_other }}</th>
                    <th class="text-end">${{ number_format($total_amount, 2) }}</th>
                </tr>
            </tfoot>
            </thead>
        </table>
    </div>
@endsection
