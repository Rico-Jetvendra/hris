<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3>{{ $count['employee'] ?? 0 }}</h3>
                            <p>Karyawan</p>
                        </div>

                        <i class="bi bi-people small-box-icon"></i>

                        <a
                            href="{{ route('web.employee.index') }}"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                        >
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>{{ $count['vehicle'] ?? 0 }}</h3>
                            <p>Kendaraan</p>
                        </div>

                        <i class="bi bi-truck small-box-icon"></i>

                        <a
                            href="{{ route('web.vehicle.index') }}"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                        >
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner text-white">
                            <h3>{{ $count['insurance'] ?? 0 }}</h3>
                            <p>Asuransi</p>
                        </div>

                        <i class="bi bi-hospital small-box-icon"></i>

                        <a
                            href="{{ route('web.insurance.index') }}"
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover text-white"
                        >
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>{{ $count['company'] ?? 0 }}</h3>
                            <p>Perusahaan</p>
                        </div>

                        <i class="bi bi-building small-box-icon"></i>

                        <a
                            href="{{ route('web.company.index') }}"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                        >
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title"><b>Kendaraan Jatuh Tempo</b></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Plat Nomor</th>
                                            <th>Pajak</th>
                                            <th>STNK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['vehicle'] as $index => $vehicle)
                                            @php
                                                $taxDue = \Carbon\Carbon::parse($vehicle->vehicle_tax_due);
                                                $regDue = \Carbon\Carbon::parse($vehicle->vehicle_reg_due);

                                                $isTaxDanger = $taxDue->between(
                                                    \Carbon\Carbon::today(),
                                                    \Carbon\Carbon::today()->addDays(7)
                                                );

                                                $isRegDanger = $regDue->between(
                                                    \Carbon\Carbon::today(),
                                                    \Carbon\Carbon::today()->addDays(7)
                                                );
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><a href="{{ route('web.vehicle.index', ['search' => $vehicle->vehicle_number]) }}" target="_blank">{{ $vehicle->vehicle_number }}</a></td>
                                                <td class="{{ $isTaxDanger ? 'text-danger fw-bold' : '' }}">{{ \Carbon\Carbon::parse($vehicle->vehicle_tax_due)->format('d M Y') }}</td>
                                                <td class="{{ $isRegDanger ? 'text-danger fw-bold' : '' }}">{{ \Carbon\Carbon::parse($vehicle->vehicle_reg_due)->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada kendaraan yang jatuh tempo.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title"><b>Karyawan Habis Kontrak</b></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal Habis Kontrak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['employee'] as $index => $employee)
                                            @php
                                                $contractDue = \Carbon\Carbon::parse($employee->end_of_contract);

                                                $isContractDanger = $contractDue->between(
                                                    \Carbon\Carbon::today(),
                                                    \Carbon\Carbon::today()->addDays(7)
                                                );
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><a href="{{ route('web.employee.index', ['search' => $employee->employee_name]) }}" target="_blank">{{ $employee->employee_name }}</a></td>
                                                <td class="{{ $isContractDanger ? 'text-danger fw-bold' : '' }}">{{ \Carbon\Carbon::parse($employee->end_of_contract)->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada karyawan yang habis kontrak.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
