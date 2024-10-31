@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Activity">
        <form action="{{ route('instructor.courses.lectures.activities.store') }}" method="POST" id="activityForm">
            @csrf
            <!-- Course Selection -->
            <div class="form-group">
                <label for="course_id">Course:</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Chapter Selection -->
            <div class="form-group" id="chapter_div">
                <label for="chapter_id">Chapter:</label>
                <select class="form-control" id="chapter_id" name="chapter_id" required disabled>
                    <option value="">Select Chapter</option>
                </select>
            </div>
            <!-- Lecture Selection -->
            <div class="form-group" id="lecture_div">
                <label for="lecture_id">Lecture:</label>
                <select class="form-control" id="lecture_id" name="lecture_id" required disabled>
                    <option value="">Select Lecture</option>
                </select>
            </div>
            <!-- Activity Title -->
            <div class="form-group">
                <label for="activity_title">Activity Title:</label>
                <input type="text" class="form-control" id="activity_title" name="activity_title[]" required>
            </div>
            <!-- Options -->
            <div class="form-group">
                <label for="options">Options (separate by commas):</label>
                <input type="text" class="form-control" id="options" name="options[]" required>
            </div>
            <!-- Correct Answer -->
            <div class="form-group">
                <label for="correct_answer">Correct Answer:</label>
                <input type="text" class="form-control" id="correct_answer" name="correct_answer[]" required>
            </div>
            <div class="form-group">
                <label for="correct_answer">Grade:</label>
                <input type="number" class="form-control" id="grade" name="grade[]" required>
            </div>
            <!-- Container for dynamic questions -->
            <div id="questionsContainer"></div>
            <!-- Buttons -->
            <button type="button" class="btn btn-secondary" id="addActivity">Add Another Activity</button>
            <button type="submit" class="btn btn-primary">Save Activity</button>
        </form>
    </x-wrapper>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const chapterDiv = $('#chapter_div');
            const lectureDiv = $('#lecture_div');
            const chapterSelect = $('#chapter_id');
            const lectureSelect = $('#lecture_id');
            chapterDiv.hide();
            lectureDiv.hide();

            $('#course_id').on('change', function() {
                const courseId = $(this).val();
                const url = `{{ route('instructor.courses.chapters.get', '') }}/${courseId}`;
                if (courseId) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            chapterSelect.empty().append('<option value="">Select Chapter</option>');
                            $.each(data, function(index, chapter) {
                                chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                            });
                            chapterSelect.prop('disabled', false);
                            chapterDiv.show();
                        },
                        error: function(xhr, status, error) {
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

            $('#chapter_id').on('change', function() {
                const chapterId = $(this).val();
                const url = `{{ route('instructor.courses.lectures.get', '') }}/${chapterId}`;
                if (chapterId) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            lectureSelect.empty().append('<option value="">Select Lecture</option>');
                            $.each(data, function(index, lecture) {
                                lectureSelect.append(`<option value="${lecture.id}">${lecture.title}</option>`);
                            });
                            lectureSelect.prop('disabled', false);
                            lectureDiv.show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching lectures:', error);
                        }
                    });
                } else {
                    lectureSelect.empty().append('<option value="">Select Lecture</option>').prop('disabled', true);
                    lectureDiv.hide();
                }
            });

            let activityCount = 0;
            $('#addActivity').on().off().click(function() {
                if (activityCount < 5) {
                    activityCount++;
                    const activityGroup = `
                        <div class="activity-group">
                            <h5>Activity ${activityCount}</h5>
                            <div class="form-group">
                                <label for="activity${activityCount}Text">Activity Title:</label>
                                <input type="text" class="form-control" name="activity_title[]" required>
                            </div>
                            <div class="form-group">
                                <label>Options:</label>
                                <input type="text" class="form-control" name="options[]" placeholder="Option 1, Option 2" required>
                            </div>
                            <div class="form-group">
                                <label for="correctAnswer">Correct Answer:</label>
                                <input type="text" class="form-control" name="correct_answer[]" placeholder="Enter correct answer" required>
                            </div>
                            <div class="form-group">
                                <label for="correctAnswer">Grade:</label>
                                <input type="number" class="form-control" name="grade[]" placeholder="Enter Grade" required>
                            </div>
                            <span class="remove-activity" onclick="$(this).closest('.activity-group').remove()">Remove Activity</span>
                        </div>
                    `;
                    $('#questionsContainer').append(activityGroup);
                }
            });
        });
    </script>
@endpush
