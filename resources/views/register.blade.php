@extends('layouts.main')

@section('main-title')
    Create your account
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/form-account.css">
@endsection

@section('container')
    <main class="container d-flex flex-column justify-content-center min-vh-100">
        <div class="row justify-content-center">
            <div class="col-5">
                <div class="bg-body rounded-0 p-5">
                    <form class="card-body" method="POST" action="{{ route('register') }}">
                        <h1 class="custom-font-size text-center mb-3">Sign up</h1>
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <input class="form-control rounded-0 border-0 border-bottom" type="text" id="nickname"
                                    name="nickname" autofocus placeholder="Nickname (optional)"
                                    value="{{ old('nickname') }}">
                                <i data-feather="user" class="custom-input-icon"></i>
                            </div>
                            @error('nickname')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <input class="form-control rounded-0 border-0 border-bottom" type="text" id="email"
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
                        <div class="mb-4">
                            <div class="d-flex align-items-center"><input
                                    class="form-control rounded-0 border-0 border-bottom" type="password"
                                    id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                <i data-feather="unlock" class="custom-input-icon"></i>
                            </div>
                            @error('password_confirmation')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="border-bottom pb-3 d-flex align-items-center justify-content-between">
                            {{-- <a class="forget-password" href="/forget-password">Forget your password?</a> --}}
                            <button class="custom-btn ms-auto" type="submit">Create</button>
                        </div>
                        <p class="text-center mt-3 register-text">Already have an account? <a href="{{ route('login') }}"
                                class="register">Log in</a></p>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
