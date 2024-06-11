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
            <span class="text-black-50 d-block">
                @php($count = $shortcuts->count())
                {{ $count > 1 ? "$count Shortcuts" : "$count Shortcut" }}</span>
        </div>

        @if (isset($shortcuts))
            <table class="table table-light table-hover mt-3">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Due date / Last edited</th>
                        <th scope="col">Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shortcuts as $shortcut)
                        <tr>
                            <td onclick="window.location.href='/dashboard/{{ $shortcut->type }}/{{ $shortcut->id }}'">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="title-container">
                                        {{ $shortcut->title }}
                                    </div>
                                    <i data-feather="eye" class="view-title-icon icon-aspect-ratio"></i>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i data-feather="{{ $shortcut->type === 'task' ? 'file' : 'file-text' }}"
                                        class="icon-aspect-ratio shortcut-type-icon"></i>{{ $shortcut->type }}
                                </div>
                            </td>
                            <td>
                                @if (is_string($shortcut->due_date))
                                    @if ($shortcut->type === 'task')
                                        {{ $shortcut->due_date }}
                                    @else
                                        {{ getLastEdited($shortcut->due_date) }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="priority-color-high">
                                @if ($shortcut->type === 'task')
                                    <i data-feather="flag"
                                        class="icon-aspect-ratio priority-icon color-{{ $shortcut->priority }}"></i>
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
                <i data-feather="star" class="icon-aspect-ratio empty-shortcut-icon mb-4"></i>
                <h6 class="empty-shortcut-title">Your shortcut list is empty</h6>
                <span class="empty-shortcut-desc">
                    You can add some by clicking action button in task or note preview.
                </span>
            </div>
        @endif
    </div>
@endsection
