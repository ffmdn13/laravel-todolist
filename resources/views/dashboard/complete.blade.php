@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/dashboard/table-view.css">
@endsection

@section('dashboard-content')
    <div class="p-4">
        <h1 class="table-view-title d-flex align-items-center gap-1">
            <i data-feather="check-circle" class="icon-w-21 aspect-ratio"></i>
            Complete
        </h1>

        @if ($items->isNotEmpty())
            <table class="table table-light-subtle table-hover">
                <thead>
                    <tr>
                        <th>Action</th>
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
                                        data-feather="trash" class="text-danger icon-w-16 aspect-ratio cursor-pointer"></i>
                                    <i onclick="window.location.href='/dashboard/complete/reopen/{{ $item->id }}'"
                                        data-feather="refresh-ccw"
                                        class="text-primary icon-w-16 aspect-ratio cursor-pointer"></i>
                                </div>
                            </td>
                            <td class="table-view-first-column"
                                onclick="window.location.href='/dashboard/complete/view/{{ $item->id }}/{{ $item->title }}'">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="table-view-td-title text-nowrap overflow-hidden">
                                        {{ $item->title }}
                                    </div>
                                    <i data-feather="eye" class="icon-w-15 table-view-td-icon aspect-ratio"></i>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-feather="file" class="aspect-ratio icon-w-15"></i>
                                    {{ $item->type }}
                                </div>
                            </td>
                            <td>{{ formatDateOrTime('l, M j Y', $item->due_date, '-') }}</td>
                            <td>{{ formatDateOrTime($timeFormat, $item->time, '-') }}</td>
                            <td class="color-{{ $item->priority }}">
                                {{ $priority[$item->priority] }}
                            </td>
                            <td>
                                @if (isset($item->list))
                                    {{ $item->list->title }}
                                @elseif(isset($item->tag))
                                    <div class="d-flex align-items-center gap-1 color-{{ $item->tag->color }}">
                                        <i data-feather="hash"
                                            class="aspect-ratio icon-w-15 tag-{{ $item->tag->color }}"></i>
                                        {{ $item->tag->title }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-table-view-container d-flex flex-column align-items-center justify-content-center">
                <i data-feather="check-circle" class="aspect-ratio empty-table-view-icon mb-4"></i>
                <h6 class="empty-table-view-title">Your complete list is empty</h6>
                <span class="empty-table-view-desc">
                    Your completed task will shown up here.
                </span>
            </div>
        @endif
    </div>
@endsection
