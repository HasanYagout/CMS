@extends('layouts.app_course')
@section('content')
    <x-wrapper title="">

        @foreach($quizzes as $quiz)
            @php
                $submitted = $quiz->submittedQuiz->isNotEmpty();
                $isPastDue = \Carbon\Carbon::now()->isAfter($quiz->due_date);
                $dueInThreeDays = \Carbon\Carbon::now()->diffInDays($quiz->due_date) <= 3;
                $bgColor = '';
                if ($isPastDue) {
                    $bgColor = $submitted ? 'bg-light-green' : 'bg-error';
                } elseif ($dueInThreeDays) {
                    $bgColor = $submitted ? '' : '';
                } else {
                    $bgColor = $submitted ? 'bg-light-green' : 'bg-error';
                }
            @endphp

            @php $lecture = $quiz->lecture; @endphp
            <a href="{{route('student.courses.lectures.view',['course_id'=>$quiz->lecture->chapters->course->id,'lecture_id'=>$lecture->id])}}">
                <section class="assignment-section border border-3 border-black mb-5 px-29 py-10 rounded-4 ">
                    <section class="d-flex mb-18 justify-content-between text-black">
                        <h3>{{ $quiz->title }}</h3>
                        <h3 class="fs-18">{{ \Carbon\Carbon::parse($quiz->end_date)->format('d M, Y') }}</h3>
                    </section>
                    <section class="quiz fw-bold rounded-end-4 rounded-top-4 mb-2 text-black d-flex justify-content-around p-13 {{ $bgColor }} ">
                        <span>{{ $quiz->title }}</span>
                        <span>Delivery time: {{ \Carbon\Carbon::parse($quiz->due_date)->format('h:i A d M, Y') }}</span>
                        @if($isPastDue)
                            <span>Status: {{ $submitted ? 'Submitted on time' : 'Not Submitted' }}</span>
                        @elseif($dueInThreeDays)
                            <span class="text-danger">Due in: {{ \Carbon\Carbon::now()->diffInDays($quiz->due_date) }} days and {{ \Carbon\Carbon::now()->diffInHours($quiz->due_date) % 24 }} hours</span>
                        @else
                            <span>Status: {{ $submitted ? 'Submitted' : 'Not Submitted' }}</span>
                        @endif
                    </section>
                </section>
            </a>
        @endforeach
    </x-wrapper>
@endsection
