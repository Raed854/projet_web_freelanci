@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/showcours.css') }}">

<div class="course-details-container">
    <h1 class="course-title">{{ $course->titre }}</h1>
    <p class="course-description">{{ $course->description }}</p>
    <p class="course-content">{{ $course->contenu }}</p>
    <p class="course-status">Status: {{ ucfirst($course->status) }}</p>
    <p class="course-date">Date de publication : {{ $course->created_at->format('d/m/Y') }}</p>

    @if ($course->files)
        <div class="course-files">
            <h3>Files:</h3>
            <ul>
                @foreach (explode(',', $course->files) as $file)
                    <li>
                        @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                            <img src="{{ asset('storage/' . $file) }}" alt="File Image" class="file-image">
                        @else
                            <a href="{{ asset('storage/' . $file) }}" download>
                                <i class="file-icon">📄</i> {{ basename($file) }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('courses.index') }}" class="back-button">Back to Courses</a>
</div>
@endsection