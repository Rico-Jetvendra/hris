@include('components.header', ['title' => 'Cabang'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Cabang</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cabang</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <x-crud-table
                    :data="$data"
                    :columns="$columns"
                    primaryKey="branch_id"
                />
            </div>
        </div>
    </div>
</main>

<x-crud-modal
    title="Cabang"
    :fields="[
        ['name' => 'branch_name', 'label' => 'Nama Cabang', 'type' => 'text', 'required' => true],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'required' => false],
    ]"
/>

@include('components.footer')

<script>
    let columns     = @json($columns);
    let permissions = @json(session('permission'));

    initCrud({
        routes: {
            store: "{{ route('web.branch.store') }}",
            update: id => "{{ route('web.branch.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.branch.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.branch.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.branch.data') }}"
        },
        fields: {
            'branch_name': 'branch_name',
            'remarks': 'remarks'
        },
        columns: columns,
        permissions: permissions
    });
</script>
