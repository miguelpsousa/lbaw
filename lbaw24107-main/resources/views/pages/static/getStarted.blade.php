@extends('layouts.app')

@section('content')
<div class="text-center py-32 px-4 pt-36">
    <!-- Main Heading -->
    <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
        Where events come to life!
    </h1>
    <!-- Subheading -->
    <h2 class="text-lg md:text-2xl font-medium text-gray-600 mb-6">
        Easily create and manage events, invite attendees,
    </h2>
    <h2 class="text-lg md:text-2xl font-medium text-gray-600 mb-8">
        share files, and collaborate
    </h2>
    <!-- Get Started Button -->
    <a href="{{ url('/register') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 ease-in-out mb-8">
        Get started
        <i class="fa-solid fa-arrow-right ml-2"></i>
    </a>
    <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard mockup" class="w-full md:w-3/4 mx-auto">
</div>
@endsection