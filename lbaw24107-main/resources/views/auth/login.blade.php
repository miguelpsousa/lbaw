@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form method="POST" action="{{ route('login') }}" class="max-w-md w-full p-6 bg-white shadow-lg rounded-lg">
        {{ csrf_field() }}

        <!-- Email Field -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if ($errors->has('email'))
                <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <!-- Password Field -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" name="password" required
                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if ($errors->has('password'))
                <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <!-- Remember Me Checkbox -->
        <div class="mb-6 flex items-center">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} 
                class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 text-sm text-gray-700">Remember Me</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Login
        </button>
        <div class="mt-4">
            <a href="{{ route('google-auth') }}" 
                class="flex items-center justify-center w-full py-2 px-4 bg-gray-200 text-gray-900 font-semibold rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 48 48">
                    <path fill="#fbc02d" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                    <path fill="#e53935" d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
                    <path fill="#4caf50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                    <path fill="#1565c0" d="M43.611 20.083 43.595 20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                </svg>
                
                Continue with Google
            </a>
        </div>

        <!-- Register Link -->
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:text-blue-700">
                Don't have an account? Register
            </a>
        </div>

        <!-- Forgot Password Link -->
        <div class="mt-4 text-center">
            <a href="{{ url('/forgot-password') }}" class="text-sm text-blue-600 hover:text-blue-700">
                Forgot your password?
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <p class="text-green-500 text-sm mt-4 text-center">
                {{ session('success') }}
            </p>
        @endif
    </form>
</div>

@endsection
