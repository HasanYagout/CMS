@extends('layouts.app')

@section('content')

    <div class="p-30" >
        <div class="">
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <form method="POST" action="{{route('admin.courses.store')}}" enctype="multipart/form-data" >
                    @csrf
                    <div>
                        <div class="pb-30"></div>
                        <div class="row rg-25">
                            <div class="col-4">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                        <label for="title" class="form-label">{{__('Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" class="primary-form-control" id="title" name="name" placeholder="{{ __('Name') }}">
                                    </div>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap">
                                        <label for="description" class="form-label">{{__('Description')}} <span class="text-danger">*</span></label>
                                        <textarea name="description" class="primary-form-control summernoteOne min-h-180" id="description" placeholder="Details" spellcheck="false"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-4 col-md-6 ">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap zImage-upload-details">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12"><img src="{{asset('public/assets/images/icon/upload-img-1.svg')}}" alt="" /></div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{__('Drag & drop files here')}}</p>
                                        </div>
                                        <label for="zImageUpload" class="form-label">{{__('Upload Image')}} <span class="text-mime-type">(jpg,jpeg,png)</span> <span class="text-danger">*</span></label>
                                        <div class="upload-img-box">
                                            <img src="">
                                            <input type="file" name="image" accept="image/*" onchange="previewFile(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>
{{--                            <div class="col-4">--}}
{{--                                <div class="primary-form-group">--}}
{{--                                    <div class="primary-form-group-wrap">--}}
{{--                                        <label for="availability" class="form-label">{{__('Availability')}} <span class="text-danger">*</span></label>--}}
{{--                                        <select name="availability_id" class="primary-form-control " id="availability" spellcheck="false">--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}


                            <div class="col-4">
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
    <div>
        {{--            <input type="hidden" id="job-post-update-route" value="{{ route('admin.jobs.update.status',':id') }}">--}}
        <input type="hidden" id="course-route" value="{{ route('admin.courses.index') }}">
        <div class="d-flex flex-wrap justify-content-between align-items-center pb-16">
            {{--                <h4 class="fs-24 fw-500 lh-34 text-black">{{$title}}</h4>--}}
        </div>
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <!-- Table -->
            <div class="table-responsive zTable-responsive">
                <table class="table zTable" id="coursesTable">
                    <thead>
                    <tr>

                        <th scope="col"><div>{{ __('Name') }}</div></th>
                        <th scope="col"><div>{{ __('Status') }}</div></th>
                        <th class="w-110 text-center" scope="col"><div>{{ __('Action') }}</div></th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                    </div>
                </div>
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

        <script src="{{ asset('public/admin/js/courses.js') }}"></script>

@endpush
