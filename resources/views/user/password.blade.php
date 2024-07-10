@extends('layouts.main')

@section('main-title')
    {{ $title }}
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/user/password.css">
@endsection

@section('container')
    <main class="mt-4 mx-auto">
        <div class="mb-3">
            <h2>Change password</h3>
                <div class="small-information-text">
                    We will send you an email verification for verify that this is the real you.
                </div>
        </div>
        <form action="/user/profile/change/password" method="POST">
            <div class="mb-2">
                <label for="inputPassword5" class="form-label">Old password</label>
                <input type="password" name="old_password" id="inputPassword5"
                    class="form-control @error('old_password') is-invalid @enderror" aria-describedby="passwordHelpBlock"
                    placeholder="Your origin password">
                @error('old_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="inputPassword5" class="form-label">New password</label>
                <input type="password" name="new_password" id="inputPassword5"
                    class="form-control @error('new_password') is-invalid @enderror" aria-describedby="passwordHelpBlock"
                    placeholder="Your new password">
                @error('new_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @else
                    <div id="passwordHelpBlock" class="form-text">
                        Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces,
                        special characters, or emoji.
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label for="inputPassword5" class="form-label">Password confirmation</label>
                <input type="password" name="new_password_confirmation" id="inputPassword5"
                    class="form-control @error('new_password_confirmation') is-invalid @enderror"
                    aria-describedby="passwordHelpBlock" placeholder="Confirm your password">
                @error('new_password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @csrf
            <div>
                <button type="submit" class="change-password-btn border-0 p-2 text-white">Change</button>
                <button type="button" class="go-back-btn border-0 p-2 text-white"
                    onclick="window.location.href='/user/profile'">Back</button>
            </div>
        </form>
    </main>
@endsection
