@if(app()->getLocale() === 'ar')
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.rtl.min.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
@endif


@if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body{
                font-family:'Cairo',sans-serif;
            }
        </style>
    @endif
