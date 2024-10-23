@extends('layouts.app')
@section('content')
    <div class="p-30">

            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <div class="container">
                <div class="row">

                    @foreach($courses as $course)
                        <div class="col-lg-4">
                            <a href="{{route('student.courses.info',$course->slug)}}">
                                <div class="card">
                                    <div class="card-body">
                                        <section class="rounded border">
                                            <section>

                                            </section>
                                            <img src="{{asset('public/storage/admin/course'.'/'.$course->image)}}" alt="">
                                        </section>
                                        <section class="mt-1">
                                            {{$course->total_materials_count}}
                                        </section>
                                        <section >
                                            <h1 class="fs-20">{{$course->name}}</h1>
                                        </section>
                                    </div>
                                    <div class="card-footer bg-transparent">

                                        {{--                                    {{$course->instructor->first_name}}--}}
                                        <section>
                                            <form method="POST" action="{{route('student.courses.register')}}">
                                                @csrf
                                                <button>Register</button>
                                            </form>
                                        </section>
                                    </div>
                                </div>
                            </a>

                        </div>
                    @endforeach

                </div>
            </div>
            </div>
    </div>
@endsection

