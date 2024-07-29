@extends('layouts.app')

@section('content')
@include('includes.header')
 <div class="container">
        <div class="login-form">
            <div class="card">
                <h2>Sign in</h2>
                    <p>Sign into your staff account to get access to our platform.</p>
                    @if ($errors->has('message'))
                        <div class="alert alert-danger">
                            {{ $errors->first('message') }}
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" value="k.sams@cgiar.org" name="email" placeholder="Enter your CGIAR email address" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span>(default is your RESNO)</span></label>
                            <input type="password" value="2204Lucio" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="login-button">
                            Login
                            <i class="uil uil-arrow-right"></i>
                        </button>
                    </form>
                    <a href="/forgot-password" class="forgot-password">Forgot your password?</a>
                </div>
        </div>
    </div>
@endsection
