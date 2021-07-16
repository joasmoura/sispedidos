<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="BASE" content="{{env('APP_URL')}}">

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="../assets/img/favicon.png">

        {!! $seo ?? '' !!}

        <link href="{{asset('manifest.json')}}" rel="manifest" />
        <link href="{{asset('assets/plugins/js/font-awesome/css/all.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/css/bootstrap.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/css/site.css')}}" rel="stylesheet" />
    </head>

    <body>
        <div class="bg-negocio"></div>

        @yield('header')

        @yield('conteudo')

        <script src="{{asset('assets/plugins/js/jquery.min.js')}}" defer></script>
          <script src="{{asset('assets/plugins/js/popper.min.js')}}" defer></script>
          <script src="{{asset('assets/plugins/js/bootstrap.min.js')}}" defer></script>
          <script src="{{asset('assets/plugins/js/sweetalert.js')}}" defer></script>
          <script src="{{asset('assets/plugins/js/perfect-scrollbar.jquery.min.js')}}" defer></script>
          <script src="{{asset('assets/js/site.js')}}" defer></script>

          @yield('styles')
    </body>
</html>
