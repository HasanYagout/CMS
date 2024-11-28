@extends('layouts.app')
@section('content')
    <div class="p-30">

        <div class="container">
            <div class="row">

                @foreach($courses as $course)
                    <div class="col-lg-4">
                        <a href="{{route('student.courses.info',$course->slug)}}">
                            <div class="card">
                                <div class="card-body">
                                    <section class="rounded border">
                                        <section class="bg-third-color m-2 position-absolute px-10 rounded w-25">
                                        </section>
                                        <img class="w-100" style="height: 240px"
                                             src="{{asset('storage/courses').'/'.$course->image}}">
                                    </section>

                                    <section>
                                        <h1 class="fs-20 text-primary-color">{{$course->name}}</h1>
                                    </section>
                                </div>
                                <div class="bg-transparent card-footer d-flex justify-content-between">
                                    @if($course->availability)

                                        <span
                                            class="text-black">{{$course->availability->instructor->first_name.' '.$course->availability->instructor->last_name}}</span>
                                        <span class="text-third-color">{{$course->start_date}}</span>
                                    @endif

                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection

