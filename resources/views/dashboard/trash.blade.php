@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/trash.css">
@endsection

@section('dashboard-content')
    <div class="p-4">
        <h1 class="shortcut-title d-flex align-items-center gap-1">
            <i data-feather="trash" class="shortcut-icon icon-aspect-ratio"></i>
            Trash
        </h1>
        <div class="border-bottom pb-2 mt-3">
            <span class="text-black-50 d-block">1 Item</span>
        </div>
        @if (isset($items))
            <table class="table table-light table-hover mt-3">
                <thead>
                    <tr>
                        <th></th>
                        <th scope="col">Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Due date</th>
                        <th scope="col">Priority</th>
                        <th scope="col">List</th>
                        <th scope="col">Tag</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i onclick="window.location.href='/dashboard/trash/1'" data-feather="trash"
                                    class="text-danger trash-icon"></i>
                                <i onclick="window.location.href='/dashboard/reopen/1'" data-feather="refresh-ccw"
                                    class="text-primary reopen-icon"></i>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="title-container" data-bs-toggle="modal" data-bs-target="#itemPreview">
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia, perferendis! Obcaecati,
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
                        <td>🚀 Workout</td>
                        <td>-</td>
                        <td class="compleated">
                            Compleated
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="empty-shortcut-msg d-flex flex-column align-items-center justify-content-center">
                <i data-feather="trash" class="icon-aspect-ratio empty-shortcut-icon mb-4"></i>
                <h6 class="empty-shortcut-title">Your trash list is empty</h6>
            </div>
        @endif
    </div>
@endsection
