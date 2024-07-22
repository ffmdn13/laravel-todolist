@extends('layouts.main')

@section('main-title')
    {{ $title }}
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/light/user/setting.css">
    @if ($apperance['theme'] === 'dark')
        <link rel="stylesheet" href="/css/dark/user/setting.css">
    @endif
@endsection

@section('container')
    <main class="user-setting-layout min-vh-100">
        @include('partials.user-setting-sidebar')

        <div class="bg-body-tertiary py-3 px-3">
            <h4 class="mb-3">Apperance</h4>

            <form action="/user/setting/apperance/update" method="POST">
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-1 col-form-label">Theme</label>
                    <div class="col-sm-3">
                        <select class="form-select bg-transparent rounded-0" name="apperance"
                            aria-label="Default select example">
                            <option value="light" @if ($apperance['theme'] === 'light') selected @endif>Light</option>
                            <option value="dark" @if ($apperance['theme'] === 'dark') selected @endif>Dark</option>
                        </select>
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
