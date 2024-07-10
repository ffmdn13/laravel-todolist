@extends('layouts.main')

@section('main-title')
    {{ $title }}
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/dashboard/user.css">
@endsection

@section('container')
    <main class="d-flex align-items-center justify-content-center min-vh-100 bg-light-subtle">

        {{-- user profile section start --}}
        <div class="p-4 rounded custom-width">

            {{-- user profile section start --}}
            <div class="d-flex align-items-center gap-2 mx-auto fit-content">
                <img src="{{ asset('storage/' . $user->profile) }}" alt=""
                    class="user-profile aspect-ratio rounded-circle cursor-pointer">
                <div>
                    <h3>{{ $user->email }}</h3>
                    <span>{{ $user->nickname ?? '-' }}</span>
                </div>
            </div>
            {{-- user profile section end --}}


            {{-- user profile setting section start --}}

            <div class="mt-4">
                <div class="card border-0 shadow cursor-pointer mb-2" data-bs-toggle="modal"
                    data-bs-target="#update-nickname">
                    <div class="card-body overflow-hidden position-relative">
                        <div class="d-flex align-items-center">
                            <span>Change nickname</span>
                            <i data-feather="arrow-right" class="icon-translate-x-anim aspect-ratio icon-w-20"></i>
                        </div>
                        <i data-feather="user"
                            class="icon-opacity-anim aspect-ratio icon-absolute icon-w-26 position-absolute"></i>
                    </div>
                </div>
                <div class="card border-0 shadow cursor-pointer mb-2"
                    onclick="window.location.href='/user/profile/change/password'">
                    <div class="card-body overflow-hidden position-relative">
                        <div class="d-flex align-items-center">
                            <span>Change password</span>
                            <i data-feather="arrow-right" class="icon-translate-x-anim aspect-ratio icon-w-20"></i>
                        </div>
                        <i data-feather="lock"
                            class="icon-opacity-anim aspect-ratio icon-absolute icon-w-26 position-absolute"></i>
                    </div>
                </div>
                <div class="card border-0 shadow cursor-pointer mb-2" onclick="window.location.href='/logout'">
                    <div class="card-body overflow-hidden position-relative">
                        <div class="d-flex align-items-center">
                            <span>Sign out</span>
                            <i data-feather="arrow-right" class="icon-translate-x-anim aspect-ratio icon-w-20"></i>
                        </div>
                        <i data-feather="log-out"
                            class="icon-opacity-anim aspect-ratio icon-absolute icon-w-26 position-absolute"></i>
                    </div>
                </div>
                <div class="card border-0 shadow cursor-pointer mb-2" data-bs-toggle="modal" data-bs-target="#delete-user">
                    <div class="card-body overflow-hidden position-relative">
                        <div class="d-flex align-items-center">
                            <span>Delete account</span>
                            <i data-feather="arrow-right" class="icon-translate-x-anim aspect-ratio icon-w-20"></i>
                        </div>
                        <i data-feather="trash"
                            class="icon-opacity-anim aspect-ratio icon-absolute icon-w-26 position-absolute"></i>
                    </div>
                </div>
            </div>

            {{-- user porifle setting section end --}}

            <a href="/dashboard" style="color: var(--lightColorPrimary) !important;">
                <i data-feather="chevron-left" class="aspect-ratio icon-w-17"></i>
                Back
            </a>
        </div>

        {{-- Change username modal start --}}

        <div class="modal fade" id="update-nickname" tabindex="-1" aria-labelledby="update-nickname" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="/user/profile/update/account/info" method="POST" enctype="multipart/form-data">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nickname') is-invalid @enderror"
                                    id="floatingInput" value="{{ $user->nickname }}" placeholder="" name="nickname">
                                <label for="floatingInput">Nickname</label>
                                @error('nickname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" name="new_profile" id="inputGroupFile02">
                                <label class="input-group-text" for="inputGroupFile02">Upload</label>
                            </div>
                            @csrf
                            <button class="update-nickname-btn">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Change username modal end --}}


        {{-- Delete user modal start --}}

        <div class="modal fade" id="delete-user" tabindex="-1" aria-labelledby="delete-user" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="/user/profile/delete/account" method="POST">
                            <div class="small-information-text mb-3">
                                Your account will <b>deleted permanently</b> and cannot be restored.
                                if you want to continue, please enter your password and the reason why you want to delete
                                it.
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="exampleFormControlInput1" placeholder="Your origin password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1"class="form-labe">
                                    Reason for deleting your account
                                </label>
                                <textarea class="form-control @error('reason_text') is-invalid @enderror" name="reason_text"
                                    id="exampleFormControlTextarea1" rows="3"></textarea>
                                @error('reason_text')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1"class="form-label">
                                    Advice for this app (optional)
                                </label>
                                <textarea class="form-control @error('advice_text') is-invalid @enderror" name="advice_text"
                                    id="exampleFormControlTextarea1" rows="3" placeholder="Your advice will be helpful for future development"></textarea>
                                @error('advice_text')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @csrf
                            <button class="btn btn-danger text-white">Delete account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete user modal end --}}

    </main>
@endsection
