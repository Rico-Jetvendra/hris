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
            <div class="row gy-4 mb-3">
                <div class="col-lg-3 col-md-6">
                    <div class="card text-white border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #3b82f6, #2563eb);border-radius: 20px;">
                        <div class="card-body pb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1 opacity-75 fs-5">
                                        Karyawan
                                    </p>

                                    <h1 class="fw-bold mb-0 display-4">
                                        {{ $count['employee'] ?? 0 }}
                                    </h1>
                                </div>

                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('web.employee.index') }}" class="text-white text-decoration-none">
                            <div class="px-3 py-2 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.12);backdrop-filter: blur(5px);transition: 0.2s;">
                                <span class="fw-semibold">More info</span>

                                <i class="bi bi-arrow-right-short ms-1"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card text-white border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #019d61, #019d61);border-radius: 20px;">
                        <div class="card-body pb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1 opacity-75 fs-5">
                                        Kendaraan
                                    </p>

                                    <h1 class="fw-bold mb-0 display-4">
                                        {{ $count['vehicle'] ?? 0 }}
                                    </h1>
                                </div>

                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-truck"></i>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('web.vehicle.index') }}" class="text-white text-decoration-none">
                            <div class="px-3 py-2 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.12);backdrop-filter: blur(5px);transition: 0.2s;">
                                <span class="fw-semibold">More info</span>

                                <i class="bi bi-arrow-right-short ms-1"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card text-white border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #fbbf24, #f59e0b);border-radius: 20px;">
                        <div class="card-body pb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1 opacity-75 fs-5">
                                        Asuransi
                                    </p>

                                    <h1 class="fw-bold mb-0 display-4">
                                        {{ $count['insurance'] ?? 0 }}
                                    </h1>
                                </div>

                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-hospital"></i>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('web.insurance.index') }}" class="text-white text-decoration-none">
                            <div class="px-3 py-2 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.12);backdrop-filter: blur(5px);transition: 0.2s;">
                                <span class="fw-semibold">More info</span>

                                <i class="bi bi-arrow-right-short ms-1"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card text-white border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #ef4444, #ef4444);border-radius: 20px;">
                        <div class="card-body pb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1 opacity-75 fs-5">
                                        Perusahaan
                                    </p>

                                    <h1 class="fw-bold mb-0 display-4">
                                        {{ $count['perusahaan'] ?? 0 }}
                                    </h1>
                                </div>

                                <div class="fs-1 opacity-50">
                                    <i class="bi bi-building"></i>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('web.company.index') }}" class="text-white text-decoration-none">
                            <div class="px-3 py-2 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.12);backdrop-filter: blur(5px);transition: 0.2s;">
                                <span class="fw-semibold">More info</span>

                                <i class="bi bi-arrow-right-short ms-1"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row align-items-stretch gy-4 mb-3">
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Gender Karyawan (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="genderChart"></canvas>
                                </div>
                            </div>


                            <div class="accordion mt-3" id="sexAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseSex">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseSex" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @foreach(config('combobox.sex') as $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $item['color'] }};"></i>
                                                        {{ $item['name'] }}
                                                    </span>

                                                    <strong>{{ $count['sex'][$item['id']] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Agama Karyawan (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="religionChart"></canvas>
                                </div>
                            </div>


                            <div class="accordion mt-3" id="religionAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseReligion">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseReligion" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            
                                            @foreach(config('combobox.religions') as $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $item['color'] }};"></i>
                                                        {{ $item['name'] }}
                                                    </span>

                                                    <strong>{{ $count['religion'][$item['id']] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Status Kontrak Karyawan
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="contractChart"></canvas>
                                </div>
                            </div>

                            <div class="accordion mt-3" id="contractAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseContract">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseContract" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <div class="d-flex justify-content-between">
                                                <span>
                                                    <i class="bi bi-circle-fill" style="color:#10b981;"></i>
                                                    Aktif
                                                </span>

                                                <strong>{{ $count['contract'][1] ?? 0 }}</strong>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <span>
                                                    <i class="bi bi-circle-fill" style="color:#ef4444;"></i>
                                                    Resign
                                                </span>

                                                <strong>{{ $count['contract'][0] ?? 0 }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Status Pernikahan Karyawan (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="marriageChart"></canvas>
                                </div>
                            </div>

                            <div class="accordion mt-3" id="marriageAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseMarriage">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseMarriage" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            
                                            @foreach(config('combobox.marriage') as $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $item['color'] }};"></i>
                                                        {{ $item['name'] }}
                                                    </span>

                                                    <strong>{{ $count['marriage'][$item['id']] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Usia Karyawan (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="ageChart"></canvas>
                                </div>
                            </div>

                            <div class="accordion mt-3" id="ageAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseAge">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseAge" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @php
                                                $ageColors  = [
                                                    '#3b82f6',
                                                    '#10b981',
                                                    '#f59e0b',
                                                    '#ef4444',
                                                    '#8b5cf6'
                                                ];
                                            @endphp
                                            @foreach($count['age'] as $key => $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $ageColors[$key] ?? '#000000' }}"></i>
                                                        {{ $item['age'] }}
                                                    </span>

                                                    <strong>{{ $item['total'] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Pendidikan Terakhir Karyawan (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="educationChart"></canvas>
                                </div>
                            </div>


                            <div class="accordion mt-3" id="educationAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseEducation">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseEducation" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @foreach(config('combobox.education') as $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $item['color'] }};"></i>
                                                        {{ $item['name'] }}
                                                    </span>

                                                    <strong>{{ $count['education'][$item['id']] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Karyawan Per Departemen (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="departmentChart"></canvas>
                                </div>
                            </div>

                            <div class="accordion mt-3" id="departmentAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseDepartment">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseDepartment" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @php
                                                $departmentColors  = [
                                                    '#3b82f6',
                                                    '#10b981',
                                                    '#f59e0b',
                                                    '#ef4444',
                                                    '#8b5cf6'
                                                ];
                                            @endphp
                                            @foreach($count['department'] as $key => $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $departmentColors[$key] ?? '#000000' }}"></i>
                                                        {{ $item['department_name'] }}
                                                    </span>

                                                    <strong>{{ $item['total'] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Karyawan Per Cabang (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="branchChart"></canvas>
                                </div>
                            </div>

                            <div class="accordion mt-3" id="branchAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseBranch">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseBranch" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @php
                                                $branchColors  = [
                                                    '#3b82f6',
                                                    '#10b981',
                                                    '#f59e0b',
                                                    '#ef4444',
                                                    '#8b5cf6'
                                                ];
                                            @endphp
                                            @foreach($count['branch'] as $key => $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $branchColors[$key] ?? '#000000' }}"></i>
                                                        {{ $item['branch_name'] }}
                                                    </span>

                                                    <strong>{{ $item['total'] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Karyawan Per Perusahaan (Aktif)
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="companyChart"></canvas>
                                </div>
                            </div>

                            <div class="accordion mt-3" id="companyAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseCompany">
                                            Detail Data
                                        </button>
                                    </h2>

                                    <div id="collapseCompany" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @php
                                                $companyColors  = [
                                                    '#3b82f6',
                                                    '#10b981',
                                                    '#f59e0b',
                                                    '#ef4444',
                                                    '#8b5cf6'
                                                ];
                                            @endphp
                                            @foreach($count['companyGraph'] as $key => $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        <i class="bi bi-circle-fill" style="color:{{ $companyColors[$key] ?? '#000000' }}"></i>
                                                        {{ $item['company_name'] }}
                                                    </span>

                                                    <strong>{{ $item['total'] ?? 0 }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-4 mb-3">
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
                                                $today      = \Carbon\Carbon::today();
                                                $nextWeek   = \Carbon\Carbon::today()->addDays(7);
                                                $taxDue     = \Carbon\Carbon::parse($vehicle->vehicle_tax_due);
                                                $regDue     = \Carbon\Carbon::parse($vehicle->vehicle_reg_due);

                                                $isTaxDanger = $taxDue->isPast() || $taxDue->between($today, $nextWeek);

                                                $isRegDanger = $regDue->isPast() || $regDue->between($today, $nextWeek);
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><a href="{{ route('web.vehicle.index', ['search' => $vehicle->vehicle_number]) }}" target="_blank">{{ $vehicle->vehicle_number }}</a></td>
                                                <td class="{{ $isTaxDanger ? 'text-danger fw-bold' : '' }}">{{ \Carbon\Carbon::parse($vehicle->vehicle_tax_due)->format('d M Y') }}</td>
                                                <td class="{{ $isRegDanger ? 'text-danger fw-bold' : '' }}">{{ \Carbon\Carbon::parse($vehicle->vehicle_reg_due)->format('d M Y') }}</td>
                                            </tr>
                                        @empty
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
                            <h5 class="card-title"><b>Karyawan Ultah Bulan Ini</b></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Karyawan</th>
                                            <th>Umur</th>
                                            <th>Tgl. Ultah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['hbd'] as $index => $h)
                                            @php
                                                $birthday       = \Carbon\Carbon::parse($h->employee_dob);
                                                $nextBirthday   = $birthday->copy()->year(now()->year);

                                                $isBirthdayToday = $nextBirthday->isToday();

                                                $isHbdDanger = !$isBirthdayToday && $nextBirthday->between(
                                                    now(),
                                                    now()->copy()->addDays(3)
                                                );
                                            @endphp

                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <td>
                                                    <a href="{{ route('web.employee.index', ['search' => $h->employee_name]) }}" target="_blank">
                                                        {{ $h->employee_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $h->age }}</td>

                                                <td class="
                                                    {{ $isBirthdayToday ? 'text-primary fw-bold' : '' }}
                                                    {{ $isHbdDanger ? 'text-warning fw-bold' : '' }}
                                                ">
                                                    {{ $birthday->format('d M Y') }}
                                                </td>
                                            </tr>
                                        @empty
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
                                            <th>Tgl. Habis Kontrak</th>
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
                                                <td>{{ $loop->iteration }}</td>
                                                <td><a href="{{ route('web.employee.index', ['search' => $employee->employee_name]) }}" target="_blank">{{ $employee->employee_name }}</a></td>
                                                <td class="{{ $isContractDanger ? 'text-danger fw-bold' : '' }}">{{ \Carbon\Carbon::parse($employee->end_of_contract)->format('d M Y') }}</td>
                                            </tr>
                                        @empty
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

@push('scripts')
<script>
    const religions         = @json(config('combobox.religions'));
    const religionCount     = @json($count['religion'] ?? []);
    const religionLabels    = religions.map(r => r.name);
    const religionColors    = religions.map(r => r.color);
    const religionData      = religions.map(r => religionCount[r.id] ?? 0);

    const marriages         = @json(config('combobox.marriage'));
    const marriageCount     = @json($count['marriage'] ?? []);
    const marriageLabels    = marriages.map(r => r.name);
    const marriageColors    = marriages.map(r => r.color);
    const marriageData      = marriages.map(r => marriageCount[r.id] ?? 0);

    const educations        = @json(config('combobox.education'));
    const educationCount    = @json($count['education'] ?? []);
    const educationLabels   = educations.map(r => r.name);
    const educationColors   = educations.map(r => r.color);
    const educationData     = educations.map(r => educationCount[r.id] ?? 0);

    const departmentCount   = @json($count['department'] ?? []).sort((a, b) => b.total - a.total).slice(0, 5);
    const departmentLabels  = departmentCount.map(r => r.department_name);
    const departmentData    = departmentCount.map(r => r.total ?? 0);
    const departmentColors  = [
        '#3b82f6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6'
    ];

    const branchCount       = @json($count['branch'] ?? []).sort((a, b) => b.total - a.total).slice(0, 5);
    const branchLabels      = branchCount.map(r => r.branch_name);
    const branchData        = branchCount.map(r => r.total ?? 0);
    const branchColors      = [
        '#3b82f6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6'
    ];

    const companyCount       = @json($count['companyGraph'] ?? []).sort((a, b) => b.total - a.total).slice(0, 5);
    const companyLabels      = companyCount.map(r => r.company_name);
    const companyData        = companyCount.map(r => r.total ?? 0);
    const companyColors      = [
        '#3b82f6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6'
    ];

    const ageCount       = @json($count['age'] ?? []).sort((a, b) => b.total - a.total).slice(0, 5);
    const ageLabels      = ageCount.map(r => r.age);
    const ageData        = ageCount.map(r => r.total ?? 0);
    const ageColors      = [
        '#3b82f6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6'
    ];

    $(document).ready(function() {
        createChart(
            'genderChart',
            'doughnut',
            ['Pria', 'Wanita'],
            [
                {{ $count['sex'][1] ?? 0 }},
                {{ $count['sex'][2] ?? 0 }}
            ],
            [
                '#0d6efd',
                '#ff69b4'
            ]
        );

        createChart(
            'contractChart',
            'doughnut',
            ['Aktif', 'Resign'],
            [
                {{ $count['contract'][1] ?? 0 }},
                {{ $count['contract'][0] ?? 0 }}
            ],
            [
                '#10b981',
                '#ef4444'
            ]
        );

        createChart(
            'religionChart',
            'doughnut',
            religionLabels,
            religionData,
            religionColors
        );

        createChart(
            'educationChart',
            'bar',
            educationLabels,
            educationData,
            educationColors,
            'y'
        );

        createChart(
            'marriageChart',
            'bar',
            marriageLabels,
            marriageData,
            marriageColors,
            'y'
        );

        createChart(
            'departmentChart',
            'bar',
            departmentLabels,
            departmentData,
            departmentColors,
            'y',
        );

        createChart(
            'branchChart',
            'bar',
            branchLabels,
            branchData,
            branchColors,
            'y',
        );

        createChart(
            'companyChart',
            'bar',
            companyLabels,
            companyData,
            companyColors,
            'y',
        );

        createChart(
            'ageChart',
            'bar',
            ageLabels,
            ageData,
            ageColors,
            'y',
        );
    });

    function createChart(elementId, chartType, labels, data, colors, orientation = 'x') {
        const ctx       = document.getElementById(elementId);
        const cutout    = chartType == 'bar' ? '100%': '60%';

        new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: {
                indexAxis: orientation, 
                maintainAspectRatio: false,
                cutout: cutout,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            }
        });
    }

    function createColors(total){
        const colors = [];

        for (let i = 0; i < total; i++) {
            const color = '#' + Math.floor(Math.random() * 16777215)
                .toString(16)
                .padStart(6, '0');

            colors.push(color);
        }

        return colors;
    }
</script>
@endpush
