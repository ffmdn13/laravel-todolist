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
            <h4 class="mb-3">Date and time</h4>

            <form action="/user/setting/datetime/update" method="POST">
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Time format</label>
                    <div class="col-sm-3">
                        <select class="form-select bg-transparent rounded-0" name="time_format"
                            aria-label="Default select example">
                            <option value="24hr" @if ($datetime->time_format === '24hr') selected @endif>24hr</option>
                            <option value="12hr" @if ($datetime->time_format === '12hr') selected @endif>12hr</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Default date</label>
                    <div class="col-sm-3">
                        <select class="form-select bg-transparent rounded-0" name="default_date"
                            aria-label="Default select example">
                            <option value="today" @if ($datetime->default_date === 'today') selected @endif>Today</option>
                            <option value="tomorrow" @if ($datetime->default_date === 'tomorrow') selected @endif>Tomorrow</option>
                            <option value="day_after_tomorrow" @if ($datetime->default_date === 'day_after_tomorrow') selected @endif>Day after
                                tomorrow</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Timezone</label>
                    <div class="col-sm-3">
                        <select class="form-select bg-transparent rounded-0" name="timezone"
                            aria-label="Default select example">
                            <option value="Asia/Jakarta">Asia/Jakarta</option>
                            <option value="Asia/Seoul">Asia/Seoul</option>
                            <option value="Asia/Singapore">Asia/Singapore</option>
                            <option value="Asia/Pontianak">Asia/Pontianak</option>
                            <option value="UTC">UTC</option>
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
