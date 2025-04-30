<dl class="dl-horizontal">
    <dt>Organization:</dt>
    <dd>{{ $fulfillment->organization->name }}</dd>

    <dt>Organization EIN:</dt>
    <dd>
        @if ($fulfillment->organization->ein)
            {{ $fulfillment->organization->ein }}
        @else
            <i>-</i>
        @endif
    </dd>

    @if ($fulfillment->contact_id > 0)
        <dt>Contact:</dt>
        <dd>{{ $fulfillment->contact->display_name }}</dd>
    @endif

    @if ($fulfillment->shipping_contact_id > 0)
        <dt>Shipping Contact:</dt>
        <dd>{{ $fulfillment->shipping_contact->display_name }}</dd>
    @endif

    @if ($fulfillment->shipping_address_id > 0)
        <dt>Shipping Address:</dt>
        <dd>
            <address>{{ $fulfillment->shipping_address->display }}</address>
        </dd>
    @endif

    @if ($fulfillment->tracking)
        <dt>Tracking Number:</dt>
        <dd>{{ $fulfillment->tracking }}</dd>
    @endif
</dl>
