@extends('layouts.app_course')

@section('content')

    <div class="p-30">
        <h1 class="text-primary-color rounded">{{ $course->name }}</h1>
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <div class="container">
                <div class="row">

                    @foreach($course->chapters as $index => $chapter)
                        <div class="col-12 mb-3">
                            <button class="btn bg-primary-color w-75 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChapter{{ $index }}" aria-expanded="false" aria-controls="collapseChapter{{ $index }}">
                                {{ $chapter->title }}
                            </button>
                            <div class="collapse multi-collapse mt-2" id="collapseChapter{{ $index }}">
                                <div class="card card-body">
                                    <div class="row">

                                        @if($chapter->lectures)
                                            @foreach($chapter->lectures as $lecture)
                                                <div class="col-lg-4">

                                                    <a href="{{route('student.courses.lectures.view',['id'=>$lecture->id])}}" class="bg-secondary-color p-2 rounded my-3 text-white">

                                                        {{ $lecture->title }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No materials available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
