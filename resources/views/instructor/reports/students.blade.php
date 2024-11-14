@extends('layouts.app')
@section('content')
    <x-wrapper title="">
        <input type="hidden" id="student-route" value="{{route('instructor.reports.students',$course_id)}}">
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
                    <th scope="col">
                        <div>{{ __('action') }}</div>
                    </th>

                </tr>
                </thead>
            </table>
        </div>
    </x-wrapper>
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('assets/Instructor/js/students_report.js')}}"></script>
@endpush
