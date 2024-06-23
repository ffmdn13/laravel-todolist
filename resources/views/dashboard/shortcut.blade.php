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
            <i data-feather="star" class="icon-w-21 aspect-ratio"></i>
            Shortcut
        </h1>

        <div class="border-bottom pb-2 mt-3">
            <span class="text-black-50 d-block">
                @php($count = $items->count())
                {{ $count > 1 ? "$count Shortcuts" : "$count Shortcut" }}</span>
        </div>

        @if (isset($items))
            @php($priority = ['0' => '-', '1' => 'Low', '2' => 'Medium', '3' => 'High'])

            <table class="table table-light-subtle table-hover">
                <thead>
                    <tr>`
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Last edited</th>
                        <th scope="col">Notebook</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td
                                onclick="window.location.href='/dashboard/shortcut/view/{{ $item->id }}/{{ $item->title }}'">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="table-view-td-title text-nowrap overflow-hidden">
                                        {{ $item->title }}
                                    </div>
                                    <i data-feather="eye" class="icon-w-15 table-view-td-icon aspect-ratio"></i>
                                </div>
                            </td>
                            <td>
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
