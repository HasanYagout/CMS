@extends('layouts.app')
@section('content')
    <div class="p-30">

            <div class="container">
                <div class="row">

                    @foreach($availabilities as $availability)

                        <div class="col-lg-4">
                            <a href="{{route('student.courses.info',$availability->course->slug)}}">
                                <div class="card">
                                    <div class="card-body">
                                        <section class="rounded border">
                                            <section class="bg-third-color m-2 position-absolute px-10 rounded w-25">
                                                {{$availability->course->semester->name}}
                                            </section>
                                            <img class="w-100" style="height: 240px" src="{{asset('public/storage/admin/course'.'/'.$availability->course->image)}}" alt="">
                                        </section>
                                        <section class="d-flex justify-content-around my-4">
                                            <section>
                                            <i class="fa fa-book fs-20 text-primary-color"></i> {{$availability->course->total_materials_count}} Lessons
                                            </section>
                                            <section>
                                                {{$availability->instructor->first_name.' '.$availability->instructor->last_name}}
                                            </section>
                                        </section>
                                        <section >
                                            <h1 class="fs-20">{{$availability->name}}</h1>
                                        </section>
                                    </div>
                                    <div class="card-footer bg-transparent">

                                        {{--                                    {{$availability->instructor->first_name}}--}}
                                        <section>
                                            <form method="POST" action="{{route('student.courses.enrollment.register',$availability->course_id)}}">
                                                @csrf
                                                <button class="zBtn-two">Register</button>
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

@endsection

