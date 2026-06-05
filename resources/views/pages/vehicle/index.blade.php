@include('components.header', ['title' => 'Kendaraan'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Kendaraan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kendaraan</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        @if(in_array(permission('add'), session('permission', [])))
                            <button class="btn btn-primary btn-create"><i class="bi bi-plus"></i> Tambah</button>
                        @endif
                        <!-- <button class="btn btn-danger btn-import"><i class="bi bi-upload"></i> Import</button> -->
                    </div>

                    <div class="card-body">
                        <input type="hidden" id="defaultSearch" value="{{ request('search') }}">
                        <table id="dataTable" class="table table-responsive table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="30">No</th>
                                    @foreach ($columns as $col)
                                        <th>{{ $col['label'] }}</th>
                                    @endforeach
                                    @if(
                                        in_array(permission('edit'), session('permission', []))
                                        ||
                                        in_array(permission('delete'), session('permission', []))
                                    )
                                        <th width="80">Aksi</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" id="crudForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="modalTitle">Tambah</span> Kendaraan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kendaraan">Kendaraan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dokumen">Dokumen</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="kendaraan" class="container tab-pane active"><br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_company">Perusahaan</label>
                                        <select class="form-control searchable-select" name="vehicle_company" id="vehicle_company" required>
                                            <option value="">----- Pilih Perusahaan -----</option>
                                            @foreach($combo['company'] as $value)
                                                <option value="{{ $value['company_id'] }}">{{ $value['company_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_number">No. Polisi</label>
                                        <input class="form-control text-uppercase" type="text" name="vehicle_number" id="vehicle_number" placeholder="B 1234 BN" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_brand">Merk</label>
                                        <select class="form-control searchable-select" name="vehicle_brand" id="vehicle_brand" required>
                                            <option value="">----- Pilih Merk -----</option>
                                            @foreach($combo['brands'] as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_color">Warna</label>
                                        <select class="form-control searchable-select" name="vehicle_color" id="vehicle_color" required>
                                            <option value="">----- Pilih Warna -----</option>
                                            @foreach($combo['colors'] as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_frame">No. Rangka</label>
                                        <input class="form-control text-uppercase frame-mask" type="text" name="vehicle_frame" id="vehicle_frame" placeholder="ABCDE1234567890AB" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_machine">No. Mesin</label>
                                        <input class="form-control text-uppercase machine-mask" type="text" name="vehicle_machine" id="vehicle_machine" placeholder="ABCDE1234567890AB" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_bpkb">BPKB</label><br/>
                                        <input type="hidden" name="vehicle_bpkb" value="0">
                                        <input type="checkbox" name="vehicle_bpkb" id="vehicle_bpkb" value="1"/>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_tax_due">Tgl. Berlaku Pajak</label>
                                        <input class="form-control datepicker" type="text" name="vehicle_tax_due" id="vehicle_tax_due" placeholder="yyyy/mm/dd" autocomplete="off"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_reg_due">Tgl. Berlaku STNK</label>
                                        <input class="form-control datepicker" type="text" name="vehicle_reg_due" id="vehicle_reg_due" placeholder="yyyy/mm/dd" autocomplete="off"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_insurance">Asuransi</label>
                                        <select class="form-control searchable-select" name="vehicle_insurance" id="vehicle_insurance">
                                            <option value="">----- Pilih Asuransi -----</option>
                                            @foreach($combo['insurance'] as $value)
                                                <option value="{{ $value['insurance_id'] }}">{{ $value['insurance_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_insurance_number">No. Asuransi</label>
                                        <input class="form-control" type="text" name="vehicle_insurance_number" id="vehicle_insurance_number" placeholder="ABCDE1234567890ABC" />
                                    </div>
                                    <div class="row">
                                        <label class="form-label" for="vehicle_insurance_start">Periode Asuransi</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <input class="form-control datepicker" type="text" name="vehicle_insurance_start" id="vehicle_insurance_start" placeholder="Dari" autocomplete="off"/>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <input class="form-control datepicker" type="text" name="vehicle_insurance_end" id="vehicle_insurance_end" placeholder="Sampai" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="vehicle_insurance_payment">Tgl. Pembayaran Asuransi</label>
                                        <input class="form-control datepicker" type="text" name="vehicle_insurance_payment" id="vehicle_insurance_payment" placeholder="yyyy/mm/dd" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="remarks">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="3" style="resize:none;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="dokumen" class="container tab-pane"><br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="document_name">Dokumen</label>
                                        <input class="form-control" type="file" name="document_name[]" id="document_name" multiple accept="image/*" />
                                    </div>
                                    <div class="mb-3 border border-secondary p-3" id="document_div">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
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

<div class="modal fade" id="uploadModal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('web.vehicle.upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="modalTitle">Upload</span> Kendaraan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="file">File Kendaraan</label>
                                <input class="form-control" type="file" name="file" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn btn-danger me-2" id="deleteImageBtn">
                    <i class="fas fa-trash"></i> Hapus
                </button>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="previewImage" src="" style="max-width:100%; max-height:90vh;" data-index="">
            </div>

        </div>
    </div>
</div>

@include('components.footer')

<script>
    const plate             = document.getElementById('vehicle_number');
    const columns           = @json($columns);
    const permissions       = @json(session('permission'));
    const basePermission    = "{{ permission() }}";
    const routes            = {
        store: "{{ route('web.vehicle.store') }}",
        update: id => "{{ route('web.vehicle.update', ':id') }}".replace(':id', id),
        edit: id => "{{ route('web.vehicle.edit', ':id') }}".replace(':id', id),
        destroy: id => "{{ route('web.vehicle.destroy', ':id') }}".replace(':id', id),
        data: "{{ route('web.vehicle.data') }}"
    };
    const fields            = {
        'vehicle_number'             : 'vehicle_number',
        'vehicle_brand'              : 'name',
        'vehicle_frame'              : 'vehicle_frame',
        'vehicle_machine'            : 'vehicle_machine',
        'vehicle_color'              : 'name',
        'vehicle_company'            : 'company_name',

        'vehicle_tax_due'            : 'vehicle_tax_due',
        'vehicle_reg_due'            : 'vehicle_reg_due',
        'vehicle_bpkb'               : 'checkbox',

        'vehicle_insurance_payment'  : 'vehicle_insurance_payment',
        'vehicle_insurance_number'   : 'vehicle_insurance_number',
        'vehicle_insurance_period'   : 'vehicle_insurance_period',
        'vehicle_insurance_start'    : 'vehicle_insurance_start',
        'vehicle_insurance_end'      : 'vehicle_insurance_end',
        'vehicle_insurance'          : 'vehicle_insurance',

        'remarks'                    : 'remarks',
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    // Initialize DataTable
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

    // OPEN CREATE
    $('.btn-create').click(() => {
        form.trigger('reset');
        form.attr('action', routes.store);
        $('#formMethod').val('POST');
        $('#modalTitle').text('Tambah');
        modal.modal('show');
    });

    // OPEN EDIT
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');

        form.attr('action', routes.update(id));
        $('#formMethod').val('PUT');
        $('#modalTitle').text('Edit');

        $.get(routes.edit(id), res => {
            $('#document_div').empty();

            for(let key in fields){
                if(key !== fields[key]){
                    $('#' + key).val(res[key]).trigger('change');
                }
                $('#' + fields[key]).val(res[fields[key]]);
            }

            $.each(res['document'], function (index, file) {
                let file_name = file.document_name.split('/').pop();

                $('#document_div').append(`
                    <div class="document-item d-inline-block text-center m-2" data-index="${file.vehicle_document_id}" data-type="edit" data-name="${file_name}">
                        <div class="image-wrapper">
                            <img
                                src="${file.document_name}"
                                class="img-thumbnail preview-thumbnail"
                                style="width:140px;height:140px;object-fit:cover;cursor:pointer;"
                            >

                            <div class="image-overlay">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>

                        <div class="mt-1 small text-truncate" style="max-width:140px;" title="${file_name}">
                            ${file_name}
                        </div>
                    </div>
                `);
            });
        });

        modal.modal('show');
    });

    // DELETE
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

    plate.addEventListener('input', (e) => {
        e.target.value = formatPlate(e.target.value);
    });

    $(document).ready(function () {
        $('.btn-import').click(() => {
            $('#uploadModal').modal('show');
        });

        $('#document_name').on('change', function () {
            // $('#document_div').empty();

            $.each(this.files, function (index, file) {
                if (!file.type.startsWith('image/')) {
                    return true;
                }

                let reader          = new FileReader();
                const displayName   = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;

                reader.onload = function (e) {
                    $('#document_div').append(`
                        <div class="document-item d-inline-block text-center m-2" data-index="${index}" data-type="input" data-name="${file.name}">
                            <div class="image-wrapper">
                                <img
                                    src="${e.target.result}"
                                    class="img-thumbnail preview-thumbnail"
                                    style="width:140px;height:140px;object-fit:cover;cursor:pointer;"
                                >

                                <div class="image-overlay">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>

                            <div class="mt-1 small text-truncate" style="max-width:140px;" title="${file.name}">
                                ${file.name}
                            </div>
                        </div>
                    `);
                };

                reader.readAsDataURL(file);
            });
        });
    });
    
    $(document).on('click', '.preview-thumbnail', function () {
        const modal     = $('#imagePreviewModal');
        const document  = $(this).closest('.document-item');
        const index     = document[0].dataset.index;
        const type      = document[0].dataset.type;
        const name      = document[0].dataset.name;

        $('#previewImage').attr('src', $(this).attr('src'));
        $('#previewImage').attr('data-index', index);
        $('#previewImage').attr('data-type', type);
        $('#previewImage').attr('data-name', name);
        modal.modal('show');
    });

    $('#deleteImageBtn').click(function(){
        const modal = $('#imagePreviewModal');
        const index = $('#previewImage').attr('data-index');    
        const type  = $('#previewImage').attr('data-type');    
        const name  = $('#previewImage').attr('data-name');    

        Swal.fire({
            title: 'Anda yakin?',
            text: "Anda yakin ingin menghapus " + name + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then(r => {
            if(type == 'edit'){
                $.ajax({
                    url: "{{ route('web.vehicle.destroyImage', ':id') }}".replace(':id', index),
                    type: 'DELETE',
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Dokumen berhasil dihapus.',
                            icon: 'success'
                        }).then(() => {
                            $(`.document-item[data-index="${index}"]`).remove();
                            modal.modal('hide');
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Dokumen gagal dihapus.',
                            icon: 'error'
                        });
                    }
                });
            }else{
                $(`.document-item[data-index="${index}"]`).remove();
                modal.modal('hide');
            }
        });
    });
</script>
