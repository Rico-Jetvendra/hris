<div class="card">
    <div class="card-header">
        <button class="btn btn-primary btn-create">
            <i class="bi bi-plus"></i> Tambah
        </button>
    </div>

    <div class="card-body">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="60">No</th>
                    @foreach ($columns as $col)
                        <th>{{ $col['label'] }}</th>
                    @endforeach
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
</div>
