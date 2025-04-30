<dl class="dl-horizontal">
    <dt>Organization:</dt>
    <dd>{{ $purchase_order->contact->organization->name }}</dd>

    <dt>Received Date:</dt>
    <dd>{{ $purchase_order->received_date ?? '<i>-</i>' }}</dd>

    @if($purchase_order->contact_id > 0)
        <dt>Donor Name:</dt>
        <dd>{{ $purchase_order->contact->full_name }}</dd>
    @endif

    @if($purchase_order->address_id > 0)
        <dt>Donor Address:</dt>
        <dd><address>{{ $purchase_order->address->display }}</address></dd>
    @endif

    @if($purchase_order->telephone_id > 0)
        <dt>Donor Telephone:</dt>
        <dd>{{ $purchase_order->telephone->display }}</dd>
    @endif

    @if($purchase_order->email_id > 0)
        <dt>Donor Email:</dt>
        <dd>{{ $purchase_order->email->address }}</dd>
    @endif
</dl>