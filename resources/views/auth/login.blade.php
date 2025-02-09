@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Log;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Laravel Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, projects">
    <meta name="author" content="Dreamguys - Laravel Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Inventara</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.jpg') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        /* Menyesuaikan ukuran logo */
        .login-logo img {
            height: 60px; /* Sesuaikan ukuran logo di sini */
            object-fit: contain; /* Memastikan proporsi gambar tetap */
        }
    </style>
</head>
<body class="account-page">

<div class="main-wrapper">
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 100px;">
                    </div>
                    <div class="login-userheading">
                        <h3>Sign In</h3>
                        <h4>Please login to your account</h4>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-login">
                            <label>Email</label>
                            <div class="form-addons">
                                <input type="email" name="email" placeholder="Enter your email address" required>
                                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="Email Icon">
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Password</label>
                            <div class="pass-group">
                                <input type="password" name="password" class="pass-input" placeholder="Enter your password" required>
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login">
                            <div class="alreadyuser">
                                @if (Route::has('password.request'))
                                    <h4>
                                        <a href="{{ route('password.request') }}" class="hover-a">Forgot Password?</a>
                                    </h4>
                                @endif
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Sign In</button>
                        </div>
                        <input type="hidden" name="fcm_token" id="fcm_token">
                    </form>
                </div>
            </div>
            <div class="login-img">
                <img src="{{ asset('assets/img/login.jpg') }}" alt="Login Image">
            </div>
            <script type="module">
                import { initializeApp } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-app.js";
                import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-messaging.js";
                // Firebase configuration
                const firebaseConfig = {
                    apiKey: "AIzaSyDfxFLhCmscfY1piRHL2bs_9qzXMRqZfqM",
                    authDomain: "inventara-backend-notification.firebaseapp.com",
                    projectId: "inventara-backend-notification",
                    storageBucket: "inventara-backend-notification.firebasestorage.app",
                    messagingSenderId: "281401742100",
                    appId: "1:281401742100:web:8ff64dc5d4c53f66fea801",
                    measurementId: "G-3EJ0Z9XY0K"
                };

                // Initialize Firebase
                try {
                    const app = initializeApp(firebaseConfig);
                } catch (err) {
                    console.log('An error occurred while initializing Firebase app. ', err);
                    document.getElementById('fcm_token').value = "{{ config('app.default_fcm_token') }}";
                    return;
                }

                const messaging = getMessaging(app);

                // Get VAPID key from .env file
                const vapidKey = "{{ config('app.vapid_key') }}";

                // Request permission and get token
                Notification.requestPermission().then((permission) => {
                    if (permission === 'granted') {
                        getToken(messaging, { vapidKey: vapidKey }).then((currentToken) => {
                            if (currentToken) {
                                // Set the FCM token to the hidden input field
                                document.getElementById('fcm_token').value = currentToken;
                            } else {
                                console.log('No registration token available. Request permission to generate one.');
                            }
                        }).catch((err) => {
                            console.log('An error occurred while retrieving token. ', err);
                            alert('Error saat mengambil data, tolong refresh halaman ini!.');
                            window.location.reload();
                        });
                    } else if (permission === 'denied') {
                        console.log('Notification permission denied.');
                    } else {
                        console.log('Notification permission not granted.');
                    }
                }).catch((err) => {
                    console.log('An error occurred while requesting notification permission. ', err);
                });
            </script>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

<!-- Feather Icon -->
<script src="{{ asset('assets/js/feather.min.js') }}"></script>

<!-- Bootstrap Bundle -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/script.js') }}"></script>

</body>
</html>
