@extends('layouts.app')

@section('content')

    <div class="p-30" >
        <div class="">
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <form action="{{route('admin.courses.store')}}" >
                    @csrf
                    <div class="max-w-840">
                        <div class="pb-30"></div>
                        <div class="row rg-25">
                            <div class="col-12">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                        <label for="title" class="form-label">{{__('Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" class="primary-form-control" id="title" name="name" placeholder="{{ __('Name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                        <label for="instructor" class="form-label">{{__('Instructor')}} <span class="text-danger">*</span></label>

                                        <select name="instructor_id" id="instructor" class="primary-form-control " id="body" spellcheck="false">
                                            <option value="" selected></option>
                                            @foreach($instructors as $instructor)
                                                <option value="{{$instructor->id}}">{{$instructor->first_name .' '. $instructor->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                        <label for="availability" class="form-label">{{__('Availability')}} <span class="text-danger">*</span></label>
                                        <select name="availability_id" class="primary-form-control " id="availability" spellcheck="false">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                        <label for="description" class="form-label">{{__('Description')}} <span class="text-danger">*</span></label>
                                        <textarea name="description" class="primary-form-control summernoteOne min-h-180" id="description" placeholder="Details" spellcheck="false"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                        <label for="instructor" class="form-label">{{__('Semester')}} <span class="text-danger">*</span></label>

                                        <select name="semester_id" id="semester" class="primary-form-control " spellcheck="false">
                                            <option value="" selected></option>
                                            @foreach($semesters as $semester)
                                                <option value="{{$semester->id}}">{{$semester->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <button type="submit" class="d-inline-flex py-13 px-26 bd-ra-12 bg-cdef84 fs-15 fw-500 lh-25 text-black mt-30 hover-bg-one border-0">{{__('Publish Now')}}</button>                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#instructor').on('change', function() {
                var instructorId = $(this).val(); // Get selected instructor ID
                console.log(instructorId);
                if (instructorId) {
                    var url = "{{ route('admin.availability.getAvailabilityByInstructor')}}";
                    $.ajax({
                        url: url, // Adjust the URL as necessary
                        type: 'GET',
                        data: {
                            instructorId: instructorId
                        },
                        success: function(data) {
                            $('#availability').empty(); // Clear the availability dropdown

                            if (data.length > 0) {
                                // Populate availability dropdown with options
                                $.each(data, function(index, availability) {
                                    // Decode the JSON days
                                    var days = JSON.parse(availability.days);
                                    // Format the days as a string
                                    var daysString = days.join(', '); // Join days with a comma, adjust as necessary

                                    $('#availability').append($('<option></option>')
                                        .attr('value', availability.id)
                                        .text(daysString + ' ( ' + availability.start_time + ' - ' + availability.end_time + ' )')
                                    );
                                });
                            } else {
                                $('#availability').append('<option value="">No availability found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ', status, error);
                        }
                    });
                } else {
                    $('#availability').empty().append('<option value="">Select availability</option>'); // Reset the dropdown if no instructor is selected
                }
            });
        });
    </script>
@endpush
