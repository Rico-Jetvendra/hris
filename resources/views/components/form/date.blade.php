
<input type="text"
    class="form-control datepicker"
    name="{{ $field['name'] }}"
    id="{{ $field['name'] }}"
    @required($field['required'] ?? false)
>
