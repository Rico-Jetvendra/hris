@include('components.header', ['title' => 'Perusahaan'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Perusahaan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Perusahaan</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <x-crud-table
                    :data="$data"
                    :columns="$columns"
                    primaryKey="company_id"
                />
            </div>
        </div>
    </div>
</main>

<x-crud-modal
    title="Perusahaan"
    :fields="[
        ['name' => 'company_name', 'id' => 'company_name', 'label' => 'Nama Perusahaan', 'type' => 'text', 'required' => true],
        ['name' => 'remarks', 'id' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'required' => false],
    ]"
/>

@include('components.footer')

<script>
    let columns = @json($columns);
    let permissions = @json(session('permission'));

    initCrud({
        routes: {
            store: "{{ route('web.company.store') }}",
            update: id => "{{ route('web.company.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.company.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.company.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.company.data') }}"
        },
        fields: ['company_name', 'remarks'],
        columns: columns,
        permissions: permissions
    });
</script>
