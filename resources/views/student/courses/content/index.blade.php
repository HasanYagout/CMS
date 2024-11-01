@extends('layouts.app_course')

@section('content')
    <x-breadcrumbs :items="[
    ['name' => 'Home', 'url' => route('student.courses.index')],
    ['name' => 'Courses', 'url' => route('student.courses.index')],
    ['name' => 'Content', 'url' => '']
        ]" />
  <x-wrapper title="">
      <div class="container">
          <div class="row">
              @foreach($lectures as $index => $lecture)
                  <div class="col-12 mb-3">
                      <button class="btn bg-primary-color w-75 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChapter{{ $index }}" aria-expanded="false" aria-controls="collapseChapter{{ $index }}">
                          {{ $lecture->title }}
                      </button>
                      <div class="collapse multi-collapse mt-2" id="collapseChapter{{ $index }}">
                          <div class="card card-body">
                              <div class="row gap-4">
                                  @if($lecture->materials)
                                      @foreach($lecture->materials as $material)
                                          <div class="col-lg-4">
                                              <a href="{{ asset('public/storage/' . $material->url) }}" class="bg-secondary-color p-2 rounded my-3 text-white" download>
                                                  @if($material->type == 'image')
                                                      <i class="fa fa-image"></i> <!-- Font Awesome Image Icon -->
                                                  @elseif($material->type == 'video')
                                                      <i class="fa fa-video"></i> <!-- Font Awesome Video Icon -->
                                                  @endif
                                                  <span>{{ $material->title }}</span>
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
  </x-wrapper>


@endsection
