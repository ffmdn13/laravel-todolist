@extends('layouts.dashboard')

@section('dsahboard-head')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/notebooks.css">

    {{-- trix editor cdn link --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endsection

@section('dashboard-content')
    <div class="grid-for-task-note-layout">
        {{-- notebooks items section start --}}
        <section class="p-4 border-end min-vh-100">
            <header class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <h1 class="notebooks-title">🌐 Web Roadmap</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <a href="" class="dropdown-plus-trigger" data-bs-toggle="modal" data-bs-target="#createNote">
                            <i data-feather="plus-square" class="icon-aspect-ratio dropdown-plus-icon"></i>
                        </a>

                        <div class="modal fade" id="createNote" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h1 class="add-new-notebooks-heading mb-3">📝 Add new note</h1>

                                        {{-- Add this to sidebar new task button --}}
                                        <form action="" method="POST">
                                            <input type="text" name="title"
                                                class="input-outline-off form-control mb-2 border-0 border-bottom"placeholder="Note title"
                                                aria-label="Title">
                                            @csrf
                                            <button class="add-new-notebooks-btn mt-2" type="submit">Add</button>
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
                            <li class="dropdown-item">
                                <a href="" class="no-text-decoration dropdown-sliders-menu-text">Due date</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <div class="border-bottom pb-2">
                <span class="text-black-50 d-block mt-2">1 Note</span>
            </div>
            @if (isset($notes))
                <ul class="notebooks-items mt-4">
                    <li class="border rounded py-1 px-3 mb-2" onclick="window.location.href=''">
                        <div class="d-flex align-items-center justify-content-between">
                            <h1 class="notebooks-items-title my-1">Drink coffee every morning</h1>
                            {{-- <div class="mt-2 d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center gap-1">
                                    <i data-feather="clock" class="notebooks-items-due-date-icon icon-aspect-ratio"></i>
                                    <span class="notebooks-items-due-date">Today, 5:45 PM</span>
                                </div>
                            </div> --}}
                        </div>
                    </li>
                </ul>
            @else
                <div class="empty-notebooks-height mt-4 d-flex flex-column justify-content-center align-items-center">
                    <i data-feather="file" class="empty-notebooks-icon mb-4"></i>
                    <h6 class="empty-notebooks-title">Your dashboard is currently empty.</h6>
                    <span class="empty-notebooks-desc mt-1">
                        Start by adding <a href="" class="empty-notebooks-link" data-bs-toggle="modal"
                            data-bs-target="#createNote">A New Note</a> to stay
                        organized and on top of your goals!
                    </span>
                </div>
            @endif
        </section>
        {{-- notebooks items section end --}}

        {{-- notebooks items preview start --}}
        <section class="p-4">
            <form action="" method="POST">
                @csrf
                <div class="d-flex align-items-center justify-content-between">
                    <input type="text" name="title" class="notebook-preview-title mb-2"
                        value="Note to workout every friday morning">
                </div>
                <input type="hidden" id="x" placeholder="Description" name="description">
                <div class="d-flex flex-column-reverse">
                    <div class="d-flex align-items-center justify-content-between">
                        <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                        <div>
                            <a href=""
                                class="notebook-preview-save-btn text-decoration-none d-flex align-items-center gap-1"
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
            </form>
        </section>
        {{-- notebooks items preview end --}}
    </div>
@endsection
