@extends('layouts.main')

@section('main-title')
    Forget Password
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/forget-password.css">
@endsection

@section('container')
    <main class="d-flex align-items-center justify-content-center min-vh-100 bg-white">
        <form action="/forget-password" method="POST" class="border p-4" style="width: 500px;">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Your email address" id="exampleInputEmail1" aria-describedby="emailHelp">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" placeholder="Your new password"
                    class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1">
            </div>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password Confirmation</label>
                <input type="password" name="password_confirmation" placeholder="Confirm new password"
                    class="form-control @error('password_confirmation') is-invalid @enderror" id="exampleInputPassword1">
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @csrf
            <button type="submit" class="btn btn-primary">Reset</button>
        </form>
    </main>
@endsection
