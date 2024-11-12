@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <x-wrapper title="{{$course->name}}">
                @foreach($course->chapters as $index => $chapter)
                    <div class="col-12 mb-3">
                        <button class="btn bg-primary-color w-100 text-white" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseChapter{{ $index }}" aria-expanded="false"
                                aria-controls="collapseChapter{{ $index }}">
                            {{ $chapter->title }}
                        </button>
                        <div class="collapse multi-collapse mt-2" id="collapseChapter{{ $index }}">
                            <div class="card card-body">
                                <div class="row gap-4">

                                    @if($chapter->lectures)
                                        @foreach($chapter->lectures as $lecture)
                                            <div class="col-lg-4">

                                                    <span
                                                        class="bg-secondary-color p-2 rounded my-3 text-white">

                                                        {{ $lecture->title }}
                                                    </span>
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

            </x-wrapper>
        </div>
        <div class="col-lg-4">
            <x-wrapper title="">
                <img class="w-100" src="{{asset('storage/courses/'.'/'.$course->image)}}" alt="">
                <div class="card-footer d-flex flex-column">
                    @if($available!=0)
                        <span class="fs-18 fw-bold text-black">{{$message}}</span>
                        @if(!$enrolled)
                            <form method="POST" action="{{route('student.enrollment.register',$course->id)}}">
                                @csrf
                                <button class="text-center w-110 zBtn-one">Enroll</button>
                            </form>
                        @else
                            <span class="fw-bold text-success">You are registered</span>
                        @endif

                    @else
                        <span class="fs-18 fw-bold text-danger">{{$message}}</span>
                    @endif
                </div>
            </x-wrapper>
        </div>
    </div>

@endsection
