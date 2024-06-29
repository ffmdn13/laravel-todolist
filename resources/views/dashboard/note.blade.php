@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    {{-- <link rel="stylesheet" href="/css/dashboard/note.css"> --}}
    <link rel="stylesheet" href="/css/dashboard/view.css">

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
                    <i data-feather="file-text" class="aspect-ratio icon-w-21"></i>
                    <h1 class="overview-title">Note</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <a href="" class="overview-dropdown-clr-black" data-bs-toggle="modal"
                            data-bs-target="#createNote">
                            <i data-feather="plus-square" class="aspect-ratio icon-w-19"></i>
                        </a>

                        <div class="modal fade" id="createNote" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h1 class="overview-add-task-title border-0 mb-3">📝 Add new note</h1>

                                        <form action="/dashboard/note/add" method="POST">
                                            <input type="text" name="title"
                                                class="input-outline-off form-control mb-2 border-0 border-bottom"
                                                placeholder="Title" aria-label="Title" value="Untitled">
                                            @csrf
                                            <button class="overview-add-task-btn border-0 mt-2" type="submit">Add</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <a href="" class="overview-dropdown-clr-black text-decoration-none" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i data-feather="sliders" class="aspect-ratio icon-w-19"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="overview-dropdown-sliders px-3">Sort by</li>
                            <li class="dropdown-item"><a href="{{ $url . 'order=title' }}"
                                    class="text-decoration-none overview-dropdown-clr-black">Title</a></li>
                            <li class="dropdown-item"><a href="{{ $url . 'order=due_date&direction=desc' }}"
                                    class="text-decoration-none overview-dropdown-clr-black">Date created</a></li>
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

            @if ($notes->isNotEmpty())
                <ul class="overview-items m-0 p-0 mt-4">
                    @foreach ($notes as $note)
                        <li class="border rounded py-2 px-3 mb-2 cursor-pointer"
                            onclick="window.location.href='/dashboard/note/{{ $note->id }}/{{ $note->title }}'">
                            <div class="d-flex align-items-center justify-content-between">
                                <h1 class="overview-item-title my-1 max-width-470">{{ $note->title }}</h1>
                                <div class="d-flex align-items-center gap-1">
                                    <i data-feather="clock" class="aspect-ratio icon-w-15"></i>
                                    <span class="overview-due-date">{{ getLastEdited($note->due_date) }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="overview-empty mt-4 d-flex flex-column justify-content-center align-items-center">
                    <i data-feather="file-text" class="overview-empty-icon mb-4"></i>
                    <h6 class="overview-title">It looks like your to-do list is empty!</h6>
                    <span class="overview-empty-description mt-1">
                        <a href="" class="overview-empty-link" data-bs-toggle="modal"
                            data-bs-target="#createNote">Add
                            your note</a> to stay organized and on track. 🚀
                    </span>
                </div>
            @endif
        </section>
        {{-- Note items list end --}}

        {{-- Note preview start --}}
        <section class="p-4">
            @if (isset($view))
                <form action="/dashboard/note/action" method="POST" class="d-flex flex-column gap-1 h-100">
                    @csrf
                    <input type="hidden" name="id" value="{{ $view->id }}">

                    <div class="d-flex align-items-center justify-content-between">
                        <input type="text" name="title" class="preview-title border-0 bg-transparent w-100 p-0"
                            value="{{ $view->title }}">
                    </div>

                    <div class="flex-fill">
                        <input type="hidden" id="x" placeholder="Description" name="description"
                            value="{{ $view->description }}">

                        <div class="d-flex flex-column-reverse h-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                                <div>
                                    <a href=""
                                        class="preview-save-btn text-decoration-none border-0 d-flex align-items-center gap-1"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="chevron-up" class="aspect-ratio icon-w-19 order-1"></i>
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
                                                {{ $view->is_shortcut == 0 ? 'Add to shortcut' : 'Remove from shortcut' }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <trix-editor toolbar="trix-toolbar-1" input="x"
                                class="custom-trix border-0 p-0 overflow-auto h-100"
                                placeholder="Description"></trix-editor>
                        </div>
                </form>
            @else
                <div class="empty-preview p-4 d-flex flex-column align-items-center justify-content-center h-100">
                    <div class="mb-1">
                        <i data-feather="book-open" class="empty-preview-icon aspect-ratio mx-auto mb-2 d-block"></i>
                        <h6 class="empty-preview-title">There's no note to view here</h6>
                    </div>
                    <span class="empty-preview-desc">
                        Click one to view here.
                    </span>
                </div>
            @endif
        </section>
        {{-- Note preview end --}}

    </div>
@endsection
