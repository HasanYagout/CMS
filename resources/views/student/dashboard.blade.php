@extends('layouts.app')

@section('content')

    <x-wrapper title="">
        <h1 class="text-black text-center text-secondary-color">welcome</h1>
        <span class="d-block fs-2 text-black text-center"> {{$student->first_name.' '.$student->last_name}}</span>
    </x-wrapper>
    <div class="row">
        <div class="col-lg-4">
            <x-wrapper title="">
                <h1>news</h1>
                <ul>
                    @foreach($news as $new)
                    <li class="border border-black d-flex justify-content-between p-2 rounded">
                        <section class="d-flex flex-column">
                            <span class="fw-300 fw-600 text-black"> {{$new->title}}</span>
                            <span> {{$new->description}}</span>
                        </section>
                        <span class="fw-300 fw-600 text-black">
{{ \Illuminate\Support\Carbon::parse($new->created_at)->format('Y-m-d') }}
                        </span>

                    </li>
                    @endforeach
                </ul>

            </x-wrapper>

        </div>
        <div class="col-lg-4">
            <x-wrapper title="">
                <h1>notifications</h1>

                    <ul>
{{--                        <li><a href="{{route('student.courses.announcements',$courseId)}}">Announcements{{$announcements}}</a></li>--}}
                        <li>Assignments{{$assignments}}</li>
                        <li>Quizzes{{$quizzes}}</li>
                    </ul>
            </x-wrapper>
        </div>
    </div>

@endsection
