@extends('layouts.app_course')
@section('content')
    <x-breadcrumbs :items="[
    ['name' => 'Home', 'url' => route('student.courses.index')],
    ['name' => 'Courses', 'url' => route('student.courses.index')],
    ['name' => $course->name, 'url' => '']
       ]"/>
    <div class="row">
        <div class="col-lg-6">
            <x-wrapper title="">
                <h3 class="text-black mb-3">Notifications</h3>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="assignments-tab" data-bs-toggle="tab" href="#assignments"
                           role="tab" aria-controls="assignments" aria-selected="true">Assignments</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="chat-tab" data-bs-toggle="tab" href="#chat" role="tab"
                           aria-controls="chat" aria-selected="false">Chat</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="quizzes-tab" data-bs-toggle="tab" href="#quizzes" role="tab"
                           aria-controls="quizzes" aria-selected="false">Quizzes</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="announcements-tab" data-bs-toggle="tab" href="#announcements" role="tab"
                           aria-controls="announcements" aria-selected="false">Announcement</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active " id="assignments" role="tabpanel"
                         aria-labelledby="assignments-tab">
                        <ul class="d-flex flex-column gap-3">
                            @foreach($assignments as $assignment)
                                <a href="{{route('student.courses.lectures.view',['course_id'=>$course_id,'lecture_id'=>$assignment->lecture->id])}}">
                                    <li class="border border-2 mt-3 p-2 rounded  shadow-sm">
                                        <section class="d-flex flex-column justify-content-lg-between">
                                            <section class="d-flex justify-content-between">

                                                <h5 class="text-black">{{ $assignment->title }}</h5>
                                                <h5 class="text-third-color fs-15">{{ $assignment->due_date }}</h5>
                                            </section>
                                            <section>

                                                <h5 class="fs-15">{{ $assignment->lecture->title }}</h5>
                                            </section>
                                        </section>
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="chat" role="tabpanel" aria-labelledby="chat-tab">
                        <ul class="d-flex flex-column gap-3">
                            <div class="chat-section p-3" style="background-color:rgba(35, 55, 255, 0.09);">
                                <div class="chat-messages  rounded" style="max-height: 350px; overflow-y: auto;">
                                    {{--                                    @foreach($chats as $chat)--}}
                                    {{--                                        <div class=" d-flex flex-column mb-3 {{ Auth::id() == $chat->user_id ? 'align-items-end' : 'align-items-start' }}">--}}
                                    {{--                                            <strong class="{{ Auth::id() == $chat->user_id ? 'text-third-color' : 'text-secondary-color' }}">--}}
                                    {{--                                                @if(Auth::id() == $chat->user_id)--}}
                                    {{--                                                    --}}{{-- Don't display name for the user sending the message --}}

                                    {{--                                                @elseif($chat->user->role_id == 3)--}}
                                    {{--                                                    {{ $chat->user->student->first_name.' '.$chat->user->student->last_name }}--}}
                                    {{--                                                @else--}}
                                    {{--                                                    {{ $chat->user->instructor->first_name.' '.$chat->user->instructor->last_name }}--}}
                                    {{--                                                @endif--}}
                                    {{--                                            </strong>--}}
                                    {{--                                            <div style="overflow-wrap: anywhere;width: fit-content;{{ Auth::id() == $chat->user_id ? 'background-color:#c2fb95;' : 'background-color:rgba(35, 55, 255, 0.09);' }}" class="d-inline-block p-2 fw-400 rounded ">--}}
                                    {{--                                                {{ $chat->message }}--}}
                                    {{--                                            </div>--}}
                                    {{--                                            <small class="text-muted">--}}
                                    {{--                                                {{ $chat->created_at->format('F j, Y, g:i a') }}--}}
                                    {{--                                            </small>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    @endforeach--}}
                                </div>
                                <form action="{{ route('student.courses.forum.store') }}" class="d-flex gap-3"
                                      method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course_id}}">
                                    <textarea name="message" style="width: 60%" class="form-control"
                                              placeholder="Type your message..."></textarea>
                                    <button type="submit" class="zBtn-one mt-2">Send</button>
                                </form>
                            </div>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="quizzes" role="tabpanel" aria-labelledby="quizzes-tab">
                        <ul class="d-flex flex-column gap-3">
                            @foreach($quizzes as $quiz)
                                <a href="{{route('student.courses.lectures.view',['course_id'=>$course_id,'lecture_id'=>$quiz->lecture->id])}}">
                                    <li class="border border-3 mt-3  p-2 rounded">
                                        <section class="d-flex flex-column justify-content-lg-between">
                                            <section class="d-flex justify-content-between">
                                                <h5 class="text-black">{{ $quiz->title }}</h5>
                                                <h5 class="text-third-color fs-15">{{ $quiz->due_date }}</h5>
                                            </section>
                                            <section>
                                                <span class="fs-15">{{ $quiz->lecture->title }}</span>
                                            </section>

                                        </section>
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="announcements" role="tabpanel" aria-labelledby="announcements-tab">
                        <ul class="d-flex flex-column gap-3">
                            @foreach($announcements as $announcement)
                                <li class="border border-3  justify-content-between p-2 rounded">
                                    <section class="d-flex flex-column">
                                        <section class="d-flex justify-content-between">
                                            <h5 class="text-black">{{ $announcement->title }}</h5>
                                            <h5 class="text-third-color fs-15">{{ $announcement->created_at }}</h5>
                                        </section>
                                        @if($announcement->text)
                                            <span> {{ $announcement->text }}</span>
                                        @else
                                            <span> vote</span>
                                        @endif
                                    </section>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </x-wrapper>
        </div>
        <div class="col-lg-6">
            <div class="col-lg-12">
                <x-wrapper title="">
                    <div class="row">
                        <ul class="d-flex flex-column gap-2">
                            <h3>Items to Pay Attention to:</h3>
                            @foreach($attentions as $index => $attention)
                                <li class="border border-3 d-flex justify-content-between p-2 rounded">
                                    <section>
                                        <h5 class="text-black">{{ $attention->title }}</h5>
                                    </section>
                                    <section class="d-flex flex-column fs-10 fw-bold text-danger">
                                        <span>Day: {{ $days[$index] }}</span>
                                        <span>Hours: {{ $hours[$index] }}</span>
                                    </section>
                                    <section class="text-secondary-color">
                                        {{ $attention->due_date }}
                                    </section>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-wrapper>
            </div>
            <div class="col-lg-12">
                <x-wrapper title="">
                    <div style="height: 220px" class="overflow-auto row">
                        <ul class="d-flex flex-column gap-2">
                            <h3>Lectures</h3>
                            @foreach($lectures as $lecture)
                                <a href="{{route('student.courses.lectures.view',['course_id'=>$course_id,'lecture_id'=>$lecture->id])}}">
                                    <li class="border border-3 justify-content-between p-2 rounded">
                                        <section class="d-flex justify-content-between">
                                            <h5 class="text-black"> {{ $lecture->title }}</h5>
                                            <h5 class="fs-15 text-black text-third-color"> {{ $lecture->start_date }}</h5>
                                        </section>
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </x-wrapper>
            </div>
        </div>
    </div>
@endsection
