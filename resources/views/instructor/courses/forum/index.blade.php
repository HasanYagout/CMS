@extends('layouts.app')

@section('content')
    <x-wrapper title="Create Forum">
        <form method="POST" action="{{route('instructor.courses.forums.store')}}">
            @csrf
            <label class="form-label" for="course">Course</label>
            <select class="form-control" name="course_id">
                <option value="" selected disabled>Select a Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
            <label class="form-label" for="title">Title</label>
            <input class="form-control" name="title" type="text" required>
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control" name="description" rows="5" required></textarea>
            <button class="btn btn-primary mt-3" type="submit">Save</button>
        </form>
    </x-wrapper>
@endsection
