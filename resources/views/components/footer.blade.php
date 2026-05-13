
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">
            Last Login at : {{ \Carbon\Carbon::parse(session('user')->login_date)->format('D, d F Y H:i:s') }}
        </div>
        <strong>
            Veron Indonesia
        </strong>
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('js/crud.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-app.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyBRfYzr2qPCLKunvXEtb1DnTtX9SsL5OVw",
            authDomain: "fcm-veron-salesapp.firebaseapp.com",
            projectId: "fcm-veron-salesapp",
            storageBucket: "fcm-veron-salesapp.firebasestorage.app",
            messagingSenderId: "661546324879",
            appId: "1:661546324879:web:ae94db9d19c8473658f1fa",
            measurementId: "G-MDBQQNXVB7"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        const VAPID_KEY = "BD5CzWSRAcM_DEr5wMPWIUplbQoWMUkOn9jXbg6aWH-i6ogpdVf9jzOF1xsLlTwCmJAm9y_jzUuNHUObw0_dSGo";

        async function initWebPush() {
            try {
                const permission = await Notification.requestPermission();
                const registration = await navigator.serviceWorker.register('./firebase-messaging-sw.js', {
                    scope: '/taking-order/public/'
                });

                if (permission !== "granted") {
                    console.log("Notification permission denied");
                    return;
                }

                const token = await getToken(messaging, {
                    vapidKey: VAPID_KEY,
                    serviceWorkerRegistration: registration
                });

                if (!token) {
                    console.log("Failed to generate token");
                    return;
                }

                sendTokenToSession(token);
            } catch (err) {
                console.error("Error getting token:", err);
            }
        }

        @if (session()->has('webpush_initialized') === false)
            initWebPush();
        @endif

        async function sendTokenToSession(token) {
            await fetch('./save-web-token-session', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ token })
            })
            .then(res => res.json())
            .then(data => {
                console.log("Session updated:", data);
            })
            .catch(err => {
                console.error("Error saving token to session:", err);
            });
        }
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
