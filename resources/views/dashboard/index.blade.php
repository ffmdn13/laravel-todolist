@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/index.css">
@endsection

@section('dashboard-content')
    {{-- top dashboard section start --}}
    <section class="top-dashboard-section">
        <img src="{{ $heroImage }}" alt="" class="hero-image">
        <header class="profile p-4 d-flex align-items-center justify-content-between">
            <div class="welcome-text">
                <h4>{{ $parseTimeToGreeting }}</h4>
                <span>{{ $welcomeText }}</span>
            </div>
            <div class="profile-setting d-flex align-items-center gap-3">
                <div class="notification">
                    <a href="" data-bs-toggle="modal" data-bs-target="#notification">
                        <i data-feather="bell" class="notification-icon"></i>
                    </a>
                    <div class="modal fade" id="notification" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div
                                    class="custom-height modal-body p-4 @if (!isset($notifications)) text-center d-flex align-items-center justify-content-center @endif">
                                    @if (!isset($notifications))
                                        There's no notification here <br>
                                        You can receive from task reminder and system
                                    @else
                                        <a href="" class="notification-link border-bottom d-block  pb-1 mb-3">
                                            <p style="font-size: .95rem;" class="mb-2">Lorem ipsum dolor sit amet
                                                consectetur adipisicing elit. Nulla?</p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="notification-date d-flex align-items-center gap-1">
                                                    <i data-feather="clock" class="notification-clock-icon"></i>
                                                    {{ now() }}
                                                </div>
                                                <span class="not-read"></span>
                                            </div>
                                        </a>
                                        <a href="" class="notification-link border-bottom d-block  pb-1 mb-3">
                                            <p style="font-size: .95rem;" class="mb-2">Lorem ipsum dolor sit amet
                                                consectetur adipisicing elit. Nulla?</p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="notification-date d-flex align-items-center gap-1">
                                                    <i data-feather="clock" class="notification-clock-icon"></i>
                                                    {{ now() }}
                                                </div>
                                                <span class="not-read"></span>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
                    alt="{{ $nickname ?? 'user' }}'s profile" class="dropdown-toggle" data-bs-toggle="dropdown">
                <div class="dropdown-menu bg-light-subtle">
                    <a href="/dashboard/profile" class="dropdown-item d-flex align-items-center gap-1">
                        <i data-feather="user" class="profile-dropdown-icon"></i>
                        Profile
                    </a>
                    <a href="/dashboard/statistic" class="dropdown-item d-flex align-items-center gap-1">
                        <i data-feather="bar-chart-2" class="profile-dropdown-icon"></i>
                        Statistic
                    </a>
                    <a href="/dashboard/settings" class="dropdown-item d-flex align-items-center gap-1">
                        <i data-feather="settings" class="profile-dropdown-icon"></i>
                        Settings
                    </a>
                    <a href="/logout" class="dropdown-item d-flex align-items-center gap-1">
                        <i data-feather="log-out" class="profile-dropdown-icon"></i>
                        Sign out
                    </a>
                </div>
            </div>
        </header>
    </section>
    {{-- top dashboard section end --}}

    {{-- today task and note section start --}}
    <section class="task-grid-layout">
        <section class="today-task p-4">
            <div class="today-task-title d-flex align-items-center justify-content-between">
                <h5 class="title">📅 Today Task</h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="" class="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i data-feather="list"class="today-task-icon"></i>
                    </a>
                    <form action="" method="POST" class="dropdown-menu">
                        <button class="dropdown-item" value="5">5</button>
                        <button class="dropdown-item" value="10">10</button>
                        <button class="dropdown-item" value="50">50</button>
                        <button class="dropdown-item" value="100">100</button>
                    </form>
                    <a href="#">
                        <i data-feather="plus"class="today-task-icon"></i>
                    </a>
                </div>
            </div>

            <ul class="today-task-items mt-2">
                <li class="today-task-item p-3 shadow rounded mb-2">
                    <small class="task-address">🎯 List > Drink coffee every morning</small>
                    <div class="content mt-2">
                        <div class="d-flex align-items-center">
                            <h1 class="title my-1">Drink coffee every morning</h1>
                            <span class="priority {{ $priorityColor }} d-block rounded-circle ms-auto"></span>
                        </div>
                    </div>
                    <div class="mt-2 d-flex align-items-center justify-content-between">
                        <div class="due-date d-flex align-items-center gap-1">
                            <i data-feather="bell" class="due-date-icon"></i>
                            5:45 PM
                        </div>
                        <div>
                            <a href=""><i data-feather="edit-2" class="today-task-edit-icon mx-1"></i></a>
                            <a href=""><i data-feather="trash" class="today-task-delete-icon"></i></a>
                        </div>
                    </div>
                </li>
            </ul>
        </section>

        <section class="recent-note p-4">
            <div class="recent-note-title d-flex align-items-center justify-content-between">
                <h5 class="title">📝 Recent Note</h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="" class="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i data-feather="list"class="recent-note-icon"></i>
                    </a>
                    <form action="" method="POST" class="dropdown-menu">
                        <button class="dropdown-item" value="5">5</button>
                        <button class="dropdown-item" value="10">10</button>
                        <button class="dropdown-item" value="50">50</button>
                        <button class="dropdown-item" value="100">100</button>
                    </form>
                    <a href="#">
                        <i data-feather="plus"class="recent-note-icon"></i>
                    </a>
                </div>
            </div>

            <ul class="recent-note-items mt-2">
                <li class="recent-note-item py-1 px-3 shadow rounded mb-2">
                    <small class="recent-note-address">🎯 List > Drink coffee every morning</small>
                    <article class="content">
                        <div class="d-flex align-items-center">
                            <h1 class="title my-1">Drink coffee every morning</h1>
                            <span class="priority {{ $priorityColor }} d-block rounded-circle ms-auto"></span>
                        </div>
                        <p class="description mt-1">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe iste, odit officiis harum
                            facere neque soluta dignissimos doloribus molestias molestiae.
                        </p>
                    </article>
                    <div class="mt-2 d-flex align-items-center justify-content-between">
                        <div class="due-date d-flex align-items-center gap-1">
                            <i data-feather="bell" class="due-date-icon"></i>
                            5:45 PM
                        </div>
                        <div>
                            <a href=""><i data-feather="edit-2" class="recent-edit-icon"></i></a>
                            <a href=""><i data-feather="trash" class="recent-delete-icon"></i></a>
                        </div>
                    </div>
                </li>
            </ul>
        </section>
    </section>
    {{-- today task and note section end --}}
@endsection
