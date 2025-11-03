@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <form method="POST" action="{{ route('register') }}" class="max-w-md w-full p-6 bg-white shadow-lg rounded-lg">
      {{ csrf_field() }}

      <!-- Name Field -->
      <div class="mb-6">
          <label for="username" class="block text-sm font-medium text-gray-700">Name</label>
          <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
              class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          @if ($errors->has('username'))
              <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('username') }}</span>
          @endif
      </div>

      <!-- Email Field -->
      <div class="mb-6">
          <label for="email" class="block text-sm font-medium text-gray-700">E-Mail Address</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required
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

      <!-- Confirm Password Field -->
      <div class="mb-6">
          <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input id="password-confirm" type="password" name="password_confirmation" required
              class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
          Register
      </button>

      <!-- Login Link -->
      <div class="mt-4 text-center">
          <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700">
              Already have an account? Login
          </a>
      </div>
  </form>
</div>
@endsection