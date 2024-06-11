<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ config('app.name') }}</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="/storage/plugins/fontawesome-free/css/all.min.css" />
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="/storage/plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
        <!-- Theme style -->
        <link rel="stylesheet" href="/storage/dist/css/adminlte.min.css" />
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            {{ $slot }}
        </div>
        <!-- jQuery -->
        <script src="/storage/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="/storage/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="/storage/dist/js/adminlte.min.js"></script>
    </body>
</html>
