@extends('layouts.app')

@section('content')

<div class="container mx-auto px-6 py-12">
    <!-- About Us Heading -->
    <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
        About Us
    </h1>
    <p class="text-lg text-gray-700 mb-8">
        Welcome to our Project Management Website! We are dedicated to helping teams collaborate and manage their projects efficiently.
    </p>
    
    <!-- Our Mission Heading -->
    <h2 class="text-3xl font-semibold text-gray-900 mt-8 mb-4">
        Our Mission
    </h2>
    <p class="text-lg text-gray-700 mb-8">
        Our mission is to provide a platform that simplifies project management and enhances team productivity. We believe in the power of collaboration and strive to create tools that make it easier for teams to work together.
    </p>

    <!-- Our Team Heading -->
    <h2 class="text-3xl font-semibold text-gray-900 mt-8 mb-4">
        Our Team
    </h2>
    <p class="text-lg text-gray-700 mb-8">
        We are a group of passionate individuals with diverse backgrounds in technology, design, and business. Our team is committed to delivering the best user experience and continuously improving our platform.
    </p>

    <!-- Contact Us Heading -->
    <h2 class="text-3xl font-semibold text-gray-900 mt-8 mb-4">
        Contact Us
    </h2>
    <p class="text-lg text-gray-700">
        If you have any questions or feedback, feel free to <a href="{{ url('/contact-us') }}" class="text-blue-600 hover:text-blue-800">contact us</a>.
    </p>
</div>


@endsection