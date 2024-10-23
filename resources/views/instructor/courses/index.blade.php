@extends('layouts.app')
@section('content')
<x-wrapper>
    <input type="hidden" id="availability-route" value="{{ route('instructor.courses.index') }}">
    <x-table id="courseTable">
    <th scope="col"><div>{{ __('Name') }}</div></th>
    <th scope="col"><div>{{ __('Image') }}</div></th>
    <th scope="col"><div>{{ __('lectures') }}</div></th>
    <th scope="col"><div>{{ __('hours') }}</div></th>
    <th scope="col"><div>{{ __('days') }}</div></th>
    <th scope="col"><div>{{ __('time') }}</div></th>
</x-table>
</x-wrapper>
@endsection
@push('script')
    <script src="{{ asset('public/instructor/js/chapter.js') }}"></script>

@endpush
