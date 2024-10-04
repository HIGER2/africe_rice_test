@extends('layouts.app')

@section('content')
{{-- @include('includes.header') --}}

<section class="login">
    <div class="container">
        <div class="login-form">
            <div class="card">
                <div class="logo">
                    <img src="https://mycareer.africarice.org/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fafricarice.b93af9cc.webp&w=1080&q=75" alt="Logo" class="logo">
                </div>
                <h2>Sign in</h2>
                <p>Please Sign in with your Mycareer Africarice credentials.</p>
                    {{-- <p>Sign into your staff account to get access to our platform.</p> --}}
                    @if ($errors->has('message'))
                        <div class="alert alert-danger">
                            {{ $errors->first('message') }}
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" value="" name="email" placeholder="Enter your CGIAR email address" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            {{-- <label for="password">Password <span>(default is your RESNO)</span></label> --}}
                            <input type="password" value="" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="login-button">
                            Login
                            <i class="uil uil-arrow-right"></i>
                        </button>
                    </form>
                    {{-- <a href="https://mycareer.africarice.org/auth/recovery" class="forgot-password">Forgot your password?</a> --}}
                    {{-- <span class="subtitle">Please log in with your Mycareer Africarice credentials.</span> --}}
            </div>
        </div>
</div>
</section>

@endsection
