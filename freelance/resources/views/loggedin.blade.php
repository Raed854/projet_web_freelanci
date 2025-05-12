@extends('layouts.app')
@section('content')
<div class="container">
    <h1>hello {{Auth::user()->role . " " . Auth::user()->prenom . " " . Auth::user()->nom}}</h1>
</div>
@endsection