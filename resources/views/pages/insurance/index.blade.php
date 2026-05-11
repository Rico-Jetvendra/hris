@include('components.header', ['title' => 'Asuransi'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Asuransi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asuransi</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <x-crud-table
                    :data="$data"
                    :columns="$columns"
                    primaryKey="insurance_id"
                />
            </div>
        </div>
    </div>
</main>

<x-crud-modal
    title="Asuransi"
    :fields="[
        ['name' => 'insurance_name', 'label' => 'Nama Asuransi', 'type' => 'text', 'required' => true],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'required' => false],
    ]"
/>

@include('components.footer')

<script>
    let columns = @json($columns);

    initCrud({
        routes: {
            store: "{{ route('web.insurance.store') }}",
            update: id => "{{ route('web.insurance.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.insurance.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.insurance.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.insurance.data') }}"
        },
        fields: ['insurance_name', 'remarks'],
        columns: columns
    });
</script>
