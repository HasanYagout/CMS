<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>{{ getOption('app_name') }} - @stack('title' ?? '')</title>

    @hasSection('meta')
        @stack('meta')
    @else

        <meta property="og:type" content="{{ __('Alumni') }}">

        <meta property="og:url" content="{{ url()->current() }}">

        <meta property="og:site_name" content="{{ __(getOption('app_name')) }}">

        <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@100;200;300;400;500;600;700;800;900&family=Nunito:wght@200;300;400;500;600;700;800;900;1000&display=swap"
        rel="stylesheet"/>
    <!-- css file  -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/scss/style.css') }}"/>
    @stack('style')

    @if(getOption('google_analytics_status', 0))

        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{ getOption('google_analytics_tracking_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', "{{ getOption('google_analytics_tracking_id') }}");
        </script>
    @endif

</head>
