@component($typeForm, get_defined_vars())
    <div data-controller="input"
         data-input-mask="{{$mask ?? ''}}"
         class="input-group"
    >
        <input {{ $attributes }} oninput="updateISBNLength(this)">
        <span class="input-group-text" id="{{$id}}-length">-</span>
    </div>


@endcomponent
