
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>
          Copyright &copy; 2014-2026&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
      </footer>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/adminlte.js') }}"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{ asset('js/crud.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.datepicker').each(function () {
                $(this).datepicker({
                    uiLibrary: 'bootstrap5',
                    format: 'yyyy-mm-dd',
                    placeholder: 'yyyy-mm-dd'
                });
            });

            $('.npwp-mask').mask('00.000.000.0.000.000');
            $('.ktp-mask').mask('0000000000000000');
            $('.frame-mask').mask('ZZZZZZZZZZZZZZZZZZZZ', {
                translation:{
                    'Z': {
                        pattern: /[A-Za-z0-9]/
                    }
                }
            });
            $('.machine-mask').mask('ZZZZZZZZZZZZZZZZZZZZ', {
                translation:{
                    'Z': {
                        pattern: /[A-Za-z0-9]/
                    }
                }
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}"
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif
  </body>
  <!--end::Body-->
</html>
