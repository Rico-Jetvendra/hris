<div class="card">
    <div class="card-header">
        @php
            $page   = request()->segment(1);
            $add    = $page.'.add';
            $edit   = $page.'.edit';
            $delete = $page.'.delete';
        @endphp

        @if(in_array($add, session('permission', [])))
            <button class="btn btn-primary btn-create">
                <i class="bi bi-plus"></i> Tambah
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

                    @if(in_array($edit, session('permission', [])) || in_array($delete, session('permission', [])))
                        <th width="120">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
</div>
