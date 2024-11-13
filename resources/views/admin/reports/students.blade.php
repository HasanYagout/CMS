@extends('layouts.app')
@section('content')
    <x-wrapper title="">
        <input type="hidden" id="student-route" value="{{route('admin.reports.students',$course_id)}}">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="studentTable">
                <thead>
                <tr>
                    <th scope="col">
                        <div>{{ __('Name') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('assignments') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('quizzes') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('activities') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('attendance') }}</div>
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </x-wrapper>
@endsection
@push('script')
    <script src="{{asset('assets/admin/js/students_report.js')}}"></script>
@endpush
