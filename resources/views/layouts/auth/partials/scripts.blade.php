<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
{{-- jquery confirm --}}
<script src="{{asset('assets/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>
{{-- jquery validation --}}
<script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
{{-- Custom --}}
<script src="{{ asset('assets/plugins/validation-setup/validation-setup.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/notification.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/form.js') }}?v={{ time() }}"></script>
<script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.7.0/firebase-app.js";
        import { getMessaging, getToken, isSupported } from "https://www.gstatic.com/firebasejs/12.7.0/firebase-messaging.js";

        (async () => {
            const firebaseConfig = {
                apiKey: "{{ config('firebase.service_credentials.apiKey') }}",
                authDomain: "{{ config('firebase.service_credentials.authDomain') }}",
                projectId: "{{ config('firebase.service_credentials.projectId') }}",
                messagingSenderId: "{{ config('firebase.service_credentials.messagingSenderId') }}",
                appId: "{{ config('firebase.service_credentials.appId') }}"
            };

            const app = initializeApp(firebaseConfig);

            const supported = await isSupported();
            if (!supported) {
                console.warn("Firebase messaging is not supported in this browser");
                return;
            }

            const messaging = getMessaging(app);

            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    getToken(messaging, {
                        vapidKey: "{{ config('firebase.vapid_key') }}"
                    }).then(token => {
                        $('#fcm_token').val(token);
                    });
                }
            });
        })();
    </script>
@stack('js')

@if(Session::has('success'))
    <script>
        Notify('success', null, "{{ Session::get('success') }}");
    </script>
@endif
@if(Session::has('error'))
    <script>
        Notify('error', null, "{{ Session::get('error') }}");
    </script>
@endif
