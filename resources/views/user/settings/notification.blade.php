@extends('layouts.main')

@section('main-title')
    {{ $title }}
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/light/user/setting.css">
    @if ($theme === 'dark')
        <link rel="stylesheet" href="/css/dark/user/setting.css">
    @endif
@endsection

@section('container')
    <main class="user-setting-layout min-vh-100">
        @include('partials.user-setting-sidebar')

        <div class="bg-body-tertiary py-3 px-3">
            <h4 class="mb-3">Notifiction</h4>

            <form action="/user/setting/notification/update" method="POST">
                <div class="mb-3 row d-flex align-items-center">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Notify missed task</label>
                    <div class="col-sm-3">
                        <input type="checkbox" name="notify_missed_task" @if ($notification->notify_missed_task === '1') checked @endif
                            value="1">
                    </div>
                </div>

                @csrf
                <button type="submit"
                    class="save-setting-btn d-flex align-items-center gap-1 border-0 p-2 px-2 text-white">
                    <i data-feather="save" class="aspect-ratio icon-w-16"></i>
                    Save change
                </button>
            </form>
        </div>
    </main>
@endsection
