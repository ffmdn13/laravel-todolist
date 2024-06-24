@extends('layouts.main')

@section('main-title')
    {{ $title }}
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/dashboard/table-view.css">

    {{-- trix editor cdn link --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endsection

@section('container')
    <main class="d-flex align-items-center justify-content-center min-vh-100 bg-light-subtle">
        <div class="d-flex gap-3">
            <a href="/dashboard/complete" class="pt-2 text-decoration-none align-self-start">
                <i data-feather="arrow-left" class="aspect-ratio icon-w-15"></i>
                Back
            </a>

            <form action="/dashboard/complete/view/action" method="POST" class="view-item-container p-4 h-100">
                @csrf
                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <label class="view-item-due-date d-flex align-items-center gap-1" for="date"
                            data-bs-toggle="modal" data-bs-target="#dueDateModal">
                            @if ($item->due_date)
                                <i data-feather="calendar" class="icon-w-15 aspect-ratio"></i>
                                {{ formatDateOrTime('l, M j Y', $item->due_date) . formatDateOrTime($timeFormat, $item->time) }}
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
                                                <input type="date" name="due_date" id="date" class="form-control"
                                                    aria-label="Date"
                                                    value="{{ formatDateOrTime('Y-m-d', $item->due_date, '') }}">
                                            </div>
                                            <div class="col">
                                                <label for="time" class="form-label">Time</label>
                                                <input type="time" name="time" class="form-control" aria-label="Time"
                                                    value="{{ formatDateOrTime('h:i', $item->time, '') }}">
                                            </div>
                                            <div class="col">
                                                <label for="reminder" class="form-label">Reminder</label>
                                                <input type="time" name="reminder" class="form-control"
                                                    aria-label="Reminder" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <i data-feather="flag" class="aspect-ratio icon-w-15 color-{{ $item->priority }}"></i>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <input type="text" name="title" class="view-item-title mb-2 border-0 bg-transparent w-100 p-0"
                        value="{{ $item->title }}">
                    <input class="view-item-complete-btn aspect-ratio" type="checkbox" name="is_complete" value="1"
                        @if ($item->is_complete == 1) checked @endif>
                </div>

                <div>
                    <input type="hidden" id="x" placeholder="Description" name="description"
                        value="{{ $item->description }}">

                    <div class="d-flex flex-column-reverse">
                        <div class="d-flex align-items-center justify-content-between">
                            <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                            <div>
                                <a href=""
                                    class="view-item-save-btn text-decoration-none border-0 d-flex align-items-center gap-1"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-feather="chevron-up" class="aspect-ratio icon-w-19 order-1"></i>
                                    Action
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action" value="save">Save</button>
                                    </li>
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action"
                                            value="delete">Delete</button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <trix-editor toolbar="trix-toolbar-1" input="x" class="custom-trix p-0 border-0 overflow-auto"
                            placeholder="Description"></trix-editor>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
