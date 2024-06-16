@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/note.css">

    {{-- trix editor cdn link --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endsection

@section('dashboard-content')
    <div class="grid-for-task-note-layout">
        {{-- Note items list start --}}
        <section class="border-end p-4 min-vh-100">
            <header class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <i data-feather="file-text" class="icon-aspect-ratio note-container-icon"></i>
                    <h1 class="note-container-title">Note</h1>
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
                                        <h1 class="add-new-note-heading mb-3">📝 Add new note</h1>

                                        <form action="/dashboard/note/add" method="POST">
                                            <input type="text" name="title"
                                                class="input-outline-off form-control mb-2 border-0 border-bottom"
                                                placeholder="Title" aria-label="Title" value="Untitled">
                                            @csrf
                                            <button class="add-new-task-btn mt-2" type="submit">Add</button>
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
                                    class="no-text-decoration dropdown-sliders-menu-text">Date created</a></li>
                        </ul>
                    </div>

                </div>
            </header>

            <div class="border-bottom pb-2">
                <span class="text-black-50 d-block mt-2">
                    @php($count = $notes->count())
                    {{ $count > 1 ? "$count Notes" : "$count Note" }}
                </span>
            </div>
            @if ($notes->has(0))
                <ul class="note-items mt-4">
                    @foreach ($notes as $note)
                        <li class="border rounded py-2 px-3 mb-2 {{ request()->getRequestUri() === '/dashboard/note/' . $note->id ? 'border-focus' : '' }}"
                            onclick="window.location.href='/dashboard/note/{{ $note->id }}'">
                            <div class="d-flex align-items-center justify-content-between">
                                <h1 class="note-items-title my-1">{{ $note->title }}</h1>
                                <div class="d-flex align-items-center gap-1">
                                    <i data-feather="clock" class="note-items-due-date-icon icon-aspect-ratio"></i>
                                    <span class="note-items-due-date">{{ getLastEdited($note->due_date) }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="empty-note-height mt-4 d-flex flex-column justify-content-center align-items-center">
                    <i data-feather="file-text" class="empty-note-icon mb-4"></i>
                    <h6 class="empty-note-title">It looks like your to-do list is empty!</h6>
                    <span class="empty-note-desc mt-1">
                        <a href="" class="empty-note-link" data-bs-toggle="modal" data-bs-target="#createNote">Add
                            your note</a> to stay organized and on track. 🚀
                    </span>
                </div>
            @endif
        </section>
        {{-- Note items list end --}}

        {{-- Note preview start --}}
        <section class="p-4">
            @if (isset($preview))
                <form action="/dashboard/note/action" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $preview->id }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <input type="text" name="title" class="note-preview-title mb-2" value="{{ $preview->title }}">
                    </div>
                    <input type="hidden" id="x" placeholder="Description" name="description"
                        value="{{ $preview->description }}">
                    <div class="d-flex flex-column-reverse">
                        <div class="d-flex align-items-center justify-content-between">
                            <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                            <div>
                                <a href=""
                                    class="note-preview-save-btn text-decoration-none d-flex align-items-center gap-1"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-feather="chevron-up" class="icon-aspect-ratio action-icon order-1"></i>
                                    Action
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action"
                                            value="save">Save</button>
                                    </li>
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action"
                                            value="delete">Delete</button>
                                    </li>
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action" value="shortcut">
                                            {{ $preview->is_shortcut == 0 ? 'Add to shortcut' : 'Remove from shortcut' }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <trix-editor toolbar="trix-toolbar-1" input="x" class="custom-trix"
                            placeholder="Description"></trix-editor>
                    </div>
                </form>
            @else
                <div style="opacity: 0.8"
                    class="p-4 d-flex flex-column align-items-center justify-content-center min-vh-100">
                    <div class="mb-1">
                        <i data-feather="book-open" class="empty-preview-icon icon-aspect-ratio mx-auto mb-2 d-block"></i>
                        <h6 class="empty-preview-title">There's no task to view here</h6>
                    </div>
                    <span class="empty-preview-desc">
                        Click one to view here.
                    </span>
                </div>
            @endif
        </section>
        {{-- Note preview end --}}

        {{-- A flash message that will appear if something happend --}}
        @if (session()->has('message'))
            <div class="position-fixed top-0 end-0 p-3">
                <div class="alert alert-info alert-dismissible fade show m-0 z-3" role="alert">
                    <span class="d-flex gap-1">
                        <i data-feather="info" class="icon-aspect-ratio info-icon"></i>{{ session()->get('message') }}
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

    </div>
@endsection
