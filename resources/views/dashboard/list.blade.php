@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/light/dashboard/view.css">
    @if ($theme === 'dark')
        <link rel="stylesheet" href="/css/dark/dashboard/view.css">
    @endif

    {{-- trix editor cdn link --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endsection

{{-- @dd($preview) --}}

@section('dashboard-content')
    <div class="grid-for-task-note-layout">
        {{-- list items section start --}}
        <section class="p-4 border-end overflow-auto">

            <header class="d-flex align-items-center justify-content-between">
                <h1 class="overview-title">{{ $listTitle }}</h1>
                <div class="d-flex align-items-center gap-2">
                    <form action="/dashboard/list/delete" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $listId }}">
                        <button class="border-0 bg-transparent p-0" type="submit">
                            <i class="aspect-ratio icon-w-19 cursor-pointer text-danger" data-feather="trash"></i>
                        </button>
                    </form>
                    <div>
                        <a href="" class="overview-dropdown-clr-black" data-bs-toggle="modal"
                            data-bs-target="#createTask">
                            <i data-feather="plus-square" class="aspect-ratio icon-w-19"></i>
                        </a>

                        <div class="modal fade" id="createTask" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content overview-dropdown-dark-theme rounded-0">
                                    <div class="modal-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h1 class="overview-add-task-title mb-3 fs-5">📜 Add new task</h1>
                                            <i data-feather="x" class="aspect-ratio icon-w-17"></i>
                                        </div>

                                        <form action="/dashboard/list/add/task" method="POST">
                                            <input type="hidden" name="id" value="{{ $listId }}">
                                            <input type="text" name="title"
                                                class="input-outline-off form-control mb-2 px-0 bg-transparent border-0 border-bottom"
                                                placeholder="Title" aria-label="Title">
                                            <select
                                                class="input-outline-off border-0 border-bottom px-0 bg-transparent form-select mb-2"
                                                aria-label="Default select example" name="priority">
                                                <option value="0" selected>⚪ None</option>
                                                <option value="1">🟢 Low</option>
                                                <option value="2">🔵 Medium</option>
                                                <option value="3">🔴 High</option>
                                            </select>
                                            @csrf
                                            <button class="overview-add-task-btn border-0 mt-2" type="submit">Add</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="" class="text-decoration-none overview-dropdown-clr-black" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i data-feather="sliders" class="aspect-ratio icon-w-19"></i>
                        </a>
                        <ul class="dropdown-menu overview-dropdown-dark-theme">
                            <li class="overview-dropdown-sliders px-3">Sort by</li>
                            <li class="dropdown-item d-flex align-items-center justify-content-between">
                                <a href="{{ $url . 'order=title' }}"
                                    class="text-decoration-none overview-dropdown-clr-black">Title</a>
                                <i data-feather="underline" class="aspect-ratio icon-w-17"></i>
                            </li>
                            <li class="dropdown-item d-flex align-items-center justify-content-between">
                                <a href="{{ $url . 'order=due_date' }}"
                                    class="text-decoration-none overview-dropdown-clr-black">Due date</a>
                                <i data-feather="calendar" class="aspect-ratio icon-w-17"></i>
                            </li>
                            <li class="dropdown-item d-flex align-items-center justify-content-between">
                                <a href="{{ $url . 'order=priority' }}"
                                    class="text-decoration-none overview-dropdown-clr-black">Priority</a>
                                <i data-feather="flag" class="aspect-ratio icon-w-17"></i>
                            </li>
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
                <ul class="overview-items m-0 p-0 mt-4">
                    @foreach ($tasks->items() as $task)
                        <li class="border py-2 px-3 mb-2 cursor-pointer"
                            onclick="window.location.href='/dashboard/list/{{ $listId }}/{{ $listTitle }}?view={{ $task->id }}{{ $queryParams }}'">
                            <div class="d-flex align-items-center justify-content-between">
                                <h1 class="overview-item-title my-1 max-width-470">{{ $task->title }}</h1>
                                <div class="d-flex align-items-center gap-1">
                                    @if (isset($task->due_date))
                                        <i data-feather="calendar" class="icon-w-15 aspect-ratio"></i>
                                    @endif
                                    @if (isset($task->reminder))
                                        <i data-feather="bell" class="icon-w-15 aspect-ratio"></i>
                                    @endif
                                    <i data-feather="flag" class="aspect-ratio icon-w-15 color-{{ $task->priority }}"></i>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-3">{{ $tasks->links() }}</div>
            @else
                <div class="overview-empty mt-4 d-flex flex-column justify-content-center align-items-center">
                    <i data-feather="file" class="overview-empty-icon mb-4"></i>
                    <h6 class="overview-title">Your dashboard is currently empty.</h6>
                    <span class="overview-empty-description mt-1">
                        Start by adding <a href="" class="overview-empty-link" data-bs-toggle="modal"
                            data-bs-target="#createTask">A New Task</a> to stay
                        organized and on top of your goals!
                    </span>
                </div>
            @endif

        </section>
        {{-- list items section end --}}

        {{-- list items preview start --}}
        <section class="p-4 preview-dark-theme">
            @if (isset($view))
                <form action="/dashboard/list/action" method="POST" class="d-flex flex-column gap-1 h-100">
                    @csrf
                    <input type="hidden" name="id" value="{{ $view->id }}">
                    <input type="hidden" name="list_id" value="{{ $listId }}">
                    <input type="hidden" name="list_title" value="{{ $listTitle }}">

                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <label class="preview-due-date d-flex align-items-center gap-1" for="date"
                                data-bs-toggle="modal" data-bs-target="#dueDateModal">
                                @if ($view->due_date)
                                    <i data-feather="calendar" class="icon-w-15 aspect-ratio"></i>
                                    {{ formatDateOrTime('l, M j Y', $view->due_date) . formatDateOrTime($timeFormat, $view->time) }}
                                @else
                                    <span class="empty-due-date d-block rounded">Set due date</span>
                                @endif
                            </label>

                            <div class="modal fade" id="dueDateModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-0 preview-date-dark-theme">
                                        <div class="modal-body">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <h1 class="modal-title fs-5">📋 Task Schedule</h1>
                                                <i data-feather="x" class="aspect-ratio icon-w-21"
                                                    data-bs-dismiss="modal" data-bs-target="modal"
                                                    style="cursor: pointer;"></i>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col">
                                                    <label for="date" class="form-label">Date</label>
                                                    <input type="date" name="due_date" id="date"
                                                        class="form-control rounded-0 bg-transparent" aria-label="Date"
                                                        value="{{ formatDateOrTime('Y-m-d', $view->due_date) }}">
                                                </div>
                                                <div class="col">
                                                    <label for="time" class="form-label">Time</label>
                                                    <input type="time" name="time"
                                                        class="form-control rounde-0 bg-transparent" aria-label="Time"
                                                        value="{{ formatDateOrTime('h:i', $view->due_date) }}">
                                                </div>
                                                <div class="col">
                                                    <label for="reminder" class="form-label">Reminder</label>
                                                    <input type="time" name="reminder"
                                                        class="form-control rounded-0 bg-transparent"
                                                        aria-label="Reminder">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <i data-feather="flag" class="aspect-ratio icon-w-15 color-{{ $view->priority }}"></i>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <input type="text" name="title" class="preview-title mb-2 border-0 bg-transparent w-100 p-0"
                            value="{{ $view->title }}">
                        <input class="preview-complete-btn aspect-ratio" type="checkbox" name="is_complete"
                            value="1" @if ($view->is_complete == 1) checked @endif>
                    </div>

                    <div class="flex-fill">
                        <input type="hidden" id="x" value="{{ $view->description }}" placeholder="Description"
                            name="description">

                        <div class="d-flex flex-column-reverse h-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                                <div>
                                    <a href=""
                                        class="preview-save-btn text-decoration-none border-0 d-flex align-items-center gap-1"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="chevron-up" class="aspect-ratio icon-w-15 order-1"></i>
                                        Action
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-item d-flex align-items-center justify-content-between">
                                            <button class="border-0 bg-transparent" name="action"
                                                value="saveTask">Save</button>
                                            <i data-feather="save" class="aspect-ratio icon-w-17"></i>
                                        </li>
                                        <li class="dropdown-item d-flex align-items-center justify-content-between">
                                            <button class="border-0 bg-transparent" name="action"
                                                value="deleteTask">Delete</button>
                                            <i data-feather="trash" class="aspect-ratio icon-w-17"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <trix-editor toolbar="trix-toolbar-1" input="x"
                                class="custom-trix p-0 border-0 overflow-auto h-100"
                                placeholder="Description"></trix-editor>
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
    </div>
@endsection
