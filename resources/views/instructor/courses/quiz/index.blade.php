@extends('layouts.app')
@section('content')
    <x-wrapper title="Quiz Submission">
        <form action="{{ route('instructor.courses.quiz.store') }}" method="POST" id="quizForm"
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
                        <label for="grade">Grade:</label>
                        <input type="number" class="form-control" id="grade" name="grade" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="due_date">Due Date:</label>
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
                <div class="col-lg-8">
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" class="primary-form-control min-h-180 summernoteOne"
                                  id="courseDescription" placeholder="{{ __('Description') }}"
                                  spellcheck="false">{{ $course->description }}</textarea>
                    </div>
                </div>
            </div>

            <div id="questionsContainer" class="row">
                <div class="col-lg-6 question-group">
                    <h5 class="text-secondary-color">Question 1</h5>
                    <div class="form-group">
                        <label for="question1Text">Question Text:</label>
                        <input type="text" class="form-control" name="questions[0][text]" required>
                    </div>
                    <div class="d-flex flex-column gap-3 options-container">
                        <label>Options:</label>
                        <input type="text" class="form-control" name="questions[0][options][]" placeholder="Option 1">
                        <input type="text" class="form-control" name="questions[0][options][]" placeholder="Option 2">
                        <input type="text" class="form-control" name="questions[0][options][]" placeholder="Option 3">
                        <input type="text" class="form-control" name="questions[0][options][]" placeholder="Option 4">
                    </div>
                    <div class="form-group">
                        <label for="correctAnswer">Correct Answer:</label>
                        <select class="form-control" name="questions[0][correct_answer]" required>
                            <option value="">Select Correct Answer</option>
                            <option value="Option 1">Option 1</option>
                            <option value="Option 2">Option 2</option>
                            <option value="Option 3">Option 3</option>
                            <option value="Option 4">Option 4</option>
                        </select>
                    </div>
                </div>
            </div>

            <section class="mb-18">
                <button type="button" class="zBtn-two" id="addQuestion">Add Another Question</button>
                <button type="submit" class="zBtn-one">Submit Quiz</button>
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
                <div class="text-center">{{ __('Status') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Action') }}</div>
            </th>
        </x-table>
    </x-wrapper>

    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            function updateQuestionNumbers() {
                $('.question-group').each(function (index) {
                    const questionNumber = index + 1;
                    $(this).find('h5').text(`Question ${questionNumber}`);
                    $(this).find('input[name^="questions"]').each(function () {
                        const name = $(this).attr('name');
                        const newName = name.replace(/[\d+]/, `[${index}]`);
                        $(this).attr('name', newName);
                    });
                    $(this).find('select[name^="questions"]').each(function () {
                        const name = $(this).attr('name');
                        const newName = name.replace(/[\d+]/, `[${index}]`);
                        $(this).attr('name', newName);
                    });
                });
            }

            $(document).on('click', '#addQuestion', function () {
                const questionCount = $('.question-group').length;
                const questionGroup = `
                <div class="col-lg-6 question-group">
                    <h5 class="text-secondary-color">Question ${questionCount + 1}</h5>
                    <div class="form-group">
                        <label for="question${questionCount + 1}Text">Question Text:</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][text]" required>
                    </div>
                    <div class="d-flex flex-column gap-3 options-container">
                        <label>Options:</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][options][]" placeholder="Option 1">
                        <input type="text" class="form-control" name="questions[${questionCount}][options][]" placeholder="Option 2">
                        <input type="text" class="form-control" name="questions[${questionCount}][options][]" placeholder="Option 3">
                        <input type="text" class="form-control" name="questions[${questionCount}][options][]" placeholder="Option 4">
                    </div>
                    <div class="form-group">
                        <label for="correctAnswer">Correct Answer:</label>
                        <select class="form-control" name="questions[${questionCount}][correct_answer]" required>
                            <option value="">Select Correct Answer</option>
                            <option value="Option 1">Option 1</option>
                            <option value="Option 2">Option 2</option>
                            <option value="Option 3">Option 3</option>
                            <option value="Option 4">Option 4</option>
                        </select>
                    </div>
                    <span class="remove-question text-decoration-underline text-third-color">Remove Question</span>
                </div>
            `;
                $('#questionsContainer').append(questionGroup);
            });

            $(document).on('click', '.remove-question', function () {
                $(this).closest('.question-group').remove();
                updateQuestionNumbers();
            });

            $(document).on('change', '#course_id', function () {
                const courseId = $(this).val();
                const url = `{{ route('instructor.courses.chapters.getChapters', '') }}/${courseId}`;
                const chapterSelect = $('#chapter_id');

                if (courseId) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            chapterSelect.empty();
                            $.each(data, function (index, chapter) {
                                chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                            });
                        },
                        error: function (xhr) {
                            console.error('Error fetching chapters:', xhr);
                        }
                    });
                } else {
                    chapterSelect.empty().append('<option value="">Select Chapter</option>');
                }
            });

            $(document).on('change', '#chapter_id', function () {
                const chapterId = $(this).val();
                const url = `{{ route('instructor.courses.lectures.get', '') }}/${chapterId}`;
                const lectureSelect = $('#lecture_id');

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        lectureSelect.empty();
                        $.each(data, function (index, lecture) {
                            lectureSelect.append(`<option value="${lecture.id}">${lecture.title}</option>`);
                        });
                    }
                });
            });
        });
    </script>
    <script src="{{asset('assets/Instructor/js/quiz.js')}}"></script>
@endpush
