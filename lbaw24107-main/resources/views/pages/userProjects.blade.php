@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    @if (Auth::user()->is_admin)
        <h1 class="text-3xl font-semibold text-gray-900 mb-6">Projects of {{ $user->username }}</h1>
        
        @if ($user->projects->isEmpty())
            <p class="text-gray-600">This user is not a member of any projects.</p>
        @else
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200">Project Name</th>
                        <th class="py-2 px-4 border-b border-gray-200">Role</th>
                        <th class="py-2 px-4 border-b border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->projects as $project)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $project->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $project->pivot->role }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="{{ route('projects.edit', $project->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">Edit</a>
                                <form action="{{ route('projects.archive', $project->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Archive</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p class="text-gray-600">You do not have permission to view this page.</p>
    @endif
</div>
@endsection
