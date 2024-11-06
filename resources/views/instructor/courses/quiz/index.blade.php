@extends('layouts.app')
@section('content')
    <x-wrapper title="Quiz Submission">


        <form action="{{route('instructor.courses.quiz.store')}}" method="POST" id="quizForm"
              class="d-flex flex-column gap-5">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="title">Quiz Title:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="duration">Quiz Duration (in minutes):</label>
                        <input type="number" class="form-control" id="duration" name="duration" value="60" required>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="duration">Grade:</label>
                        <input type="number" class="form-control" id="grade" name="grade" required>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="duration">Due Date:</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="course_id">Course:</label>
                        <select class="form-control" id="course_id" name="course_id" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->course->id }}">{{ $course->course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="chapter_id">Chapter:</label>
                        <select class="form-control" id="chapter_id" name="chapter_id" required>
                            <option value="">Select Chapter</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="lecture_id">Lecture:</label>
                        <select class="form-control" id="lecture_id" name="lecture_id" required>
                            <option value="">Select Lecture</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="questionsContainer" class="gap-3 row">
                <div class="gap-2 gy-3 question-group row">
                    <h5 class="text-secondary-color">Question 1</h5>
                    <div class="col-lg-4 d-flex flex-column form-group gap-2">
                        <label for="question1Text">Question Text:</label>
                        <input type="text" class="form-control" name="questions[0][text]" required>
                    </div>
                    {{--                    <div class="col-lg-4 d-flex flex-column form-group gap-2">--}}
                    {{--                        <label>Answer Type:</label>--}}
                    {{--                        <select class="form-control question-type" name="questions[0][type]">--}}
                    {{--                            <option value="mcq">Multiple Choice</option>--}}
                    {{--                            <option value="essay">Essay</option>--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}
                    <div class="options-container row">
                        <div class="col-lg-4 d-flex flex-column form-group gap-2">
                            <label>Choices (separate by commas):</label>
                            <input type="text" class="form-control col-lg-6" name="questions[0][options]"
                                   placeholder="Option 1, Option 2">
                        </div>
                        <div class="col-lg-4 d-flex flex-column form-group gap-2">
                            <label for="correctAnswer">Correct Answer:</label>
                            <input type="text" class="form-control" name="questions[0][correct_answer]"
                                   placeholder="Enter correct answer">
                        </div>

                    </div>
                    <span class="remove-question text-decoration-underline text-third-color"
                          onclick="$(this).closest('.question-group').remove()">Remove Question</span>
                </div>
            </div>
            <section class="mb-18">
                <button type="button" class="zBtn-two" id="addQuestion">Add Another Question</button>

                <button type="submit" class=" zBtn-one">Submit Quiz</button>
            </section>

        </form>
        <input type="hidden" id="quiz-route" value="{{ route('instructor.courses.quiz.index') }}">
        <x-table id="quizTable">
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
            const url = `{{route('instructor.courses.quiz.updateStatus','')}}/${chapterId}`
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
            $(document).on('change', '#course_id', function () {
                const courseId = $(this).val();
                const url = `{{route('instructor.courses.chapters.getChapters','')}}/${courseId}`;
                const chapterSelect = $('#chapter_id');

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        chapterSelect.empty();
                        $.each(data, function (index, chapter) {
                            chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                        });
                    }
                });
            });

            $(document).on('change', '#chapter_id', function () {
                const chapterId = $(this).val();
                const url = `{{route('instructor.courses.lectures.get','')}}/${chapterId}`;
                const lectureSelect = $('#lecture_id');

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        lectureSelect.empty();
                        $.each(data, function (index, chapter) {
                            lectureSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                        });
                    }
                });
            });

            let questionCount = 1;

            // Ensure handler is only attached once
            $(document).off('click', '#addQuestion').on('click', '#addQuestion', function () {
                questionCount++;
                const questionGroup = `
            <div class="gap-2 gy-3 question-group row">
                <h5 class="text-secondary-color">Question ${questionCount}</h5>
                <div class="col-lg-4 d-flex flex-column form-group gap-2">
                    <label for="question${questionCount}Text">Question Text:</label>
                    <input type="text" class="form-control" name="questions[${questionCount - 1}][text]" required>
                </div>

                <div class="options-container row">
                    <div class="col-lg-4 d-flex flex-column form-group gap-2">
                        <label>Choices (separate by commas):</label>
                        <input type="text" class="form-control col-lg-6" name="questions[${questionCount - 1}][options]" placeholder="Option 1, Option 2">
                    </div>
                    <div class="col-lg-4 d-flex flex-column form-group gap-2">
                        <label for="correctAnswer">Correct Answer:</label>
                        <input type="text" class="form-control col-lg-6" name="questions[${questionCount - 1}][correct_answer]" placeholder="Enter correct answer">
                    </div>
                </div>
                <span class="remove-question text-decoration-underline text-third-color" onclick="$(this).closest('.question-group').remove()">Remove Question</span>
            </div>
        `;
                $('#questionsContainer').append(questionGroup);
            });
        });

    </script>
    <script src="{{ asset('assets/instructor/js/quiz.js') }}"></script>

@endpush
