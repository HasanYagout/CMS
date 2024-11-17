@extends('layouts.app')

@section('content')

    <x-wrapper title="">
        <h1 class="text-black text-center text-secondary-color">welcome</h1>
        <span class="d-block fs-2 text-black text-center"> {{$student->first_name.' '.$student->last_name}}</span>
    </x-wrapper>
    <div class="row">
        <div class=" col-xl-4 col-lg-6">
            <x-wrapper title="">
                <h1 class="text-black">news</h1>
                <ul>
                    @foreach($news as $new)
                        <li class="border border-black d-flex justify-content-between p-2 rounded">
                            <section class="d-flex flex-column">
                                <span class="fw-300 fw-600 text-black"> {{$new->title}}</span>
                                <span> {!! $new->description !!}</span>
                            </section>
                            <span class="fw-300 fw-600 text-third-color">
                            {{ \Illuminate\Support\Carbon::parse($new->created_at)->format('Y-m-d') }}
                        </span>

                        </li>
                    @endforeach
                </ul>

            </x-wrapper>

        </div>
        <div class=" col-xl-4 col-lg-6">
            <x-wrapper title="">
                <h1 class="text-black">notifications</h1>
                <ul style="height: 250px" class="overflow-auto">
                    @foreach($attentions as $attention)
                        <section class="border border-2 d-flex justify-content-between mb-10 p-13">
                            <h6 class="text-black">{{$attention->title}}</h6>
                            <h6 class="text-secondary-color text-third-color">{{\Illuminate\Support\Carbon::parse($attention->due_date)->toDateString()}}</h6>
                        </section>
                    @endforeach
                    @foreach($announcements as $announcement)
                        <section class="border border-2 d-flex justify-content-between mb-10 p-13">
                            <h6 class=" text-black">{{$announcement->title}}</h6>
                            <h6 class="text-secondary-color text-third-color">{{\Illuminate\Support\Carbon::parse($announcement->created_at)->toDateString()}}</h6>
                        </section>
                    @endforeach


                </ul>
            </x-wrapper>
        </div>
    </div>

@endsection
