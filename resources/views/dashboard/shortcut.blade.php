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
    <div class="p-4 h-100 dark-bg-theme">
        <h1 class="table-view-title d-flex align-items-center gap-1">
            <i data-feather="star" class="icon-w-21 aspect-ratio"></i>
            Shortcut
        </h1>

        <div class="border-bottom pb-2 mb-2">
            <span class="text-black-50 d-block mt-2">
                @php($count = $items->count())
                {{ $count > 1 ? "$count Notes" : "$count Note" }}
            </span>
        </div>

        @if ($items->isNotEmpty())
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Last edited</th>
                        <th scope="col">Notebook</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items->items() as $item)
                        <tr>
                            <td onclick="window.location.href='/dashboard/shortcut/view/{{ $item->id }}/{{ $item->title . $queryParams }}'"
                                class="cursor-pointer">
                                <div class="d-flex align-items-center gap-2">
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
                <i data-feather="star" class="aspect-ratio empty-table-view-icon mb-4"></i>
                <h6 class="empty-table-view-title">Your shortcut list is empty</h6>
                <span class="empty-table-view-desc">
                    You can add some by clicking action button in task or note preview.
                </span>
            </div>
        @endif
    </div>
@endsection
