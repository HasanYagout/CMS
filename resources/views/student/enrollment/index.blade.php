@extends('layouts.app')
@section('content')
    <div class="p-30">

        <div class="container">
            <div class="row">

                @foreach($courses as $course)
                    <div class="col-lg-4">
                        <a href="{{route('student.enrollment.index',$course->slug)}}">
                            <div class="card">
                                <div class="card-body">
                                    <section class="rounded border">
                                        <section class="bg-third-color m-2 position-absolute px-10 rounded w-25">
                                        </section>
                                        <img class="w-100" style="height: 240px" src="{{asset('storage/courses').'/'.$course->image}}">
                                    </section>

                                    <section >
                                        <h1 class="fs-20 text-primary-color ">{{$course->name}}</h1>
                                    </section>
                                </div>

                                <div class="card-footer bg-transparent text-secondary-color">
                                    {{$course->availability->instructor->first_name.' '.$course->availability->instructor->last_name}}
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection

