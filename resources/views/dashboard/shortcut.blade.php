@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/shortcut.css">
@endsection

@section('dashboard-content')
    <div class="p-4">
        <h1 class="shortcut-title d-flex align-items-center gap-1">
            <i data-feather="star" class="shortcut-icon icon-aspect-ratio"></i>
            Shortcut
        </h1>
        <div class="border-bottom pb-2 mt-3">
            <span class="text-black-50 d-block">2 Shortcuts</span>
        </div>
        @if (isset($shortcuts))
            <table class="table table-light table-hover mt-3">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Due date</th>
                        <th scope="col">Priority</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td onclick="window.location.href='/dashboard/task'">
                            <div class="d-flex align-items-center gap-2">
                                <div class="title-container">
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia, perferendis! Obcaecati,
                                    maxime enim. Error quae officia libero laborum nesciunt rerum ratione, fugiat
                                    necessitatibus
                                    quo optio vitae quibusdam harum voluptates repellendus adipisci minus maiores amet
                                    suscipit.
                                    Voluptas suscipit maxime nostrum ad deleniti alias adipisci nisi ea sunt. Saepe odit
                                    expedita nisi?
                                </div>
                                <i data-feather="eye" class="view-title-icon icon-aspect-ratio"></i>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i data-feather="file" class="icon-aspect-ratio shortcut-type-icon"></i>Task
                            </div>
                        </td>
                        <td>Today, 5:45 PM</td>
                        <td class="priority-color-high">
                            High
                        </td>
                    </tr>
                    <tr>
                        <td onclick="window.location.href='/dashboard/note'">
                            <div class="d-flex align-items-center gap-2">
                                <div class="title-container">
                                    Note to workout every morning
                                </div>
                                <i data-feather="eye" class="view-title-icon icon-aspect-ratio"></i>
                            </div>

                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i data-feather="file-text" class="icon-aspect-ratio shortcut-type-icon"></i>Note
                            </div>
                        </td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="empty-shortcut-msg d-flex flex-column align-items-center justify-content-center">
                <i data-feather="star" class="icon-aspect-ratio empty-shortcut-icon mb-4"></i>
                <h6 class="empty-shortcut-title">Your shortcut list is empty</h6>
                <span class="empty-shortcut-desc">
                    You can add some by clicking action button in task or note preview.
                </span>
            </div>
        @endif
    </div>
@endsection
