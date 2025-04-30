<dl class="dl-horizontal">
    @if($purchase_order->organization->ein)
    <dt>{{ __('Organization EIN') }}:</dt>
    <dd>{{ $purchase_order->organization->ein }}</dd>
    @endif

    @if($purchase_order->contact_id > 0 && $purchase_order->contact->ein)
    <dt>{{ __('Contact EIN') }}:</dt>
    <dd>{{ $purchase_order->contact->ein }}</dd>
    @endif

    @if($purchase_order->note != '')
    <dt>{{ __('Note') }}:</dt>
    <dd>{!! nl2p($purchase_order->note) !!}</dd>
    @endif

    <dt>{{ __('Total') }}:</dt>
    <dd>{{ $quantity }}  items<br/>${{ number_format($total, 2) }}</dd>
</dl>