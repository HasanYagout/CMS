@extends('layouts.app')
@section('content')
    <div class="p-30">
        <section class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <h1 class="text-primary-color mb-2">Add Assignments</h1>
            <form method="POST" action="{{route('instructor.courses.assignments.store')}}">
                @csrf
                <div class="row gy-4">
                    <div class="primary-form-group col-lg-4">
                        <div class="primary-form-group-wrap">
                            <label for="course" class="form-label text-secondary-color fw-bold">{{__('Course')}} <span
                                    class="text-danger">*</span></label>
                            <select name="course_id" class="primary-form-control" id="course">
                                <option value=""></option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->course->id }}">{{$course->course->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="primary-form-group col-lg-4">
                        <div class="primary-form-group-wrap">
                            <label for="chapter" class="form-label text-secondary-color fw-bold">{{__('hapter')}} <span
                                    class="text-danger">*</span></label>
                            <select class="primary-form-control" name="chapter_id" id="chapter">
                                <option value="">Select Chapter</option>
                            </select>
                        </div>
                    </div>
                    <div class="primary-form-group col-lg-4">
                        <div class="primary-form-group-wrap">
                            <label for="lecture" class="form-label text-secondary-color fw-bold">{{ __('Lecture') }}
                                <span class="text-danger">*</span></label>
                            <select class="primary-form-control" name="lecture_id" id="lecture">
                                <option value="">Select Lecture</option>
                            </select>
                        </div>
                    </div>
                    <div class="primary-form-group col-lg-4">
                        <div class="primary-form-group-wrap">
                            <label for="title" class="form-label text-secondary-color fw-bold">{{__('Title')}} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="primary-form-control">
                        </div>
                    </div>
                    <div class="primary-form-group col-lg-4">
                        <div class="primary-form-group-wrap">
                            <label for="grade" class="form-label text-secondary-color fw-bold">{{__('Grade')}} <span
                                    class="text-danger">*</span></label>
                            <input name="grade" type="number" id="grade" class="primary-form-control">
                        </div>
                    </div>
                    <div class="primary-form-group col-lg-4">
                        <div class="primary-form-group-wrap">
                            <label for="due_date" class="form-label text-secondary-color fw-bold">{{__('Due Date')}}
                                <span class="text-danger">*</span></label>
                            <input name="due_date" type="date" id="due_date" class="primary-form-control">
                        </div>
                    </div>
                    <div class="primary-form-group col-lg-12">
                        <div class="primary-form-group-wrap">
                            <label for="description"
                                   class="form-label text-secondary-color fw-bold">{{__('Description')}} <span
                                    class="text-danger">*</span></label>
                            <textarea name="description"
                                      class="primary-form-control min-h-180 summernoteOne"></textarea>
                        </div>
                    </div>
                </div>

                <button class="zBtn-one mt-2">Submit</button>
            </form>
        </section>
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30 mt-5">
            {{--            <input type="hidden" id="job-post-update-route" value="{{ route('admin.jobs.update.status',':id') }}">--}}
            <input type="hidden" id="assignments-route" value="{{ route('instructor.courses.assignments.index') }}">
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-16">
            </div>

            <div class="table-responsive zTable-responsive">
                <table class="table zTable" id="assignmentsTable">
                    <thead>
                    <tr>
                        <th scope="col">
                            <div>{{ __('course') }}</div>
                        </th>
                        <th scope="col">
                            <div>{{ __('chapter') }}</div>
                        </th>
                        <th scope="col">
                            <div>{{ __('lecture') }}</div>
                        </th>
                        <th scope="col">
                            <div>{{ __('title') }}</div>
                        </th>
                        <th scope="col">
                            <div>{{ __('due date') }}</div>
                        </th>
                        <th scope="col">
                            <div class="text-center">{{ __('Status') }}</div>
                        </th>
                        <th class="w-110 text-center" scope="col">
                            <div>{{ __('Action') }}</div>
                        </th>
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
        $(document).on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapters ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{route('instructor.courses.assignments.updateStatus','')}}/${chapterId}`
            $.ajax({
                url: url, // Update with your actual route
                type: 'POST',
                data: {
                    status: status,
                    _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                success: function (response) {
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    // Optionally, handle error response
                    console.error('Error updating status:', xhr);
                }
            });
        });

        $(document).ready(function () {
            const course = $('#course');
            const chapter = $('#chapter');
            const lecture = $('#lecture');

            // Fetch chapters when a course is selected
            course.on('change', function () {
                const courseId = $(this).val();
                const url = `{{ route('instructor.courses.chapters.getChapters', '') }}/${courseId}`;
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        chapter.empty(); // Clear existing options
                        chapter.append('<option value="">Select Chapter</option>'); // Add default option
                        data.forEach(function (item) {
                            chapter.append('<option value="' + item.id + '">' + item.title + '</option>'); // Append options correctly
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching chapters:', error); // Handle errors
                    }
                });
            });

            // Fetch lectures when a chapter is selected
            chapter.on('change', function () {
                const chapterId = $(this).val();
                const url = `{{ route('instructor.courses.lectures.get', '') }}/${chapterId}`;
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        lecture.empty(); // Clear existing options
                        lecture.append('<option value="">Select Lecture</option>'); // Add default option
                        data.forEach(function (item) {
                            lecture.append('<option value="' + item.id + '">' + item.title + '</option>'); // Append options correctly
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching lectures:', error); // Handle errors
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('assets/instructor/js/instructorassignments.js') }}"></script>

@endpush
