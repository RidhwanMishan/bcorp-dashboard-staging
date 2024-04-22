@extends('layouts.app')

@section('content')
    <div class="hidden md:flex md:bg-cover min-w-full min-h-screen bg-red-100 flex-col justify-center sm:px-6 lg:px-8 bg-fixed" style="background-image: url({{ asset('images/Main-Background.jpg') }})">

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-gray-700 border-4 border-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="sm:mx-auto sm:w-full sm:max-w-md">
                    <img class="mx-auto h-32  w-auto" src="{{ asset('images/Logo-2-(-At-top-left-of-dashboard-).png') }}" alt="Workflow" />
                    <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-white">
                        Update/Reset Password
                    </h2>
                </div>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mt-8">
                        <div class="flex">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <label for="email" class="ml-2 block text-sm font-medium leading-5 text-white">
                                Email address
                            </label>
                        </div>

                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="email" type="email" name="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('email') border-red-500 @enderror" value="{{ $email ?? old('email') }}"/>

                            @error('email')
                            <p class="text-red-500 text-xs italic mt-4">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="flex">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <label for="password" class="ml-2 block text-sm font-medium leading-5 text-white">
                                Password
                            </label>
                        </div>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="password" type="password" name="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') border-red-500 @enderror" autocomplete="new-password"/>

                            @error('password')
                            <p class="text-red-500 text-xs italic mt-4">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="flex">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <label for="password_confirm" class="ml-2 block text-sm font-medium leading-5 text-white">
                                New Password
                            </label>
                        </div>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="password_confirm" type="password" name="password_confirmation" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="new-password"/>

                        </div>
                    </div>

                    <div class="mt-6">
          <span class="block w-full rounded-md shadow-sm">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border-4 border-gray-500 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition duration-150 ease-in-out">
              Sign in
            </button>
          </span>
                    </div>
                </form>

            </div>
        </div>
    </div>

{{--    <div class="container mx-auto">--}}
{{--        <div class="flex flex-wrap justify-center">--}}
{{--            <div class="w-full max-w-sm">--}}
{{--                <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">--}}

{{--                    <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">--}}
{{--                        {{ __('Reset Password') }}--}}
{{--                    </div>--}}

{{--                    <form class="w-full p-6" method="POST" action="{{ route('password.update') }}">--}}
{{--                        @csrf--}}

{{--                        <input type="hidden" name="token" value="{{ $token }}">--}}

{{--                        <div class="flex flex-wrap mb-6">--}}
{{--                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">--}}
{{--                                {{ __('E-Mail Address') }}:--}}
{{--                            </label>--}}

{{--                            <input id="email" type="email" class="form-input w-full @error('email') border-red-500 @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>--}}

{{--                            @error('email')--}}
{{--                                <p class="text-red-500 text-xs italic mt-4">--}}
{{--                                    {{ $message }}--}}
{{--                                </p>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

{{--                        <div class="flex flex-wrap mb-6">--}}
{{--                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">--}}
{{--                                {{ __('Password') }}:--}}
{{--                            </label>--}}

{{--                            <input id="password" type="password" class="form-input w-full @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">--}}

{{--                            @error('password')--}}
{{--                                <p class="text-red-500 text-xs italic mt-4">--}}
{{--                                    {{ $message }}--}}
{{--                                </p>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

{{--                        <div class="flex flex-wrap mb-6">--}}
{{--                            <label for="password-confirm" class="block text-gray-700 text-sm font-bold mb-2">--}}
{{--                                {{ __('Confirm Password') }}:--}}
{{--                            </label>--}}

{{--                            <input id="password-confirm" type="password" class="form-input w-full" name="password_confirmation" required autocomplete="new-password">--}}
{{--                        </div>--}}

{{--                        <div class="flex flex-wrap">--}}
{{--                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-gray-100 font-bold  py-2 px-4 rounded focus:outline-none focus:shadow-outline">--}}
{{--                                {{ __('Reset Password') }}--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </form>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
