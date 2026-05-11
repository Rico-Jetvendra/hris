
<input
    type="{{ $field['type'] }}"
    class="form-control"
    name="{{ $field['name'] }}"
    id="{{ $field['name'] }}"
    @required($field['required'] ?? false)
>
