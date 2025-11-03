<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'ProjectFlow' }}</title>

        <!-- Styles -->
        @vite('resources/css/app.css')
        <!-- Font Awesome --> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        @vite('resources/js/app.js')
        @include('partials.scripts')
    </head>
    <body class="flex flex-col min-h-screen">
        <main class="flex-grow">
            @include('partials.header')
            <section id="content">
                @yield('content')
            </section>
        </main>

        <!-- Footer Section -->
        @include('partials.footer')
    </body>
</html>
