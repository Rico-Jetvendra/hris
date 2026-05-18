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
                        @if(in_array('vehicle.add', session('permission', [])))
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
                                    @if(in_array('vehicle.edit', session('permission', [])) || in_array('vehicle.delete', session('permission', [])))
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

            <form method="POST" id="crudForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="modalTitle">Tambah</span> Kendaraan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
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
                                <input class="form-control datepicker" type="text" name="vehicle_tax_due" id="vehicle_tax_due" placeholder="yyyy/mm/dd"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="vehicle_reg_due">Tgl. Berlaku STNK</label>
                                <input class="form-control datepicker" type="text" name="vehicle_reg_due" id="vehicle_reg_due" placeholder="yyyy/mm/dd"/>
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
                                    <input class="form-control datepicker" type="text" name="vehicle_insurance_start" id="vehicle_insurance_start" placeholder="Dari"/>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input class="form-control datepicker" type="text" name="vehicle_insurance_end" id="vehicle_insurance_end" placeholder="Sampai" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="vehicle_insurance_payment">Tgl. Pembayaran Asuransi</label>
                                <input class="form-control datepicker" type="text" name="vehicle_insurance_payment" id="vehicle_insurance_payment" placeholder="yyyy/mm/dd"/>
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

@include('components.footer')

<script>
    let columns = @json($columns);
    let plate   = document.getElementById('vehicle_number');
    let permissions = @json(session('permission'));

    initCrud({
        routes: {
            store: "{{ route('web.vehicle.store') }}",
            update: id => "{{ route('web.vehicle.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.vehicle.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.vehicle.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.vehicle.data') }}"
        },
        fields: {
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

            'remarks'                    : 'vehicle_remarks',
        },
        columns: columns,
        permissions: permissions
    });

    plate.addEventListener('input', (e) => {
        e.target.value = formatPlate(e.target.value);
    });

    $(document).ready(function () {
        $('.btn-import').click(() => {
            $('#uploadModal').modal('show');
        });
    });
</script>
