@extends('layouts.app')
@section('content')
    <x-wrapper title="Quiz Submission">


        <form action="{{route('instructor.courses.quiz.store')}}" method="POST" id="quizForm">
            @csrf
            <div class="form-group">
                <label for="title">Quiz Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="duration">Quiz Duration (in minutes):</label>
                <input type="number" class="form-control" id="duration" name="duration" value="60" required>
            </div>
            <div class="form-group">
                <label for="course_id">Course:</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->course->id }}">{{ $course->course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="chapter_id">Chapter:</label>
                <select class="form-control" id="chapter_id" name="chapter_id" required>
                    <option value="">Select Chapter</option>

                </select>
            </div>

            <div id="questionsContainer">
                <div class="question-group">
                    <h5>Question 1</h5>
                    <div class="form-group">
                        <label for="question1Text">Question Text:</label>
                        <input type="text" class="form-control" name="questions[0][text]" required>
                    </div>
                    <div class="form-group">
                        <label>Answer Type:</label>
                        <select class="form-control question-type" name="questions[0][type]">
                            <option value="mcq">Multiple Choice</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>
                    <div class="options-container">
                        <label>Choices (separate by commas):</label>
                        <input type="text" class="form-control" name="questions[0][options]" placeholder="Option 1, Option 2">
                        <label for="correctAnswer">Correct Answer:</label>
                        <input type="text" class="form-control" name="questions[0][correct_answer]" placeholder="Enter correct answer">
                    </div>
                    <span class="remove-question" onclick="$(this).closest('.question-group').remove()">Remove Question</span>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="addQuestion">Add Another Question</button>

            <button type="submit" class="btn btn-primary btn-block">Submit Quiz</button>
        </form>
        <input type="hidden" id="availability-route" value="{{ route('instructor.courses.index') }}">
        <x-table id="courseTable">
            <th scope="col"><div>{{ __('Name') }}</div></th>
            <th scope="col"><div>{{ __('Image') }}</div></th>
            <th scope="col"><div>{{ __('lectures') }}</div></th>
            <th scope="col"><div>{{ __('hours') }}</div></th>
            <th scope="col"><div>{{ __('days') }}</div></th>
            <th scope="col"><div>{{ __('time') }}</div></th>
        </x-table>
    </x-wrapper>
@endsection
@push('script')
    <script>
        $(document).ready(function() {

            $(document).on('change', '#course_id', function() {
                const courseId=$(this).val();
                const url=`{{route('instructor.courses.chapters.get','')}}/${courseId}`;
                const chapterSelect=$('#chapter_id');

                $.ajax({
                    url:url,
                    method:'GET',
                    success:function (data){
                        chapterSelect.empty();
                        $.each(data, function(index, chapter) {
                            chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                        });

                    }
                })

            })
                let questionCount = 1;

            $('#addQuestion').click(function() {
                questionCount++;
                const questionGroup = `
                <div class="question-group">
                    <h5>Question ${questionCount}</h5>
                    <div class="form-group">
                        <label for="question${questionCount}Text">Question Text:</label>
                        <input type="text" class="form-control" name="questions[${questionCount - 1}][text]" required>
                    </div>
                    <div class="form-group">
                        <label>Answer Type:</label>
                        <select class="form-control question-type" name="questions[${questionCount - 1}][type]">
                            <option value="mcq">Multiple Choice</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>
                    <div class="options-container">
                        <label>Choices (separate by commas):</label>
                        <input type="text" class="form-control" name="questions[${questionCount - 1}][options]" placeholder="Option 1, Option 2">
                        <label for="correctAnswer">Correct Answer:</label>
                        <input type="text" class="form-control" name="questions[${questionCount - 1}][correct_answer]" placeholder="Enter correct answer">
                    </div>
                    <span class="remove-question" onclick="$(this).closest('.question-group').remove()">Remove Question</span>
                </div>
            `;
                $('#questionsContainer').append(questionGroup);
            });

            $(document).on('change', '.question-type', function() {
                const optionsContainer = $(this).closest('.question-group').find('.options-container');
                if ($(this).val() === 'essay') {
                    optionsContainer.find('input').val(''); // Clear options and correct answer inputs
                    optionsContainer.find('input').attr('placeholder', ''); // Clear placeholders
                    optionsContainer.find('label:contains("Choices")').hide(); // Hide choices label
                    optionsContainer.find('label:contains("Correct Answer")').hide(); // Hide correct answer label
                } else {
                    optionsContainer.find('label:contains("Choices")').show(); // Show choices label
                    optionsContainer.find('label:contains("Correct Answer")').show(); // Show correct answer label
                }
            });
        });
    </script>
    <script src="{{ asset('instructor/js/course.js') }}"></script>

@endpush
