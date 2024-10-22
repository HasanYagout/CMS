@extends('layouts.app')
@section('content')
    <div class="p-30">

            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <div class="container">
                <div class="row">
                    @foreach($courses as $course)
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <section>
                                    <img src="{{asset('public/storage/admin/course'.'/'.$course->image)}}" alt="">
                                    </section>
                                    <section>
                                        <h1>{{$course->name}}</h1>
                                    </section>
                                </div>
                                <div class="card-footer">
                                    {{$course->instructor->first_name}}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            </div>
    </div>
@endsection

