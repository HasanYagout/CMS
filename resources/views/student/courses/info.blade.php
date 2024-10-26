@extends('layouts.app')

@section('content')
    <div class="p-30">
        <h1 class="text-primary-color rounded">{{ $course->name }}</h1>
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <div class="container">
                <div class="row">
                    @foreach($chapters as $index => $chapter)
                        <div class="col-12 mb-3">
                            <button class="btn bg-primary-color w-75 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChapter{{ $index }}" aria-expanded="false" aria-controls="collapseChapter{{ $index }}">
                                {{ $chapter->title }}
                            </button>
                            <a href="{{route('student.courses.chapters.view',$chapter->id)}}">view</a>
                            <div class="collapse multi-collapse mt-2" id="collapseChapter{{ $index }}">
                                <div class="card card-body">
                                    <div class="row">
                                    @if($chapter->material)
                                        @foreach($chapter->material as $material)
                                            <div class="col-lg-4">

                                            <p class="bg-secondary-color p-2 rounded my-3 text-white">
                                                @if($material->type == 'png')
                                                    <i class="fas fa-image"></i>
                                                @elseif($material->type == 'mp4')
                                                    <i class="fas fa-video"></i>
                                                @endif
                                                {{ $material->title }}
                                            </p>
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
