@php
    $select = collect($selects)->firstWhere('name', $field['name']);
@endphp

<select
    name="{{ $field['name'] }}"
    id="{{ $field['name'] }}"
    class="form-control"
    {{ $field['required'] ? 'required' : '' }}
>
    <option value="">-- Select {{ $field['label'] }} --</option>

    @foreach ($select['data'] ?? [] as $option)
        <option value="{{ $option->{$select['id']} }}">
            {{ $option->{$select['text']} }}
        </option>
    @endforeach
</select>
