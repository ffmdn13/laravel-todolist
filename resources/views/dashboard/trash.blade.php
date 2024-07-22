@extends('layouts.dashboard')

@section('dashboard-title')
    {{ $title }}
@endsection

@section('additional-dashboard-head')
    <link rel="stylesheet" href="/css/light/dashboard/table-view.css">
    @if ($theme === 'dark')
        <link rel="stylesheet" href="/css/dark/dashboard/table-view.css">
    @endif
@endsection

@section('dashboard-content')
    <div class="p-4 dark-bg-theme">
        <h1 class="table-view-title d-flex align-items-center gap-1">
            <i data-feather="trash" class="icon-w-21 aspect-ratio"></i>
            Trashed Note
        </h1>

        <div class="border-bottom pb-2 mb-3">
            <span class="text-black-50 d-block mt-2">
                @php($count = $items->count())
                {{ $count > 1 ? "$count Notes" : "$count Note" }}
            </span>
        </div>

        @if ($items->isNotEmpty())
            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        <th></th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Last Edited</th>
                        <th scope="col">Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items->items() as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i onclick="window.location.href='/dashboard/trash/delete/{{ $item->id }}'"
                                        data-feather="trash" class="text-danger icon-w-16 aspect-ratio cursor-pointer"></i>
                                    <i onclick="window.location.href='/dashboard/trash/reopen/{{ $item->id }}'"
                                        data-feather="refresh-ccw"
                                        class="text-primary icon-w-16 aspect-ratio-ratio cursor-pointer"></i>
                                </div>
                            </td>
                            <td
                                onclick="window.location.href='/dashboard/trash/view/{{ $item->id }}/{{ $item->title . $queryParams }}'">
                                <div class="d-flex align-items-center gap-2 cursor-pointer">
                                    <div class="table-view-td-title text-nowrap overflow-hidden">
                                        {{ $item->title }}
                                    </div>
                                    <i data-feather="eye" class="icon-w-15 table-view-td-icon aspect-ratio"></i>
                                </div>
                            </td>
                            <td class="table-view-first-column">
                                <div class="table-view-td-description text-nowrap overflow-hidden">
                                    {{ is_string($item->description) ? strip_tags($item->description) : '-' }}
                                </div>
                            </td>
                            <td>
                                {{ getLastEdited($item->due_date) }}
                            </td>
                            <td>
                                {{ isset($item->notebook) ? $item->notebook->title : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $items->links() }}
        @else
            <div class="empty-table-view-container d-flex flex-column align-items-center justify-content-center">
                <i data-feather="trash" class="aspect-ratio empty-table-view-icon mb-4"></i>
                <h6 class="empty-table-view-title">Your trash list is empty</h6>
                <span class="empty-table-view-desc">
                    Your trashed item will show here..
                </span>
            </div>
        @endif
    </div>
@endsection
