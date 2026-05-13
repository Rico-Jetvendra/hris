@include('components.header', ['title' => 'Karyawan'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Karyawan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        @if(in_array('employee.add', session('permission', [])))
                            <button class="btn btn-primary btn-create"><i class="bi bi-plus"></i> Tambah</button>
                        @endif
                        @if(in_array('employee.upload', session('permission', [])))
                            <button class="btn btn-danger btn-import"><i class="bi bi-upload"></i> Import</button>
                        @endif
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
                                    <th width="80">Aksi</th>
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
                        <span id="modalTitle">Tambah</span> Karyawan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#pribadi">Pribadi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#perusahaan">Perusahaan</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="pribadi" class="container tab-pane active"><br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_name">Nama Karyawan</label>
                                        <input class="form-control text-uppercase" type="text" name="employee[employee_name]" id="employee_name" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_pob">Tempat Lahir</label>
                                        <input class="form-control text-uppercase" type="text" name="employee[employee_pob]" id="employee_pob" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_dob">Tgl. Lahir</label>
                                        <input class="form-control datepicker" type="text" name="employee[employee_dob]" id="employee_dob" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_sex">Jenis Kelamin</label>
                                        <select class="form-select searchable-select" name="employee[employee_sex]" id="employee_sex" required>
                                            <option value="">----- Pilih Jenis Kelamin -----</option>
                                            @foreach($combo['sex'] as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_blood">Gol. Darah</label>
                                        <select class="form-select searchable-select" name="employee[employee_blood]" id="employee_blood" required>
                                            <option value="">----- Pilih Golongan Darah -----</option>
                                            @foreach($combo['blood'] as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_religion">Agama</label>
                                        <select class="form-select searchable-select" name="employee[employee_religion]" id="employee_religion" required>
                                            <option value="">----- Pilih Agama -----</option>
                                            @foreach($combo['religion'] as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_marriage">Status Pernikahan</label>
                                        <select class="form-select searchable-select" name="employee[employee_marriage]" id="employee_marriage" required>
                                            <option value="">----- Pilih Status Pernikahan -----</option>
                                            @foreach($combo['marriage'] as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_ktp">KTP</label>
                                        <input class="form-control ktp-mask" type="text" name="employee[employee_ktp]" id="employee_ktp" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_npwp">NPWP</label>
                                        <input class="form-control npwp-mask" type="text" name="employee[employee_npwp]" id="employee_npwp" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_education">Pendidikan Terakhir</label>
                                        <select class="form-select searchable-select" name="employee[employee_education]" id="employee_education" required>
                                            <option value="">----- Pilih Pendidikan Terakhir -----</option>
                                            @foreach($combo['education'] as $value)
                                                <option value="{{ $value['id'] }}">{{ $value['name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_father">Nama Ayah</label>
                                        <input class="form-control text-uppercase" type="text" name="employee[employee_father]" id="employee_father" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_mother">Nama Ibu</label>
                                        <input class="form-control text-uppercase" type="text" name="employee[employee_mother]" id="employee_mother" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_email">Email</label>
                                        <input class="form-control" type="email" name="employee[employee_email]" id="employee_email"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_home_phone">Telepon Rumah</label>
                                        <input class="form-control" type="text" name="employee[employee_home_phone]" id="employee_home_phone" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_phone">Telepon</label>
                                        <input class="form-control" type="text" name="employee[employee_phone]" id="employee_phone" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_address">Alamat</label>
                                        <textarea class="form-control" name="employee[employee_address]" id="employee_address" rows="3" style="resize:none"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="employee_remarks">Keterangan</label>
                                        <textarea class="form-control" name="employee[employee_remarks]" id="employee_remarks" rows="3" style="resize:none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="perusahaan" class="container tab-pane fade"><br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="department_id">Departemen</label>
                                        <select class="form-select searchable-select" name="company[department_id]" id="department_id" required>
                                            <option value="">----- Pilih Departemen -----</option>
                                            @foreach($combo['department'] as $value)
                                                <option value="{{ $value['department_id'] }}">{{ $value['department_name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="company_id">Perusahaan</label>
                                        <select class="form-select searchable-select" name="company[company_id]" id="company_id" required>
                                            <option value="">----- Pilih Perusahaan -----</option>
                                            @foreach($combo['company'] as $value)
                                                <option value="{{ $value['company_id'] }}">{{ $value['company_name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="entry_date">Tgl. Masuk</label>
                                        <input class="form-control datepicker" type="text" name="company[entry_date]" id="entry_date" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="position_id">Jabatan</label>
                                        <select class="form-select searchable-select" name="company[position_id]" id="position_id" required>
                                            <option value="">----- Pilih Jabatan -----</option>
                                            @foreach($combo['position'] as $value)
                                                <option value="{{ $value['position_id'] }}">{{ $value['position_name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="branch_id">Cabang</label>
                                        <select class="form-select searchable-select" name="company[branch_id]" id="branch_id" required>
                                            <option value="">----- Pilih Cabang -----</option>
                                            @foreach($combo['branch'] as $value)
                                                <option value="{{ $value['branch_id'] }}">{{ $value['branch_name'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="end_of_contract">Akhir Kontrak</label>
                                        <input class="form-control datepicker" type="text" name="company[end_of_contract]" id="end_of_contract" required/>
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

            <form method="POST" action="{{ route('web.employee.upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="modalTitle">Upload</span> Karyawan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="file">File Karyawan</label>
                                <input class="form-control" type="file" name="file" id="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="resign">Resign</label><br/>
                                <input type="checkbox" name="resign" id="resign" value="1"/>
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
    let permissions = @json(session('permission'));

    initCrud({
        routes: {
            store: "{{ route('web.employee.store') }}",
            update: id => "{{ route('web.employee.update', ':id') }}".replace(':id', id),
            edit: id => "{{ route('web.employee.edit', ':id') }}".replace(':id', id),
            destroy: id => "{{ route('web.employee.destroy', ':id') }}".replace(':id', id),
            data: "{{ route('web.employee.data') }}"
        },
        fields: {
            // Employee
            'employee_name': 'employee_name',
            'employee_pob': 'employee_pob',
            'employee_dob': 'employee_dob',
            'employee_sex': 'name',
            'employee_blood': 'name',
            'employee_religion': 'name',
            'employee_marriage': 'name',
            'employee_ktp': 'employee_ktp',
            'employee_npwp': 'employee_npwp',
            'employee_education': 'name',
            'employee_father': 'employee_father',
            'employee_mother': 'employee_mother',
            'employee_email': 'employee_email',
            'employee_home_phone': 'employee_home_phone',
            'employee_phone': 'employee_phone',
            'employee_address': 'employee_address',
            'employee_remarks': 'employee_remarks',

            // Company
            'branch_id': 'branch_name',
            'company_id': 'company_name',
            'department_id': 'department_name',
            'position_id': 'position_name',
            'entry_date': 'entry_date',
            'end_of_contract': 'end_of_contract',
        },
        columns: columns,
        permissions: permissions
    });


    $(document).ready(function () {
        $('.btn-import').click(() => {
            $('#uploadModal').modal('show');
        });
    });
</script>
