@include('components.header', ['title' => 'Jabatan'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Jabatan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jabatan</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <x-crud-table
                    :data="$data"
                    :columns="$columns"
                    primaryKey="position_id"
                />
            </div>
        </div>
    </div>
</main>

<x-crud-modal
    title="Jabatan"
    :fields="[
        ['name' => 'position_name', 'id' => 'position_name', 'label' => 'Nama Jabatan', 'type' => 'text', 'required' => true],
        ['name' => 'remarks', 'id' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'required' => false],
    ]"
/>

@include('components.footer')

<script>
    let columns = @json($columns);
    let permissions = @json(session('permission'));

    initCrud({
        routes: {
            store: "{{ route('web.position.store') }}",
            update: id => "{{ route('web.position.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.position.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.position.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.position.data') }}"
        },
        fields: {
            'position_name': 'position_name',
            'remarks': 'remarks'
        },
        columns: columns,
        permissions: permissions
    });
</script>
