@include('components.header', ['title' => 'Departemen'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Departemen</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Departemen</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <x-crud-table
                    :data="$data"
                    :columns="$columns"
                    primaryKey="department_id"
                />
            </div>
        </div>
    </div>
</main>

<x-crud-modal
    title="Departemen"
    :fields="[
        ['name' => 'department_name', 'label' => 'Nama Departemen', 'type' => 'text', 'required' => true],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'required' => false],
    ]"
/>

@include('components.footer')

<script>
    let columns = @json($columns);

    initCrud({
        routes: {
            store: "{{ route('web.department.store') }}",
            update: id => "{{ route('web.department.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.department.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.department.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.department.data') }}"
        },
        fields: ['department_name', 'remarks'],
        columns: columns
    });
</script>
