@extends('layouts.app_course')

@section('content')
    <div class="row">
        <div class="bg-white col-lg-3 mt-30 rounded rounded-5">
            <ul class="zSidebar-menu my-5" id="sidebarMenu">
                @if($forums->isEmpty())
                    <li>No forums available for this course.</li>
                @else
                    @foreach($forums as $forum)
                        <li>
                            <a class="d-flex flex-column fs-12 fw-bold text-primary-color {{ $activeForum && $activeForum->id == $forum->id ? 'active bg-custom-primary-light text-primary-color' : '' }}"
                               href="{{ route('student.courses.forum.index', ['course_id' => $forum->course_id, 'id' => $forum->id]) }}">
                                <span class="fw-bold">{{ $forum->title }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        <div class="col-lg-9">
            <div class="bg-white mt-30 px-25 py-17 rounded rounded-5">
                @if($activeForum)
                    <h1 class="text-primary-color">{{ $activeForum->title }}</h1>
                    <p class="text-black">{{ $activeForum->description }}</p>

                    <hr>
                    <div class="chat-section p-3" style="background-color:rgba(35, 55, 255, 0.09);">
                        <div class="chat-messages rounded" style="max-height: 350px; overflow-y: auto;">
                            @foreach($activeForum->comment as $comment)
                                <div
                                    class="d-flex flex-column mb-3 {{ Auth::id() == $comment->student_id ? 'align-items-end' : 'align-items-start' }}">
                                    <strong
                                        class="{{ Auth::id() == $comment->student_id ? 'text-third-color' : 'text-secondary-color' }}">
                                        {{ Auth::id() == $comment->student_id ? '' : ($comment->user->role_id == 3 ? $comment->user->student->first_name . ' ' . $comment->user->student->last_name : $comment->user->instructor->first_name . ' ' . $comment->user->instructor->last_name) }}
                                    </strong>
                                    <div
                                        style="overflow-wrap: anywhere;width: fit-content;{{ Auth::id() == $comment->student_id ? 'background-color:#c2fb95;' : 'background-color:rgba(35, 55, 255, 0.09);' }}"
                                        class="d-inline-block p-2 fw-400 rounded ">
                                        {{ $comment->comment }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $comment->created_at->format('F j, Y, g:i a') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <form action="{{ route('student.courses.forum.store') }}" class="d-flex gap-3" method="POST">
                        @csrf
                        <input type="hidden" name="forum_id" value="{{ $activeForum->id }}">
                        <textarea name="message" style="width: 60%" class="form-control"
                                  placeholder="Type your message..."></textarea>
                        <button type="submit" class="zBtn-one mt-2">Send</button>
                    </form>
                @else
                    <h3>No Data</h3>
                @endif
            </div>
        </div>
    </div>
@endsection
