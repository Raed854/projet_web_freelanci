@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/editusers.css') }}" rel="stylesheet">

<div class="container">
    <h1>Edit User</h1>
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder=" " required>
            <label for="email">Email</label>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group password-field">
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder=" ">
            <label for="password">Password</label>
            <button type="button" class="password-toggle">👁</button>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group password-field">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder=" ">
            <label for="password_confirmation">Confirm Password</label>
            <button type="button" class="password-toggle">👁</button>
        </div>

        <div class="form-row">
            <div class="form-group">
                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $user->nom) }}" placeholder=" " required>
                <label for="nom">Nom</label>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" name="prenom" id="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom', $user->prenom) }}" placeholder=" " required>
                <label for="prenom">Prenom</label>
                @error('prenom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="docteur" {{ $user->role == 'docteur' ? 'selected' : '' }}>docteur</option>
                <option value="patient" {{ $user->role == 'patient' ? 'selected' : '' }}>patient</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <label for="role">Role</label>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="file" name="photo" id="photo" class="form-control-file @error('photo') is-invalid @enderror">
            <label for="photo">Upload New Photo</label>
            @error('photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" style="max-width: 150px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary btn-success-animation">Update User</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user-edit.js') }}"></script>
@endsection
