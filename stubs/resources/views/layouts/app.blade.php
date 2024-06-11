<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ config('app.name') }} | {{ $title }}</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="/storage/plugins/fontawesome-free/css/all.min.css" />
        <!-- Theme style -->
        <link rel="stylesheet" href="/storage/dist/css/adminlte.min.css" />
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <!-- Navbar -->
            <x-app.main-header />
            <!-- Main Sidebar -->
            <x-app.main-sidebar />
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                {{ $slot }}
            </div>
            <!-- Control Sidebar -->
            {{-- <x-app.main-control-sidebar /> --}}
            <!-- Main Footer -->
            <x-app.main-footer />
        </div>
        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="/storage/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="/storage/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="/storage/dist/js/adminlte.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.getElementById('btn-logout').onclick = (event) => {
                    event.preventDefault();
                    document.getElementById('form-logout').submit();
                };
            });
        </script>
    </body>
</html>
