@extends('layouts.app_course')

@section('content')
    <section class="mt-30">
        @foreach($lectures as $lecture)
{{--            {{dd($lecture->end_date)}}--}}
            <div class="assignment-section border border-3 border-black mb-5 px-29 py-10 rounded-4 ">
                <section class="d-flex mb-18 justify-content-between text-black">
                    <h3>{{ $lecture->title }}</h3>
                    <h3 class="fs-18">{{ \Carbon\Carbon::parse($lecture->end_date)->format('d M, Y') }}</h3>
                </section>
                @foreach($lecture->assignments as $assignment)
                    @php
                        $submitted = $assignment->submittedAssignments->isNotEmpty();
                        $isPastDue = \Carbon\Carbon::now()->isAfter($assignment->due_date);
                        $dueInThreeDays = \Carbon\Carbon::now()->diffInDays($assignment->due_date) <= 3;
                        $bgColor = '';
                        if ($isPastDue) {
                            $bgColor = $submitted ? 'bg-light-green' : 'bg-error';
                        } elseif ($dueInThreeDays) {
                            $bgColor = $submitted ? '' : '';
                        } else {
                            $bgColor = $submitted ? 'bg-light-green' : 'bg-error';
                        }
                    @endphp
                    <div class="assignment fw-bold rounded-end-4 rounded-top-4 mb-2 text-black d-flex justify-content-around p-13 {{ $bgColor }} ">
                        <span>{{ $assignment->title }}</span>
                        <span>Delivery time: {{ \Carbon\Carbon::parse($assignment->due_date)->format('h:i A d M, Y') }}</span>
                        @if($isPastDue)
                            <span>Status: {{ $submitted ? 'Submitted on time' : 'Not Submitted' }}</span>
                        @elseif($dueInThreeDays)
                            <span class="text-danger">Due in: {{ \Carbon\Carbon::now()->diffInDays($assignment->due_date) }} days and {{ \Carbon\Carbon::now()->diffInHours($assignment->due_date) % 24 }} hours</span>
                        @else
                            <span>Status: {{ $submitted ? 'Submitted' : 'Not Submitted' }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>
@endsection
