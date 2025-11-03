<aside class="w-64 bg-white p-6 shadow-lg">
    <ul class="space-y-4">
        <li>
            <a href="{{ url('/projects') }}" class="text-lg font-semibold text-gray-800 hover:text-blue-600">Projects</a>
        </li>
        <li>
            <a href="{{ url('/favorites') }}" class="text-lg font-semibold text-gray-800 hover:text-blue-600">Favorites</a>
        </li>
        @if (Auth::user()->is_admin)
        <li>
            <a href="{{ url('/users') }}" class="text-lg font-semibold text-gray-800 hover:text-blue-600">Users</a>
        </li>
        @endif

        <!-- Styled divider -->
        <li class="my-4 border-t border-gray-300"></li>

        @foreach ($projects as $project)
        <li>
            <a href="{{ url('/projects/' . $project->id) }}" class="block bg-white rounded-lg shadow-md p-2 hover:bg-gray-100">
                {{ $project->name }}
            </a>
        </li>
        @endforeach
    </ul>
</aside>
