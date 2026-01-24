<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Telekonsultasi modern untuk layanan medis">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Telekonsultasi | {{ isset($title) ? $title : 'Dashboard' }}</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3774/3774299.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        purple: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#2e1065',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        input {
            font-family: 'Inter', sans-serif;
        }

        .smooth-transition {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="font-sans bg-gray-50">
    <div id="app">
        @yield('body')
        @yield('content')
    </div>

    <script>
        window.profilePhotoConfirmAndSubmit = function (input) {
            const file = input && input.files ? input.files[0] : null;
            if (!file) {
                return;
            }

            if (typeof Swal === 'undefined') {
                input.form.submit();
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                Swal.fire({
                    title: 'Upload foto profil?',
                    text: file.name,
                    imageUrl: e.target.result,
                    imageAlt: 'Preview Foto',
                    imageHeight: 200,
                    showCancelButton: true,
                    confirmButtonText: 'Upload',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        input.form.submit();
                    } else {
                        input.value = '';
                    }
                });
            };
            reader.readAsDataURL(file);
        };

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swal === 'undefined') {
                return;
            }

            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            @if (session('success'))
                toast.fire({ icon: 'success', title: @json(session('success')) });
            @endif

            @if (session('error'))
                toast.fire({ icon: 'error', title: @json(session('error')) });
            @endif
        });
    </script>

    @stack('scripts')
    <!-- Scripts -->
</body>

</html>