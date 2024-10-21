@extends('layouts.app')

@section('content')
    <div class="p-30" >
        <div class="">
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
    <form action="{{route('admin.availability.store')}}" method="POST">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-lg-4">

                            <label for="instructor" class="form-label">{{__('Instructor')}} <span class="text-danger">*</span></label>

                            <select name="instructor_id" class="form-control" id="body" spellcheck="false">
                                <option value="" selected></option>
                                @foreach($instructors as $instructor)
                                    <option value="{{$instructor->id}}">{{$instructor->first_name .' '. $instructor->last_name}}</option>
                                @endforeach
                            </select>

                </div>
                <div class="col-lg-4">

                                <label for="days" class="form-label">Days</label>
                                <select name="days[]" class="instructor-select select2" id="days" multiple="multiple">
                                    <option value="sat">Sat</option>
                                    <option value="sun">Sun</option>
                                    <option value="mon">Mon</option>
                                    <option value="tue">Tue</option>
                                    <option value="wed">Wed</option>
                                    <option value="thu">Thu</option>
                                </select>

                </div>
                <div class="col-lg-4">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input name="start_time" class="form-control" type="time">
                </div>
                <div class="col-lg-4">
                    <label for="end_time" class="form-label">End Time</label>
                    <input name="end_time" class="form-control" type="time">
                </div>

            </div>
        </div>
        <button class="d-flex justify-content-center align-items-center border-0 fs-15 fw-500 lh-25 text-1b1c17 p-13 bd-ra-12 bg-cdef84 hover-bg-one" type="submit">Save</button>
    </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('.instructor-select').select2({
                allowClear: true
            });
        });
    </script>
@endpush
