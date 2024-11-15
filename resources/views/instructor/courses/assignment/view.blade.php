@extends('layouts.app')
@section('content')
    <x-wrapper title="Submitted Assignments">
        <input type="hidden" id="assignments-route" value="{{route('instructor.courses.assignments.view',$course_id)}}">
        <x-table id="assignmentsTable">
            <th scope="col">
                <div class="text-center">{{ __('Name') }}</div>
            </th>
            <th scope="col">
                <div class="text-center">{{ __('Assignment') }}</div>
            </th>
            <th scope="col">
                <div class="text-center">{{ __('Action') }}</div>
            </th>
        </x-table>
    </x-wrapper>
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('assets/Instructor/js/submittedAssignments.js')}}"></script>
@endpush
