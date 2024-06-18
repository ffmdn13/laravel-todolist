@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/trash.css">
    <link rel="stylesheet" href="/css/dashboard/table-view.css">
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
                                    <i onclick="window.location.href='/dashboard/trash/delete/{{ $item->id }}'"
                                        data-feather="trash" class="text-danger trash-icon"></i>
                                    <i onclick="window.location.href='/dashboard/trash/reopen/{{ $item->id }}'"
                                        data-feather="refresh-ccw" class="text-primary reopen-icon"></i>
                                </div>
                            </td>
                            <td>
                                <div class="title-container" data-bs-toggle="modal" data-bs-target="#itemPreview">
                                    {{ $item->title }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-feather="file" class="icon-aspect-ratio shortcut-type-icon"></i>
                                    {{ $item->type }}
                                </div>
                            </td>
                            <td>{{ $item->due_date == true ? date('l, M  Y', $item->due_date) : '-' }}</td>
                            <td>{{ $item->time == true ? date($timeFormat, $item->time) : '-' }}</td>
                            <td class="color-{{ $item->priority }}">
                                {{ $priority[$item->priority] }}
                            </td>
                            <td>
                                @if (isset($item->list->title))
                                    {{ $item->list->title }}
                                @elseif(isset($item->tag->title))
                                    <div class="d-flex align-items-center gap-1 color-{{ $item->tag->color }}">
                                        <i data-feather="hash"
                                            class="aspect-ratio icon-w-15 tag-{{ $item->tag->color }}"></i>
                                        {{ $item->tag->title }}
                                    </div>
                                @elseif(isset($item->notebook->title))
                                    {{ $item->notebook->title }}
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
                <i data-feather="trash" class="icon-aspect-ratio empty-shortcut-icon mb-4"></i>
                <h6 class="empty-shortcut-title">Your trash list is empty</h6>
            </div>
        @endif
    </div>
@endsection
