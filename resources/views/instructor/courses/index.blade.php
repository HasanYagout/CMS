@extends('layouts.app')
@section('content')
    <x-wrapper title="Courses">
        <input type="hidden" id="course-route" value="{{ route('instructor.courses.index') }}">
        <x-table id="courseTable">
            <th scope="col">
                <div>{{ __('Name') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Image') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('lectures') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('hours') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('days') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('time') }}</div>
            </th>
        </x-table>
    </x-wrapper>
@endsection
@push('script')
    <script src="{{ asset('assets/Instructor/js/course.js') }}"></script>
@endpush
