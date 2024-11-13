@extends('layouts.app')

@section('content')
    <div class="row">
        <x-wrapper title="">
            <h1 class=" text-center text-primary-color"><span
                    class=" text-third-color">Welcome </span>{{\Illuminate\Support\Facades\Auth::user()->instructor->first_name.' '. \Illuminate\Support\Facades\Auth::user()->instructor->last_name}}
            </h1>
            <div class="row">
                <div class="col-lg-6">
                    <a href="{{route('instructor.courses.index')}}">
                        <x-wrapper class="shadow" title="Courses">
                            <h1 class="text-third-color">{{ $courses }}</h1>
                        </x-wrapper>
                    </a>

                </div>
            </div>
        </x-wrapper>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <x-wrapper title="Up Coming Lectures">
                <section class="border row d-flex flex-column gap-3 p-13 rounded-1">
                    @foreach($lectures as $lecture)
                        @if($lecture->chapter && $lecture->chapter->course)
                            <section
                                class="border d-flex col-lg-12 justify-content-between p-13 rounded-1 text-secondary-color">
                                <span class="">{{$lecture->title}} - {{$lecture->chapter->course->name}}</span>
                                <span>{{$lecture->start_date}}</span>
                            </section>
                        @endif
                    @endforeach
                </section>
            </x-wrapper>
        </div>
        <div class="col-lg-6">
            <x-wrapper title="Assignments">
                <section class="border row d-flex flex-column gap-3 p-13 rounded-1">
                    @foreach($assignments as $assignment)
                        @if($assignment->lecture->chapter && $assignment->lecture->chapter->course)

                            <section
                                class="border d-flex col-lg-12 justify-content-between p-13 rounded-1 text-secondary-color">
                                <span
                                    class="">{{$assignment->title}} - {{$assignment->lecture->chapter->course->name}}</span>
                                <span>{{$assignment->due_date}}</span>
                            </section>
                        @endif
                    @endforeach
                </section>
            </x-wrapper>

        </div>
        <div class="col-lg-6">
            <x-wrapper title="Quizzes">
                <section class="border row d-flex flex-column gap-3 p-13 rounded-1">
                    @foreach($quizzes as $quiz)
                        @if($quiz->lecture->chapter && $quiz->lecture->chapter->course)

                            <section
                                class="border d-flex col-lg-12 justify-content-between p-13 rounded-1 text-secondary-color">
                                <span
                                    class="">{{$quiz->title}} - {{$quiz->lecture->chapter->course->name}}</span>
                                <span>{{$quiz->due_date}}</span>
                            </section>
                        @endif
                    @endforeach
                </section>
            </x-wrapper>

        </div>
        <div class="col-lg-6">
            <x-wrapper title="Activities">
                <section class="border row d-flex flex-column gap-3 p-13 rounded-1">
                    @foreach($activities as $activity)
                        @if($activity->lecture->chapter && $activity->lecture->chapter->course)

                            <section
                                class="border d-flex col-lg-12 justify-content-between p-13 rounded-1 text-secondary-color">
                                <span
                                    class="">{{$activity->title}} - {{$activity->lecture->chapter->course->name}}</span>
                                <span>{{$activity->due_date}}</span>
                            </section>
                        @endif
                    @endforeach
                </section>
            </x-wrapper>

        </div>
    </div>
@endsection
