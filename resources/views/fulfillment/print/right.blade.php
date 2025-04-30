<dl class="dl-horizontal">

    <dt>{{ __('Status') }}:</dt>
    <dd>{{ $fulfillment->status_display }}</dd>

    <dt>{{ __('Program') }}:</dt>
    <dd>{{ $fulfillment->program_display }}</dd>

    @if ($fulfillment->description != '')
        <dt>{{ __('Description') }}:</dt>
        <dd>{!! nl2p($fulfillment->description) !!}</dd>
    @endif
</dl>
