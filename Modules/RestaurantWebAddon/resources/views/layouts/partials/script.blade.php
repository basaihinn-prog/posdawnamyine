<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/theme.js') }}"></script>
{{-- jquery confirm --}}
<script src="{{asset('assets/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>
{{-- jquery validation --}}
<script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
{{-- Custom --}}
<script src="{{ asset('assets/plugins/validation-setup/validation-setup.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/notification.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/form.js') }}?v={{ time() }}"></script>
{{-- Status --}}
<script src="{{ asset('assets/js/custom-ajax.js') }}?v={{ time() }}"></script>
{{-- Toaster --}}
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/custom/custom.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/custom/business-custom.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/choices.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>

<script src="{{ asset('assets/js/choices.min.js') }}"></script>

<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .catch(err => console.error('SW error', err));
}
</script>
<script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.7.0/firebase-app.js";
        import { getMessaging, getToken, isSupported, onMessage } from "https://www.gstatic.com/firebasejs/12.7.0/firebase-messaging.js";

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

            onMessage(messaging, (payload) => {
                if (audioUnlocked && notificationAudio) {
                    notificationAudio.currentTime = 0;
                    notificationAudio.play().catch(() => {});
                }

               showOrderToast();
            });
        })();
    </script>

@stack('js')

@stack('modal-view')

{{-- Toaster Message --}}
@if(Session::has('message'))
    <script>
        toastr.success( "{{ Session::get('message') }}");
    </script>
@endif

@if(Session::has('error'))
    <script>
        toastr.error( "{{ Session::get('error') }}");
    </script>
@endif

@if($errors->any())
<script>
    toastr.warning('Error some occurs!');
</script>
@endif
