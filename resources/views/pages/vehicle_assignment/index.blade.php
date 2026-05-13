@include('components.header', ['title' => 'Penempatan Kendaraan'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Penempatan Kendaraan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Penempatan Kendaraan</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <x-crud-table
                    :data="$data"
                    :columns="$columns"
                    primaryKey="vehicle_assignment_id"
                />
            </div>
        </div>
    </div>
</main>

<x-crud-modal
    title="Penempatan Kendaraan"
    :fields="[
        ['name' => 'vehicle_number', 'id' => 'vehicle_id', 'label' => 'Kendaraan', 'type' => 'select', 'required' => true],
        ['name' => 'employee_name', 'id' => 'employee_id',  'label' => 'Karyawan', 'type' => 'select', 'required' => true],
        ['name' => 'remarks', 'id' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'required' => false],
    ]"
    :selects="[
        ['name' => 'vehicle_number', 'selects' => $selects['vehicle']],
        ['name' => 'employee_name', 'selects' => $selects['employee']],
    ]"
/>

@include('components.footer')

<script>
    let columns     = @json($columns);
    let permissions = @json(session('permission'));

    initCrud({
        routes: {
            store: "{{ route('web.vehicle-assignment.store') }}",
            update: id => "{{ route('web.vehicle-assignment.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.vehicle-assignment.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.vehicle-assignment.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.vehicle-assignment.data') }}"
        },
        fields: {
            'vehicle_id': 'vehicle_number',
            'employee_id': 'employee_name',
            'remarks': 'remarks'
        },
        columns: columns,
        permissions: permissions
    });
</script>
