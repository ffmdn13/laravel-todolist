<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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

    <title>Todolist - @yield('dashboard-title')</title>

    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/light/dashboard/sidebar.css">
    @if (json_decode(auth()->user()->personalization)->apperance->theme === 'dark')
        <link rel="stylesheet" href="/css/dark/dashboard/sidebar.css">
    @endif

    @yield('additional-dashboard-head')
</head>

<body>

    <main class="grid-layout min-vh-100">
        @include('partials.dashboard-sidebar')

        <section class="dashboard-layout bg-light">
            @yield('dashboard-content')
        </section>

        @if (session()->has('message'))
            <div class="position-fixed top-0 end-0 p-3">
                <div class="alert alert-info alert-dismissible fade show m-0 z-3" role="alert">
                    <span class="d-flex gap-1">
                        <i data-feather="info" class="aspect-ratio"
                            style="width: 19px;"></i>{{ session()->get('message') }}
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </main>

    {{-- bootstrap js link --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    {{-- feather icon link --}}
    <script>
        feather.replace();
    </script>

</body>

</html>
