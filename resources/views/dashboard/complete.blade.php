@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/complete.css">
    <link rel="stylesheet" href="/css/dashboard/table-view.css">
@endsection

@section('dashboard-content')
    <div class="p-4">
        <h1 class="shortcut-title d-flex align-items-center gap-1">
            <i data-feather="check-circle" class="shortcut-icon icon-aspect-ratio"></i>
            Complete
        </h1>
        <div class="border-bottom pb-2 mt-3">
            <span class="text-black-50 d-block">1 Item</span>
        </div>
        @if (isset($items))
            @php($priority = ['0' => '-', '1' => 'Low', '2' => 'Medium', '3' => 'High'])

            <table class="table table-light table-hover mt-3">
                <thead>
                    <tr>
                        <th></th>
                        <th scope="col">Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Due date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i onclick="window.location.href='/dashboard/complete/delete/{{ $item->id }}'"
                                        data-feather="trash" class="text-danger trash-icon"></i>
                                    <i onclick="window.location.href='/dashboard/complete/reopen/{{ $item->id }}'"
                                        data-feather="refresh-ccw" class="text-primary reopen-icon"></i>
                                </div>
                            </td>
                            <td onclick="window.location.href='makan.blade.php'">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="title-container" data-bs-toggle="modal" data-bs-target="#itemPreview">
                                        {{ $item->title }}
                                    </div>
                                    <i data-feather="eye" class="view-title-icon icon-aspect-ratio"></i>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-feather="file" class="icon-aspect-ratio shortcut-type-icon"></i>
                                    {{ $item->type }}
                                </div>
                            </td>
                            <td>{{ $item->due_date == true ? date('l, M j Y', $item->due_date) : '-' }}</td>
                            <td>{{ $item->time == true ? date($timeFormat, $item->due_date) : '-' }}</td>
                            <td class="color-{{ $item->priority }}">
                                {{ $priority[$item->priority] }}
                            </td>
                            <td>
                                @if ($item->list->title)
                                    {{ $item->list->title }}
                                @elseif($item->tag->title)
                                    {{ $item->tag->title }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-shortcut-msg d-flex flex-column align-items-center justify-content-center">
                <i data-feather="check-circle" class="icon-aspect-ratio empty-shortcut-icon mb-4"></i>
                <h6 class="empty-shortcut-title">Your complete list is empty</h6>
            </div>
        @endif
    </div>
@endsection
