<header class="bg-white shadow-sm">
    <nav class="container mx-auto flex justify-between items-center py-4 px-6">
        <!-- Logo Section -->
        <div class="logo">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="ProjectFlow Logo" class="h-8">
            </a>
        </div>

        <!-- Menu Section -->
        <div class="menu flex items-center space-x-6">
            @if(Request::is('/') || Request::is('about-us') || Request::is('contact-us'))
                <a href="{{ url('/login') }}" class="text-gray-700 hover:text-gray-900 font-medium">Log In</a>
                <a href="{{ url('/about-us') }}" class="text-gray-700 hover:text-gray-900 font-medium border-2 border-indigo-600 px-4 py-2 rounded-full transition">
                    About Us
                </a>
                <a href="{{ url('/contact-us') }}" class="text-gray-700 hover:text-gray-900 font-medium border-2 border-indigo-600 px-4 py-2 rounded-full transition">
                    Contact Us
                </a>
                <a href="{{ url('/register') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-semibold flex items-center hover:bg-indigo-700 transition">
                    Get Started
                    <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            @elseif(Request::is('login') || Request::is('register') || Request::is('forgot-password') || Request::is('reset-password/*'))
                <!-- Additional menu items can be added here -->
            @else
                <div class="relative">
                    <a href="{{ route('notifications.markAllRead') }}" class="text-gray-700 hover:text-gray-900 text-lg relative">
                        <i class="fa-regular fa-bell"></i>
                    </a>
                    @if($unreadCount > 0)
                        <span class="absolute -top-3 -right-3 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-1">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </div>
                <a href="{{ url('/search') }}" class="text-gray-700 hover:text-gray-900 text-lg">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <a href="{{ url('/profile/' . Auth::user()->id) }}" class="text-gray-700 hover:text-gray-900 font-medium">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-8 h-8 rounded-full">
                    @else
                        <i class="fa-solid fa-user"></i>
                    @endif
                </a>
                <form action="{{ route('logout') }}" method="GET" class="inline">
                    {{ csrf_field() }}
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition">
                        Log Out
                    </button>
                </form>
            @endif
        </div>
    </nav>
</header>
