@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/list.css">
    <link rel="stylesheet" href="/css/dashboard/preview.css">

    {{-- trix editor cdn link --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endsection

{{-- @dd($preview) --}}

@section('dashboard-content')
    <div class="grid-for-task-note-layout">
        {{-- list items section start --}}
        <section class="p-4 border-end min-vh-100">
            <header class="d-flex align-items-center justify-content-between">
                <h1 class="list-title">{{ $listTitle }}</h1>
                <div class="d-flex align-items-center gap-2">
                    <form action="/dashboard/lists/delete" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $listId }}">
                        <button class="border-0 bg-transparent p-0" type="submit">
                            <i class="icon-aspect-ratio delete-list-icon pointer-trash text-danger"
                                data-feather="trash"></i>
                        </button>
                    </form>
                    <div>
                        <a href="" class="dropdown-plus-trigger" data-bs-toggle="modal" data-bs-target="#createTask">
                            <i data-feather="plus-square" class="icon-aspect-ratio dropdown-plus-icon"></i>
                        </a>

                        <div class="modal fade" id="createTask" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h1 class="add-new-list-heading mb-3">📜 Add new task</h1>

                                        {{-- Add this to sidebar new task button --}}
                                        <form action="" method="POST">
                                            <input type="text" name="title"
                                                class="input-outline-off form-control mb-2 border-0 border-bottom"placeholder="Title"
                                                aria-label="Title">
                                            <select class="input-outline-off border-0 border-bottom form-select mb-2"
                                                aria-label="Default select example">
                                                <option value="0" selected>⚪ None</option>
                                                <option value="1">🟢 Low</option>
                                                <option value="2">🔵 Medium</option>
                                                <option value="3">🔴 High</option>
                                            </select>
                                            @csrf
                                            <button class="add-new-list-btn mt-2" type="submit">Create</button>
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
                            <li class="dropdown-item"><a href=""
                                    class="no-text-decoration dropdown-sliders-menu-text">Priority</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            <div class="border-bottom pb-2">
                <span class="text-black-50 d-block mt-2">
                    @php($count = $tasks->count())
                    {{ $count > 1 ? "$count Tasks" : "$count Task" }}
                </span>
            </div>

            @if ($tasks->isNotEmpty())
                <ul class="list-items mt-4">
                    @foreach ($tasks as $task)
                        <li class="border rounded py-2 px-3 mb-2"
                            onclick="window.location.href='/dashboard/lists/{{ $listId }}/{{ $listTitle }}?preview={{ $task->id }}'">
                            <div class="d-flex align-items-center justify-content-between">
                                <h1 class="list-items-title my-1">{{ $task->title }}</h1>
                                <div class="d-flex align-items-center gap-1">
                                    @if (isset($task->due_date))
                                        <i data-feather="calendar" class="task-items-due-date-icon icon-aspect-ratio"></i>
                                    @endif
                                    @if (isset($task->reminder))
                                        <i data-feather="bell" class="task-items-due-date-icon icon-aspect-ratio"></i>
                                    @endif
                                    <i data-feather="flag"
                                        class="icon-aspect-ratio priority-icon color-{{ $task->priority }}"></i>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="empty-list-height mt-4 d-flex flex-column justify-content-center align-items-center">
                    <i data-feather="file" class="empty-list-icon mb-4"></i>
                    <h6 class="empty-list-title">Your dashboard is currently empty.</h6>
                    <span class="empty-list-desc mt-1">
                        Start by adding <a href="" class="empty-list-link" data-bs-toggle="modal"
                            data-bs-target="#createTask">A New Task</a> to stay
                        organized and on top of your goals!
                    </span>
                </div>
            @endif

        </section>
        {{-- list items section end --}}

        {{-- list items preview start --}}
        <section>
            @if (isset($preview))
                <form action="/dashboard/list/something" method="POST" class="p-4 h-100">
                    @csrf
                    <input type="hidden" name="id" value="{{ $preview['preview']->id }}">

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <label class="preview-due-date d-flex align-items-center gap-1" for="date"
                                data-bs-toggle="modal" data-bs-target="#dueDateModal">
                                @if ($preview['preview']->due_date)
                                    <i data-feather="calendar" class="preview-due-date-icon aspect-ratio"></i>
                                    {{ $preview['preview']->due_date }}
                                @else
                                    <span class="empty-due-date d-block rounded">Set due date</span>
                                @endif
                            </label>

                            <div class="modal fade" id="dueDateModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="row g-2">
                                                <div class="col">
                                                    <label for="date" class="form-label">Date</label>
                                                    <input type="date" name="date" id="date"
                                                        class="form-control" aria-label="Date"
                                                        value="{{ $preview['inputDateValue'] }}">
                                                </div>
                                                <div class="col">
                                                    <label for="time" class="form-label">Time</label>
                                                    <input type="time" name="time" class="form-control"
                                                        aria-label="Time" value="{{ $preview['inputTimeValue'] }}">
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
                        <i data-feather="flag"
                            class="aspect-ratio priority-icon color-{{ $preview['preview']->priority }}"></i>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <input type="text" name="title" class="preview-title mb-2 border-0 bg-transparent w-100 p-0"
                            value="{{ $preview['preview']->title }}">
                        <input class="preview-complete-btn aspect-ratio" type="checkbox" name="is_complete"
                            value="1" @if ($preview['preview']->is_complete == 1) checked @endif>
                    </div>

                    <div>
                        <input type="hidden" id="x" value="{{ $preview['preview']->description }}"
                            placeholder="Description" name="description">
                        <div class="d-flex flex-column-reverse">
                            <div class="d-flex align-items-center justify-content-between">
                                <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                                <div>
                                    <a href=""
                                        class="preview-save-btn text-decoration-none border-0 d-flex align-items-center gap-1"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="chevron-up" class="aspect-ratio action-icon order-1"></i>
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
                                            <button class="border-0 bg-transparent" name="action" value="shortcut">
                                                {{ $preview['preview']->is_shortcut == 0 ? 'Add to shortcut' : 'Remove from shortcut' }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <trix-editor toolbar="trix-toolbar-1" input="x"
                                class="custom-trix p-0 border-0 overflow-auto" placeholder="Description"></trix-editor>
                        </div>
                    </div>
                </form>
            @else
                <div class="empty-preview p-4 d-flex flex-column align-items-center justify-content-center min-vh-100">
                    <div class="mb-1">
                        <i data-feather="book-open" class="empty-preview-icon aspect-ratio mx-auto mb-2 d-block"></i>
                        <h6 class="empty-preview-title">There's no task to view here</h6>
                    </div>
                    <span class="empty-preview-desc">
                        Click one to view here.
                    </span>
                </div>
            @endif
        </section>
        {{-- list items preview end --}}

        @if (session()->has('message'))
            <div class="position-fixed top-0 end-0 p-3">
                <div class="alert alert-info alert-dismissible fade show m-0 z-3" role="alert">
                    <span class="d-flex gap-1">
                        <i data-feather="info" class="aspect-ratio info-icon"></i>{{ session()->get('message') }}
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>
@endsection
