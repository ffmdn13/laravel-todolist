@extends('layouts.main')

@section('main-title')
    Login to your account
@endsection

@section('additional-main-head')
    <meta name="color-scheme" content="dark">
    <link rel="stylesheet" href="/css/form-account.css">
@endsection

@section('container')
    <main class="container d-flex flex-column justify-content-center min-vh-100">
        <div class="row justify-content-center">
            <div class="col-5">
                @if ($errors->has('loginFailed'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex gap-1">
                            <i data-feather="alert-triangle"
                                style="aspect-ratio: 1/1; width: 17px;"></i>{{ $errors->get('loginFailed')[0] }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session()->has('registerSuccess'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex gap-1">
                            <i data-feather="check-circle"
                                style="aspect-ratio: 1/1; width: 17px;"></i>{{ session()->get('registerSuccess') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="bg-body rounded-0 p-5">
                    <form class="card-body" method="POST" action="{{ route('login') }}">
                        <h1 class="custom-font-size text-center mb-3">Log in</h1>
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <input class="form-control rounded-0 border-0 border-bottom" type="email" id="email"
                                    name="email" autofocus placeholder="Email address" value="{{ old('email') }}">
                                <i data-feather="mail" class="custom-input-icon"></i>
                            </div>
                            @error('email')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="d-flex align-items-center"><input
                                    class="form-control rounded-0 border-0 border-bottom" type="password" id="password"
                                    name="password" placeholder="Password">
                                <i data-feather="lock" class="custom-input-icon"></i>
                            </div>
                            @error('password')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="border-bottom pb-3 d-flex align-items-center justify-content-between">
                            <a class="forget-password" href="/forget-password">Forget your password?</a>
                            <button class="custom-btn" type="submit">Log in</button>
                        </div>
                        <p class="text-center mt-3 register-text">
                            Dont have an account?
                            <a href="{{ route('register') }}" class="register">Signup</a>
                        </p>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
