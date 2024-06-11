@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/today.css">

    {{-- trix editor cdn link --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endsection

@section('dashboard-content')
    <div class="grid-for-task-note-layout">
        {{-- Today items list start --}}
        <section class="p-4 border-end min-vh-100">
            <header class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <i data-feather="sun" class="icon-aspect-ratio today-container-icon"></i>
                    <h1 class="today-container-title">Today</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <a href="" class="dropdown-plus-trigger" data-bs-toggle="modal" data-bs-target="#createTask">
                            <i data-feather="plus-square" class="icon-aspect-ratio dropdown-plus-icon"></i>
                        </a>

                        <div class="modal fade" id="createTask" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h1 class="add-new-today-heading mb-3">📜 Add new task</h1>

                                        {{-- Add this to sidebar new task button --}}
                                        <form action="" method="POST">
                                            <input type="text" name="title"
                                                class="input-outline-off form-control mb-2 border-0 border-bottom"placeholder="Title"
                                                aria-label="Title">
                                            <select class="input-outline-off border-0 border-bottom form-select mb-2"
                                                aria-label="Default select example">
                                                <option selected>Priority</option>
                                                <option value="0">⚪ None</option>
                                                <option value="1">🟢 Low</option>
                                                <option value="2">🔵 Medium</option>
                                                <option value="3">🔴 High</option>
                                            </select>
                                            @csrf
                                            <button class="add-new-today-btn mt-2" type="submit">Add</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="" class="no-text-decoration dropdown-sliders-trigger" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i data-feather="sliders" class="icon-aspect-ratio dropdown-sliders-icon"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-sliders-menu-title px-3">Sort by</li>
                            <li class="dropdown-item"><a href=""
                                    class="no-text-decoration dropdown-sliders-menu-text">Title</a></li>
                            <li class="dropdown-item"><a href=""
                                    class="no-text-decoration dropdown-sliders-menu-text">Due date</a></li>
                            <li class="dropdown-item"><a href=""
                                    class="no-text-decoration dropdown-sliders-menu-text">Priority</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            <div class="border-bottom pb-2">
                <span class="text-black-50 d-block mt-2">1 Task</span>
            </div>
            @if (isset($tasks))
                <ul class="today-items mt-4">
                    <li class="border rounded py-2 px-3 mb-2" onclick="window.location.href=''">
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="today-items-today-address">🎯 List > Drink coffee every morning</small>
                            <span class="priority color-blue d-block rounded-circle ms-auto"></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <h1 class="today-items-title my-1">Drink coffee every morning</h1>
                            <div class="mt-2 d-flex align-items-center gap-1">
                                <i data-feather="clock" class="today-items-due-date-icon icon-aspect-ratio"></i>
                                <span class="today-items-due-date">Today, 5:45 PM</span>
                            </div>
                        </div>
                    </li>
                </ul>
            @else
                <div class="empty-today-height mt-4 d-flex flex-column justify-content-center align-items-center">
                    <i data-feather="file" class="empty-today-icon mb-4"></i>
                    <h6 class="empty-today-title">Your dashboard is currently empty.</h6>
                    <span class="empty-task-desc mt-1">
                        Start by adding <a href="" class="empty-today-link" data-bs-toggle="modal"
                            data-bs-target="#createTask">A New Task</a> to stay
                        organized and on top of your goals!
                    </span>
                </div>
            @endif
        </section>
        {{-- Today items list end --}}

        {{-- Today preview start --}}
        <section>
            <form action="" method="POST" class="p-4">
                @csrf
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <label class="today-preview-due-date d-flex align-items-center gap-1" for="date"
                            data-bs-toggle="modal" data-bs-target="#dueDateModal">
                            <i data-feather="clock" class="today-preview-due-date-icon icon-aspect-ratio"></i>
                            Today, 5:45 PM
                        </label>
                        <div class="modal fade" id="dueDateModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="row g-2">
                                            <div class="col">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" name="date" id="date" class="form-control"
                                                    aria-label="Date">
                                            </div>
                                            <div class="col">
                                                <label for="time" class="form-label">Time</label>
                                                <input type="time" name="time" class="form-control"
                                                    aria-label="Time">
                                            </div>
                                            <div class="col">
                                                <label for="reminder" class="form-label">Reminder</label>
                                                <input type="time" name="reminder" class="form-control"
                                                    aria-label="Reminder">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="priority color-red d-block rounded-circle"></span>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <input type="text" name="title" class="today-preview-title mb-2"
                        value="Drink coffee every morning">
                    <input class="today-preview-compleate-btn icon-aspect-ratio" type="checkbox" name="compleate"
                        value="1">
                </div>
                <div>
                    <input type="hidden" id="x" placeholder="Description" name="description">
                    <div class="d-flex flex-column-reverse">
                        <div class="d-flex align-items-center justify-content-between">
                            <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                            <div>
                                <a href=""
                                    class="today-preview-save-btn text-decoration-none d-flex align-items-center gap-1"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-feather="chevron-up" class="icon-aspect-ratio action-icon order-1"></i>
                                    Action
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" value="save">Save</button>
                                    </li>
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" value="delete">Delete</button>
                                    </li>
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" value="shortcut">Add to shortcut</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <trix-editor toolbar="trix-toolbar-1" input="x" class="custom-trix"
                            placeholder="Description"></trix-editor>
                    </div>
                </div>
            </form>
        </section>
        {{-- Today preview end --}}
    </div>
@endsection
