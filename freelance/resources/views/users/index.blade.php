@extends('layouts.app')



@section('content')
<link rel="stylesheet" href="{{ asset('css/affichageusers.css') }}">

<div class="users-container">

    <div class="page-header">
        <h1 class="page-title">Users</h1>
       
    </div>
    
    <div class="table-responsive">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->nom }}</td>
                        <td>{{ $user->prenom }}</td>
                        <td>
                            <span class="role-badge">{{ $user->role }}</span>
                        </td>
                       <td>
                        <div class="action-buttons">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
                            
                            <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $user->is_active ? 'btn-secondary' : 'btn-success' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection