@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Activity">
        <form action="{{ route('instructor.courses.lectures.activities.store') }}" method="POST" id="activityForm">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="course_id">Course:</label>
                        <select class="form-control" id="course_id" name="course_id" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group" id="chapter_div">
                        <label for="chapter_id">Chapter:</label>
                        <select class="form-control" id="chapter_id" name="chapter_id" required disabled>
                            <option value="">Select Chapter</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group" id="lecture_div">
                        <label for="lecture_id">Lecture:</label>
                        <select class="form-control" id="lecture_id" name="lecture_id" required disabled>
                            <option value="">Select Lecture</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="activity_title">Activity Title:</label>
                        <input type="text" class="form-control" id="activity_title" name="activity_title[]" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="options">Options (separate by commas):</label>
                        <input type="text" class="form-control" id="options" name="options[]" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="correct_answer">Correct Answer:</label>
                        <input type="text" class="form-control" id="correct_answer" name="correct_answer[]" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="correct_answer">Grade:</label>
                        <input type="number" class="form-control" id="grade" name="grade[]" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="correct_answer">Due Date:</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                </div>
            </div>

            <!-- Container for dynamic questions -->
            <div id="questionsContainer" class="d-flex flex-column gap-5 mt-30"></div>
            <!-- Buttons -->
            <button type="button" class="zBtn-two" id="addActivity">Add Another Activity</button>
            <button type="submit" class="zBtn-one">Save Activity</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <x-table id="activitiesTable">
            <input type="hidden" id="activity-route" value="{{route('instructor.courses.lectures.activities.index')}}">
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
                <div>{{ __('Title') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Due Date') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Status') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Action') }}</div>
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
    <script>
        $(document).on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapters ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{route('instructor.courses.lectures.activities.updateStatus','')}}/${chapterId}`
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
            const chapterDiv = $('#chapter_div');
            const lectureDiv = $('#lecture_div');
            const chapterSelect = $('#chapter_id');
            const lectureSelect = $('#lecture_id');
            chapterDiv.hide();
            lectureDiv.hide();

            $('#course_id').on('change', function () {
                const courseId = $(this).val();
                const url = `{{ route('instructor.courses.chapters.getChapters', '') }}/${courseId}`;
                if (courseId) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            chapterSelect.empty().append('<option value="">Select Chapter</option>');
                            $.each(data, function (index, chapter) {
                                chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                            });
                            chapterSelect.prop('disabled', false);
                            chapterDiv.show();
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching chapters:', error);
                        }
                    });
                } else {
                    chapterSelect.empty().append('<option value="">Select Chapter</option>').prop('disabled', true);
                    lectureSelect.empty().append('<option value="">Select Lecture</option>').prop('disabled', true);
                    chapterDiv.hide();
                    lectureDiv.hide();
                }
            });

            $('#chapter_id').on('change', function () {
                const chapterId = $(this).val();
                const url = `{{ route('instructor.courses.lectures.get', '') }}/${chapterId}`;
                if (chapterId) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            lectureSelect.empty().append('<option value="">Select Lecture</option>');
                            $.each(data, function (index, lecture) {
                                lectureSelect.append(`<option value="${lecture.id}">${lecture.title}</option>`);
                            });
                            lectureSelect.prop('disabled', false);
                            lectureDiv.show();
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching lectures:', error);
                        }
                    });
                } else {
                    lectureSelect.empty().append('<option value="">Select Lecture</option>').prop('disabled', true);
                    lectureDiv.hide();
                }
            });

            let activityCount = 0;
            $('#addActivity').on().off().click(function () {
                if (activityCount < 5) {
                    activityCount++;
                    const activityGroup = `

                        <div class="activity-group row gap-3">
                            <h5>Activity ${activityCount}</h5>

<div class="col-lg-4">
<div class="form-group">
                                <label for="activity${activityCount}Text">Activity Title:</label>
                                <input type="text" class="form-control" name="activity_title[]" required>
                            </div>
</div>
                            <div class="col-lg-4"><div class="form-group">
                                <label>Options:</label>
                                <input type="text" class="form-control" name="options[]" placeholder="Option 1, Option 2" required>
                            </div></div>
                            <div class="col-lg-4"><div class="form-group">
                                <label for="correctAnswer">Correct Answer:</label>
                                <input type="text" class="form-control" name="correct_answer[]" placeholder="Enter correct answer" required>
                            </div>
</div>
                            <div class="col-lg-4"><div class="form-group">
                                <label for="correctAnswer">Grade:</label>
                                <input type="number" class="form-control" name="grade[]" placeholder="Enter Grade" required>
                            </div></div>






                            <span class="remove-activity text-decoration-underline text-third-color" onclick="$(this).closest('.activity-group').remove()">Remove Activity</span>
                        </div>
                    `;
                    $('#questionsContainer').append(activityGroup);
                }
            });
        });
    </script>
    <script src="{{asset('assets/Instructor/js/activities.js')}}"></script>

@endpush
