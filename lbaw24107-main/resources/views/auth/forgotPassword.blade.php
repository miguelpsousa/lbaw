@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form method="POST" action="{{ route('password.email') }}" class="max-w-md w-full p-6 bg-white shadow-lg rounded-lg">
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

        <!-- Submit Button -->
        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Send Password Reset Link
        </button>

        <!-- Back to Login Link -->
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700">
                Back to Login
            </a>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <p class="text-green-500 text-sm mt-4 text-center">
                {{ session('status') }}
            </p>
        @endif
    </form>
</div>
@endsection