@extends('layouts.app')
@section('content')
    <div class="p-30">
        <section class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <h1 class="text-primary-color">Add Assignments</h1>
            <form method="POST" action="{{route('admin.courses.assignments.store')}}">
                @csrf
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="course" class="form-label text-secondary-color fw-bold">{{__('Course')}} <span
                                class="text-danger">*</span></label>
                        <select name="course_id" class="primary-form-control" id="course">
                            <option value=""></option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{$course->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="chapter" class="form-label text-secondary-color fw-bold">{{__('hapter')}} <span
                                class="text-danger">*</span></label>
                        <select class="primary-form-control" name="chapter_id" id="chapter">
                            <option value="">Select Chapter</option>
                        </select>
                    </div>
                </div>
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="title" class="form-label text-secondary-color fw-bold">{{__('Title')}} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="primary-form-control">
                    </div>
                </div>
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="description" class="form-label text-secondary-color fw-bold">{{__('Description')}}
                            <span class="text-danger">*</span></label>
                        <textarea name="description" class="primary-form-control min-h-180 summernoteOne"></textarea>
                    </div>
                </div>
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="marks" class="form-label text-secondary-color fw-bold">{{__('Marks')}} <span
                                class="text-danger">*</span></label>
                        <input name="marks" type="number" id="marks" class="primary-form-control">
                    </div>
                </div>
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="due_date" class="form-label text-secondary-color fw-bold">{{__('Due Date')}} <span
                                class="text-danger">*</span></label>
                        <input name="due_date" type="date" id="due_date" class="primary-form-control">
                    </div>
                </div>
                <button class="zBtn-one">Submit</button>
            </form>
        </section>
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            {{--            <input type="hidden" id="job-post-update-route" value="{{ route('admin.jobs.update.status',':id') }}">--}}
            <input type="hidden" id="assignments-route" value="{{ route('admin.courses.assignments.index') }}">
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-16">
                {{--                <h4 class="fs-24 fw-500 lh-34 text-black">{{$title}}</h4>--}}
            </div>

            <div class="table-responsive zTable-responsive">
                <table class="table zTable" id="assignmentsTable">
                    <thead>
                    <tr>
                        <th scope="col">
                            <div>{{ __('Name') }}</div>
                        </th>
                        <th scope="col">
                            <div>{{ __('course') }}</div>
                        </th>
                        <th scope="col">
                            <div>{{ __('chapters') }}</div>
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
        $(document).ready(function () {
            const course = $('#course');
            const chapter = $('#chapters');

            course.on('change', function () {
                const courseId = $(this).val();
                console.log(courseId);
                const url = `{{ route('admin.courses.chapters.get', '') }}/${courseId}`;
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
        });
    </script>
    <script src="{{ asset('assets/admin/js/assignments.js') }}"></script>

@endpush
