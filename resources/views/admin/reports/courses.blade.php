@extends('layouts.app')
@section('content')
<x-wrapper title="">
    <input type="hidden" id="course-route" value="{{route('admin.reports.courses')}}">
    <div class="table-responsive zTable-responsive">
        <table class="table zTable" id="courseTable">
            <thead>
            <tr>
                <th scope="col"><div>{{ __('Name') }}</div></th>
                <th scope="col"><div>{{ __('Semester') }}</div></th>
                <th scope="col"><div>{{ __('Students') }}</div></th>
                <th scope="col"><div>{{ __('Instructor') }}</div></th>
                <th scope="col"><div>{{ __('Status') }}</div></th>
            </tr>
            </thead>
        </table>
    </div>
</x-wrapper>
@endsection
@push('script')
    <script src="{{asset('assets/admin/js/course_report.js')}}"></script>
@endpush
