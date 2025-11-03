@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12">
    <!-- Contact Information Heading -->
    <h2 class="text-3xl md:text-4xl font-semibold text-gray-900 mb-4">
        Our Contact Information
    </h2>
    <p class="text-lg md:text-xl text-gray-700 mb-8">
        You can reach us through the following channels:
    </p>

    <!-- Contact Info List -->
    <ul class="space-y-4 text-gray-600">
        <li class="flex items-center">
            <strong class="mr-2 text-gray-900">Email:</strong>
            <span>support@projectFlow.com</span>
        </li>
        <li class="flex items-center">
            <strong class="mr-2 text-gray-900">Phone:</strong>
            <span>+1 (555) 123-4567</span>
        </li>
        <li class="flex items-center">
            <strong class="mr-2 text-gray-900">Address:</strong>
            <span>123 Project Management St, Suite 100, City, Country</span>
        </li>
    </ul>

    <!-- Social Media Section -->
    <h3 class="text-2xl md:text-3xl font-semibold text-gray-900 mt-12 mb-6">
        Follow Us
    </h3>
    <ul class="flex space-x-6">
        <!-- Facebook Link -->
        <li>
            <a href="https://facebook.com/projectFlow" target="_blank" class="flex items-center text-gray-700 hover:text-blue-600" >
                <i class="fa-brands fa-square-facebook text-xl mr-2"></i>
                Facebook
            </a>
        </li>
        <!-- Twitter Link -->
        <li>
            <a href="https://twitter.com/projectFlow" target="_blank" class="flex items-center text-gray-700 hover:text-blue-400">
                <i class="fa-brands fa-square-twitter text-xl mr-2"></i>
                Twitter
            </a>
        </li>
        <!-- LinkedIn Link -->
        <li>
            <a href="https://linkedin.com/company/projectFlow" target="_blank" class="flex items-center text-gray-700 hover:text-blue-700">
                <i class="fa-brands fa-linkedin text-xl mr-2"></i>
                LinkedIn
            </a>
        </li>
    </ul>
</div>

@endsection
