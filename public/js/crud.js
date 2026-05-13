function initCrud({ routes, fields, columns, permissions }) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const modal         = $('#crudModal');
    const form          = $('#crudForm');
    const defaultSearch = $('#defaultSearch').val();
    const segment1      = window.location.pathname.split('/').filter(Boolean).at(-1).replace(/-/g, "_");
    const tableColumns  = [
        { data: 'DT_RowIndex', orderable: false, searchable: false },

        ...columns.map(col => ({
            data: col.field,
            orderable: col.orderable ?? true,
            searchable: col.searchable ?? true
        })),
    ];

    if(permissions.includes(segment1+'.edit') || permissions.includes(segment1+'.delete')){
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
            for(let key in fields){
                if(key !== fields[key]){
                    if(fields[key] == 'checkbox'){
                        $('#' + key).prop('checked', true);
                    }
                    $('#' + key).val(res[key]).trigger('change');
                }
                $('#' + fields[key]).val(res[fields[key]]);
            }
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
}
