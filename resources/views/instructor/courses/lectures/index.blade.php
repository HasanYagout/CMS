@extends('layouts.app')

@section('content')
    <x-wrapper title="Add Lectures">
        <form method="POST" action="{{ route('instructor.courses.lectures.store') }}" class="row g-3"
              enctype="multipart/form-data">
            @csrf
            <div class="col-lg-4">
                <label class="form-label" for="course">Course</label>
                <select class="form-control" name="course_id" id="course">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->course_id }}">{{ $course->course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label" for="chapter">Chapter</label>
                <select class="form-control" name="chapter_id" id="chapters">
                    <option value="">-- Select Chapter --</option>
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label" for="title">Title</label>
                <input class="form-control" name="title" type="text">
            </div>

            <div class="col-12">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" class="summernoteOne form-control"></textarea>
            </div>

            <div class="col-lg-4">
                <label class="form-label" for="materials">Materials</label>
                <input type="file" name="images[]"
                       accept=".png,.jpg,.svg,.jpeg,.gif,.mp4,.mov,.avi,.mkv,.webm,.flv,.pdf,.docx,.pptx" multiple
                       class="form-control"/>
            </div>

            <div class="col-lg-4">
                <label class="form-label" for="zoom_link">Zoom Link</label>
                <input name="zoom_link" class="form-control" type="text">
            </div>

            <div class="col-12">
                <button class="zBtn-one mt-3" type="submit">Save</button>
            </div>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="lecture-route" value="{{route('instructor.courses.lectures.index')}}">
        <x-table id="lecturesTable">
            <th scope="col">
                <div>{{ __('Course') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Chapter') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Lecture') }}</div>
            </th>
            
            <th scope="col">
                <div class="text-center">{{ __('Status') }}</div>
            </th>
            <th class="w-110 text-center" scope="col">
                <div>{{ __('Action') }}</div>
            </th>
        </x-table>
    </x-wrapper>
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content"></div>
        </div>
    </div>
    <div id="search-section">
        <div class="collapse" id="collapseExample">
            <div class="alumniFilter">
                <h4 class="fs-18 fw-500 lh-38 text-1b1c17 pb-10">{{ __('Filter your search') }}</h4>
                <div class="filterOptions">
                    <div class="item">
                        <div class="primary-form-group mb-3">
                            <div class="primary-form-group-wrap">
                                <label for="course" class="form-label">Filter By Course</label>
                                <select name="course_id" id="course_filter" class="primary-form-control"
                                        spellcheck="false">
                                    <option value="" selected></option>
                                    @foreach($courses as $course)
                                        <option
                                            value="{{ $course->course->id }}">{{ $course->course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button
                        class="bg-cdef84 border-0 bd-ra-12 py-13 px-26 fs-15 fw-500 lh-25 text-black hover-bg-one advance-filter">{{ __('Search Now') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).off().on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapters ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{route('instructor.courses.lectures.updateStatus','')}}/${chapterId}`
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#course').on('change', function () {
                const courseId = $(this).val();
                const chapterSelect = $('#chapters');
                chapterSelect.empty().append('<option value="">-- Select Chapter --</option>');

                if (courseId) {
                    $.ajax({
                        url: `{{ route('instructor.courses.chapters.get', '') }}/${courseId}`,
                        method: 'GET',
                        success: function (data) {
                            // Log entire data to see its structure
                            console.log('Response Data:', data);

                            // Log individual parts of data to verify
                            console.log('Chapters:', data.chapters);
                            console.log('Availabilities:', data.availabilities);

                            // Ensure data exists before proceeding
                            if (data.chapters && data.availabilities) {
                                $.each(data.chapters, function (index, chapter) {
                                    chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                                });

                                if (data.availabilities.length > 0) {
                                    const lastLectureDate = new Date(data.lastLecture ? data.lastLecture.start_date : data.course.start_date);
                                    const availability = data.availabilities[0];

                                    // Calculate next available date based on availabilities
                                    const days = JSON.parse(availability.days);
                                    const startTime = availability.start_time;
                                    const endTime = availability.end_time;

                                    const dayMap = {
                                        "Sunday": 0,
                                        "Monday": 1,
                                        "Tuesday": 2,
                                        "Wednesday": 3,
                                        "Thursday": 4,
                                        "Friday": 5,
                                        "Saturday": 6
                                    };

                                    const availableDays = days.map(day => dayMap[day]);

                                    do {
                                        lastLectureDate.setDate(lastLectureDate.getDate() + 1);
                                    } while (!availableDays.includes(lastLectureDate.getDay()));

                                    const [startHours, startMinutes] = startTime.split(':');
                                    lastLectureDate.setHours(startHours);
                                    lastLectureDate.setMinutes(startMinutes);

                                    const startFormattedDate = formatDate(lastLectureDate);
                                    $('input[name="start_date"]').val(startFormattedDate);

                                    const lectureEndDate = new Date(lastLectureDate);
                                    const [endHours, endMinutes] = endTime.split(':');
                                    lectureEndDate.setHours(endHours);
                                    lectureEndDate.setMinutes(endMinutes);

                                    const endFormattedDate = formatDate(lectureEndDate);
                                    $('input[name="end_date"]').val(endFormattedDate);
                                }
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching course details:', error);
                            alert('Failed to load chapters. Please try again.');
                        }
                    });
                }
            });

            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            }
        });
    </script>
    <script src="{{asset('assets/Instructor/js/lectures.js')}}"></script>
@endpush
