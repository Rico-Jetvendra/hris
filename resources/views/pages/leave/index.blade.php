@include('components.header', ['title' => 'Cuti'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Cuti</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cuti</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        @if(in_array(permission('add'), session('permission', [])))
                            <button class="btn btn-success btn-generate">
                                <i class="bi bi-plus"></i> Generate
                            </button>
                            <button class="btn btn-primary btn-leave">
                                <i class="bi bi-file-minus"></i> Libur
                            </button>
                        @endif
                    </div>

                    <div class="card-body">
                        <table id="dataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    @foreach ($columns as $col)
                                        <th>{{ $col['label'] }}</th>
                                    @endforeach

                                    @if(
                                        in_array(permission('edit'), session('permission', []))
                                        ||
                                        in_array(permission('delete'), session('permission', []))
                                    )
                                        <th width="120">Aksi</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="crudModal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="crudForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="modalTitle">Edit</span> Jatah Cuti
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="leave_entitlement">Jatah Cuti</label>
                        <input class="form-control" type="text" name="leave_entitlement" id="leave_entitlement" required/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="crudMinus" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('web.leave.minus') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="modalTitle">Cuti</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="days">Lama Hari</label>
                        <input class="form-control" type="text" name="days" id="days" required/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('components.footer')

<script>
    const columns           = @json($columns);
    const permissions       = @json(session('permission'));
    const basePermission    = "{{ permission() }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const routes = {
        update: id => "{{ route('web.leave.update', ':id') }}".replace(':id', id),
        edit: id => "{{ route('web.leave.edit', ':id') }}".replace(':id', id),
        destroy: id => "{{ route('web.leave.destroy', ':id') }}".replace(':id', id),
        data: "{{ route('web.leave.data') }}"
    };

    const modal         = $('#crudModal');
    const form          = $('#crudForm');
    const defaultSearch = $('#defaultSearch').val();
    const tableColumns  = [
        { data: 'DT_RowIndex', orderable: false, searchable: false },

        ...columns.map(col => ({
            data: col.field,
            orderable: col.orderable ?? true,
            searchable: col.searchable ?? true
        })),
    ];

    if(permissions.includes(basePermission+'.edit') || permissions.includes(basePermission+'.delete')){
        tableColumns.push({
            data: 'action',
            orderable: false,
            searchable: false
        });
    }

    $.fn.DataTable.ext.pager.numbers_length = 5;

    const table = $('#dataTable').DataTable({
        responsive:true,
        autoWidth:false,
        processing: true,
        serverSide: true,
        ajax: routes.data,
        pagingType: "simple_numbers",
        columns: tableColumns,
        search: {
            search: defaultSearch
        }
    });

    $('.btn-generate').click(() => {
        const currentYear = new Date().getFullYear();

        Swal.fire({
            title: 'Anda yakin?',
            text: "Anda yakin ingin data cuti untuk tahun " + currentYear + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, buat data cuti!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then(r => {
            if (!r.isConfirmed){
                return Swal.fire({
                    title: 'Dibatalkan',
                    text: 'Data tidak jadi dibuat.',
                    icon: 'error'
                });
            };

            $.post("{{ route('web.leave.generate') }}").done(() => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil dibuat.',
                    icon: 'success'
                }).then(() => table.draw());
            });
        });
    });

    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');

        form.attr('action', routes.update(id));
        $('#formMethod').val('PUT');
        $('#modalTitle').text('Edit');

        $.get(routes.edit(id), res => {
            $('#leave_entitlement').val(res['leave_entitlement']);
        });

        modal.modal('show');
    });

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Anda yakin?',
            text: "Anda yakin ingin menghapus " + name + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then(r => {
            if (!r.isConfirmed){
                return Swal.fire({
                    title: 'Dibatalkan',
                    text: 'Data tidak jadi dihapus.',
                    icon: 'error'
                });
            };

            destroy(id);
        });
    });

    $('.btn-leave').click(() => {
        $('#crudMinus').modal('show');
    });

    function destroy(id) {
        $.post(routes.destroy(id), {
            _method: 'DELETE'
        })
        .done(() => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data berhasil dihapus.',
                icon: 'success'
            }).then(() => table.draw());
        });
    }
</script>
