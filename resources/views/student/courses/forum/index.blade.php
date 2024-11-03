@extends('layouts.app_course')
@section('content')
    <div class="row">
        <div class="bg-white col-lg-3 mt-30 rounded rounded-5">
            <ul class="zSidebar-menu my-5" id="sidebarMenu">
                @foreach($forums as $forum)
                <li>
                    <a class="d-flex flex-column fs-12 fw-bold text-primary-color {{ request()->is('student/courses/lectures/view/' . $forum->id . '/' . $forum->id) ? 'active bg-custom-primary-light text-primary-color' : '' }}"
                       href="{{route('student.courses.forum.index',['course_id'=>$forum->course_id,'id'=>$forum->id])}}">
                        <span class="fw-bold">{{ $forum->title }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-9">
            <div class="bg-white mt-30 rounded rounded-5">
                <h1>{{$activeForum->title}}</h1>
                <p>{{$activeForum->description}}</p>
            <hr>
                @foreach($chats as $chat)
                                                        <div class=" d-flex flex-column mb-3 {{ Auth::id() == $chat->user_id ? 'align-items-end' : 'align-items-start' }}">
                                                            <strong class="{{ Auth::id() == $chat->user_id ? 'text-third-color' : 'text-secondary-color' }}">
                                                                @if(Auth::id() == $chat->user_id)
                                                                     Don't display name for the user sending the message

                                                                @elseif($chat->user->role_id == 3)
                                                                    {{ $chat->user->student->first_name.' '.$chat->user->student->last_name }}
                                                                @else
                                                                    {{ $chat->user->instructor->first_name.' '.$chat->user->instructor->last_name }}
                                                                @endif
                                                            </strong>
                                                            <div style="overflow-wrap: anywhere;width: fit-content;{{ Auth::id() == $chat->user_id ? 'background-color:#c2fb95;' : 'background-color:rgba(35, 55, 255, 0.09);' }}" class="d-inline-block p-2 fw-400 rounded ">
                                                                {{ $chat->message }}
                                                            </div>
                                                            <small class="text-muted">
                                                                {{ $chat->created_at->format('F j, Y, g:i a') }}
                                                            </small>
                                                        </div>
                                                    @endforeach
            </div>
        </div>
    </div>

@endsection
