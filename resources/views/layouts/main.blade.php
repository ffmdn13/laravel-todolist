<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todolist | @yield('main-title')</title>

    {{-- bootstrap css link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- google font link --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">

    {{-- feather js link --}}
    <script src="https://unpkg.com/feather-icons"></script>

    {{-- css file link --}}
    <link rel="stylesheet" href="/css/main.css">

    @yield('additional-main-head')
</head>

<body>

    @yield('container')

    {{-- bootstrap js link --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>


    {{-- Flash a session message named flashMessage start --}}

    @if (session()->has('flashMessage'))
        <div class="position-fixed top-0 end-0 p-3">
            <div class="alert alert-info alert-dismissible fade show m-0 z-3" role="alert">
                <span class="d-flex gap-1">
                    <i data-feather="info" class="aspect-ratio icon-w-19"></i>{{ session()->get('flashMessage') }}
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- Flash a session message named flashMessage end --}}


    {{-- feather icon link --}}
    <script>
        feather.replace();
    </script>
</body>

</html>
