@include('components.header', ['title' => 'Kunjungan'])

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Kunjungan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('web.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kunjungan</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
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

                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="viewModal" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="visit_id" value="">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#informasi">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#lampiran">Lampiran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#komentar">Komentar</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="informasi" class="container tab-pane active"><br>
                        <div class="card mb-3">
                            <div class="card-header fw-bold">
                                Visit Information
                            </div>

                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-12">

                                        <div class="row mb-2">
                                            <div class="col-4 fw-semibold">Visit Name</div>
                                            <div class="col-8" id="visit_name"></div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-4 fw-semibold">Customer</div>
                                            <div class="col-8" id="customer_name"></div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-4 fw-semibold">Sales</div>
                                            <div class="col-8" id="sales_name"></div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-4 fw-semibold">Competitor</div>
                                            <div class="col-8" id="visit_competitor"></div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-4 fw-semibold">Address</div>
                                            <div class="col-md-8" id="visit_address"></div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-4 fw-semibold">Visit Result</div>
                                            <div class="col-8" id="visit_result"></div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-4 fw-semibold">Business Potential</div>
                                            <div class="col-8" id="business_potential"></div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-4 fw-semibold">Status</div>
                                            <div class="col-8">
                                                <span class="badge bg-success" id="visit_status_name"></span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header fw-bold">
                                Check In / Check Out Information
                            </div>

                            <div class="card-body">

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Visit Coordinate</div>
                                    <div class="col-md-8">
                                        <span id="visit_latitude"></span>,
                                        <span id="visit_longitude"></span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Check In Coordinate</div>
                                    <div class="col-md-8">
                                        <span id="check_in_latitude"></span>,
                                        <span id="check_in_longitude"></span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Check In Time</div>
                                    <div class="col-md-8">
                                        <span id="visit_check_in"></span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Check Out Coordinate</div>
                                    <div class="col-md-8">
                                        <span id="check_out_latitude">-</span>,
                                        <span id="check_out_longitude">-</span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Check Out Time</div>
                                    <div class="col-md-8">
                                        <span id="visit_check_out"></span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Remark</div>
                                    <div class="col-md-8">
                                        <span id="remarks"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="lampiran" class="container tab-pane"><br>
                        <div class="row" id="attachment">

                        </div>
                    </div>
                    <div id="komentar" class="container tab-pane"><br>
                        <div id="comment-list" style="text-align:justify;">

                        </div>

                        <div class="alert alert-danger" role="alert" id="comment-error" style="display: none;"></div>

                        <div id="new-comment" class="mt-3">
                            <div class="mb-3">
                                <label for="comment" class="form-label">Add Comment</label>
                                <textarea class="form-control" id="comment" rows="3" style="resize: none;"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary" id="add-comment-btn">Add Comment</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('components.footer')

<script>
    const columns = @json($columns);

    const tableColumns  = [
        { data: 'DT_RowIndex', orderable: false, searchable: false },

        ...columns.map(col => ({
            data: col.field,
            orderable: col.orderable ?? true,
            searchable: col.searchable ?? true
        })),
    ];

    tableColumns.push({
        data: 'action',
        orderable: false,
        searchable: false
    });

    $.fn.DataTable.ext.pager.numbers_length = 5;

    const table = $('#dataTable').DataTable({
        responsive:true,
        autoWidth:false,
        processing: true,
        serverSide: true,
        ajax: "{{ route('web.visit.data') }}",
        pagingType: "simple_numbers",
        columns: tableColumns
    });

    $(document).on('click', '.btn-view', function () {
        const id    = $(this).data('id');
        const modal = $('#viewModal');

        $('.btn-view').prop('disabled', true);

        $.get("{{ route('web.visit.edit', ':id') }}".replace(':id', id)).then(function (res) {
            $('#visit_id').val(id);

            renderInformation(modal, res.information);
            renderAttachment(modal, res.attachment);
            renderComments(modal, res.comment);

            $('.btn-view').prop('disabled', false);
            modal.modal('show');
        });
    });

    $(document).on('click', '#add-comment-btn', function () {
        const modal     = $('#viewModal');
        const id        = modal.find('#visit_id').val();
        const comment   = modal.find('#comment').val();

        if (!comment) {
            errorMessage('Comment cannot be empty.');
            return;
        }

        $('#add-comment-btn').prop('disabled', true);

        $.post("{{ route('web.visit.comment.store') }}", { _token: $('meta[name="csrf-token"]').attr('content'), visit_id: id, comment: comment }).then(function (res) {
            renderComments(modal, res.comments);

            resetComment();

            $('#add-comment-btn').prop('disabled', false);
        }).catch(function (err) {
            console.error(err);
            errorMessage('Failed to add comment. Please try again.');

            $('#add-comment-btn').prop('disabled', false);
        });
    });

    function renderInformation(modal, information){
        $("#visit_name").text(information.visit_name);
        $("#visit_result").text(information.visit_result);
        $("#business_potential").text(information.business_potential);
        $("#visit_address").text(information.visit_address);
        $("#visit_competitor").text(information.visit_competitor);

        $("#customer_name").text(information.customer_name);
        $("#customer_type_name").text(information.customer_type_name);
        $("#sales_name").text(information.sales_name);

        $("#visit_status_name").text(information.visit_status_name);

        $("#visit_latitude").text(information.visit_latitude);
        $("#visit_longitude").text(information.visit_longitude);

        $("#check_in_latitude").text(information.check_in_latitude);
        $("#check_in_longitude").text(information.check_in_longitude);

        $("#check_out_latitude").text(information.check_out_latitude ?? "-");
        $("#check_out_longitude").text(information.check_out_longitude ?? "-");

        $("#visit_check_in").text(information.visit_check_in);
        $("#visit_check_out").text(information.visit_check_out ?? "-");

        $("#remarks").text(information.remarks ?? "-");
    }

    function renderAttachment(modal, attachment){
        const attachmentContainer = modal.find('#attachment');
        attachmentContainer.empty();

        if (attachment.length === 0) {
            attachmentContainer.append('<p>No attachments available.</p>');
            return;
        }

        attachment.forEach(att => {
            const attachmentHtml = `
                <div class="col-md-4 card p-0 mb-3 mx-1">
                    <div class="card-body">
                        <a href="${att.visit_attachment}" target="_blank">
                            <img src="${att.visit_attachment}" class="img-fluid" alt="Attachment">
                        </a>
                    </div>
                </div>
            `;

            attachmentContainer.append(attachmentHtml);
        });
    }

    function renderComments(modal, comments) {
        const commentList            = modal.find('#comment-list');
        const repliesList            = modal.find('#replies-list');
        const currentUserId          = {{ session('user')->id }};
        const replies                = comments.filter(comment => comment.replies_id !== null);
        const commentsWithoutReplies = comments.filter(comment => comment.replies_id === null);

        commentList.empty();
        repliesList.empty();

        if (comments.length === 0) {
            commentList.append('<p>No comments available.</p>');
            return;
        }

        commentsWithoutReplies.forEach(comment => {
            const deleteButton = comment.created_by == currentUserId
                    ? `<small class="text-danger" style="cursor:pointer;" onclick="deleteComment(${comment.visit_comment_id})">
                            <i class="bi bi-trash"></i> Delete
                    </small>`
                    : "";

            const commentHtml = `
                <div class="card mb-2" id="comment-${comment.visit_comment_id}">
                    <div class="card-body">
                        <p>${comment.comment}</p>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                By ${comment.username} on ${comment.created_date}
                            </small>

                            <div>
                                <small class="text-primary" style="cursor:pointer;" onclick="replies(${comment.visit_comment_id})">
                                    <i class="bi bi-reply"></i> Reply
                                </small>
                                ${deleteButton}
                            </div>
                        </div>
                        <div class="replies-list" id="replies-${comment.visit_comment_id}" class="mt-2" style="margin-left: 5px;"></div>
                        <div class="alert alert-danger" role="alert" id="replies-error-${comment.visit_comment_id}" style="display: none;"></div>
                        <div class="mt-2" id="reply-form-${comment.visit_comment_id}" style="display: none;">
                            <textarea class="form-control mb-2" id="reply-comment-${comment.visit_comment_id}" rows="2" placeholder="Write a reply..." style="resize: none;"></textarea>
                            <button type="button" class="btn btn-sm btn-primary btn-replies" onclick="submitReply(${comment.visit_comment_id})">Submit Reply</button>
                            <button type="button" class="btn btn-sm btn-secondary btn-replies" onclick="cancelReply(${comment.visit_comment_id})">Cancel</button>
                    </div>
                </div>
            `;

            commentList.append(commentHtml);
        });

        renderReplies(modal, replies, currentUserId);
    }

    function renderReplies(modal, replies, currentUserId) {
        const repliesList = modal.find('#replies-list');

        repliesList.empty();

        replies.forEach(reply => {
            var repliesContainer = $(`#replies-${reply.replies_id}`);

            const deleteButton = reply.created_by == currentUserId
                    ? `<small class="text-danger" style="cursor:pointer;" onclick="deleteComment(${reply.visit_comment_id})">
                            <i class="bi bi-trash"></i> Delete
                    </small>`
                    : "";

            const replyHtml = `
                <div class="card mb-2" id="comment-${reply.visit_comment_id}">
                    <div class="card-body">
                        <p>${reply.comment}</p>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                By ${reply.username} on ${reply.created_date}
                            </small>

                            <div>
                                ${deleteButton}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            repliesContainer.append(replyHtml);
        });
    }

    function replies(id){
        const replyForm = $(`#reply-form-${id}`);
        replyForm.show();
    }

    function submitReply(id){
        const replyComment = $(`#reply-comment-${id}`).val();
        const visitId       = $('#visit_id').val();

        $('.btn-replies').prop('disabled', true);

        if (!replyComment) {
            errorReplies('Reply cannot be empty.', id);
            return;
        }

        $.post("{{ route('web.visit.replies.store') }}", { _token: $('meta[name="csrf-token"]').attr('content'), visit_id: visitId, comment: replyComment, replies_id: id }).then(function (res) {
            renderComments($('#viewModal'), res.comments);

            resetReplies(id);

            $('.btn-replies').prop('disabled', false);
        }).catch(function (err) {
            console.error(err);
            errorReplies('Failed to add reply. Please try again.', id);

            $('.btn-replies').prop('disabled', false);
        });
    }

    function cancelReply(id){
        const replyForm = $(`#reply-form-${id}`);
        replyForm.hide();
    }

    function deleteComment(id){
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

            $.ajax({
                url: "{{ route('web.visit.comment.store') }}/" + id,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.success) {
                        $('#comment-' + id).remove();

                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Komen berhasil dihapus.',
                            icon: 'success'
                        });
                    } else {
                        errorMessage('Failed to delete comment. Please try again.');
                    }
                },
                error: function (err) {
                    console.error(err);
                    errorMessage('Failed to delete comment. Please try again.');
                }
            });
        });
    }

    function errorMessage(message) {
        const modal = $('#viewModal');

        modal.find('#comment-error').text(message);
        modal.find('#comment-error').show();

        $('#add-comment-btn').prop('disabled', false);
    }

    function errorReplies(message, id) {
        const modal = $('#viewModal');

        modal.find('#replies-error-' + id).text(message);
        modal.find('#replies-error-' + id).show();

        $('.btn-replies').prop('disabled', false);
    }

    function resetComment(){
        const modal = $('#viewModal');

        modal.find('#comment').val('');
        modal.find('#comment-error').text('');
        modal.find('#comment-error').hide();

        $('#add-comment-btn').prop('disabled', false);
    }

    function resetReplies(id){
        const modal = $('#viewModal');

        modal.find('#reply-comment-' + id).val('');
        modal.find('#replies-error-' + id).text('');
        modal.find('#replies-error-' + id).hide();

        $('.btn-replies').prop('disabled', false);
    }
</script>
