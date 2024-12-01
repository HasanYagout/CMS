@extends('layouts.app')
@section('content')
    <x-wrapper title="Take Exam">
        <div class="container mt-5">
            <h3 class="text-black">{{ $quiz->title }}</h3>
            <h4 id="timer" class="alert alert-info my-4">Time Remaining: <span id="time"></span></h4>
            <form id="examForm" action="{{route('student.courses.quizzes.store')}}" method="post">
                @csrf
                <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                <h3 class="text-black">Please read the following instructions carefully:</h3>
                <ol class="mt-4">
                    <li>Make sure you have a stable internet connection.</li>
                    <li>Once you start the exam, you cannot leave the page.</li>
                    <li>You will be automatically submitted when time is up.</li>
                </ol>
                <hr>
                @foreach($quiz->questions as $index=> $question)
                    <div class="mb-4">
                        <h5 class="text-secondary-color mb-2"><span
                                class="me-2">{{$index+1}} -</span>{{ $question->text }}</h5>
                        @if ($question->type == 'mcq')
                            @foreach (json_decode($question->options) as $option)
                                <div class="text-black">
                                    <input type="radio" name="questions[{{ $question->id }}]"
                                           value="{{ trim($option) }}">
                                    <label>{{ trim($option) }}</label>
                                </div>
                            @endforeach
                        @else
                            <textarea name="questions[{{ $question->id }}]" class="form-control" rows="3"></textarea>
                        @endif
                    </div>
                    <hr>
                @endforeach
                <button type="submit" class=" bg-secondary-color btn btn-primary text-white ">Submit Exam</button>
            </form>
        </div>
    </x-wrapper>
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            let duration = {{ $quiz->duration }} * 60; // Exam duration in seconds
            const timerDisplay = $('#time');

            // Check if there's a saved time in localStorage
            if (localStorage.getItem('remainingTime')) {
                duration = parseInt(localStorage.getItem('remainingTime'), 10);
            }

            function startTimer() {
                const interval = setInterval(function () {
                    let minutes = Math.floor(duration / 60);
                    let seconds = duration % 60;

                    seconds = seconds < 10 ? '0' + seconds : seconds;
                    timerDisplay.text(`${minutes}:${seconds}`);

                    // Save the remaining time to localStorage
                    localStorage.setItem('remainingTime', duration);

                    if (--duration < 0) {
                        clearInterval(interval);
                        alert("Time is up! Submitting your exam.");
                        $('#examForm').submit(); // Auto-submit the form
                    }
                }, 1000);
            }

            startTimer();


            // Clear the timer in localStorage on exam submission
            $('#examForm').on('submit', function () {
                localStorage.removeItem('remainingTime');
            });
        });
    </script>
@endpush
