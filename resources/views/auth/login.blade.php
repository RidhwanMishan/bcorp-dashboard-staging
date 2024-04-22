@extends('layouts.app')

@section('title') Signin @endsection

@section('content')

<div class="signin-wrapper">

    <div class="signin-box">

        <img class="slim-logo tx-center" style="display:block; margin-left:auto; margin-right:auto;" src="{{ asset('images/logo-color.svg') }}" height="70px"/>

        <h2 class="signin-title-primary">Welcome back!</h2>
        <h3 class="signin-title-secondary">Sign in to continue.</h3>

        <form class="w-full p-6" method="GET" action="{{ route('signin') }}"> 
            @csrf 

            <div class="form-group">
                <!--label for="email" class="block text-gray-700 text-sm font-bold mb-2"> 
                    {{ __('E-Mail Address') }}: 
                </label--> 

                <input id="email" type="email" class="form-input form-control @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email" autofocus> 

                @error('email') 
                    <p class="text-red-500 text-xs italic mt-4"> 
                        {{ $message }} 
                    </p> 
                @enderror 
            </div><!-- form-group -->

            <div class="form-group mg-b-50">
                <!--label for="password" class="block text-gray-700 text-sm font-bold mb-2"> 
                    {{ __('Password') }}: 
                </label--> 

                <input id="password" type="password" class="form-input form-control @error('password') border-red-500 @enderror" name="password" placeholder="Enter your password" required> 

                @error('password') 
                    <p class="text-red-500 text-xs italic mt-4"> 
                        {{ $message }} 
                    </p> 
                @enderror 
            </div><!-- form-group -->

            {{-- <div class="flex mb-6"> --}}
                {{-- <label class="inline-flex items-center text-sm text-gray-700" for="remember"> --}}
                {{-- <input type="checkbox" name="remember" id="remember" class="form-checkbox" {{ old('remember') ? 'checked' : '' }}> --}}
                {{-- <span class="ml-2">{{ __('Remember Me') }}</span> --}}
                {{-- </label> --}}
            {{-- </div> --}}

            <button class="btn btn-primary btn-block">
                {{ __('Login') }} 
            </button> 

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-500 hover:text-blue-700 whitespace-no-wrap no-underline ml-auto" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif

            @if (Route::has('register'))
                <p class="w-full text-xs text-center text-gray-700 mt-8 -mb-4">
                    {{ __("New User?") }}
                    <a class="text-blue-500 hover:text-blue-700 no-underline" href="{{ route('register') }}">
                        {{ __('Sign Up') }}
                    </a>
                </p>
            @endif
        </form>

    </div><!-- signin-box -->

</div><!-- signin-wrapper -->

@endsection
