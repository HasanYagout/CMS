@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-6">
        <x-wrapper title="">
            <h3>Notifications</h3>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="assignments-tab" data-bs-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="true">Assignments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="quizzes-tab" data-bs-toggle="tab" href="#quizzes" role="tab" aria-controls="quizzes" aria-selected="false">Chat</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Quizzes</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Announcement</a>
                </li>
            </ul>
        </x-wrapper>
        </div>
        <div class="col-lg-6">
            <div class="col-lg-12">
                <x-wrapper title="">
                    <div class="row">
                        <ul class="d-flex flex-column gap-2">
                            <h3>Items to Pay Attention to:</h3>
                            @foreach($attentions as $attention)
                                <li class="border border-3 d-flex justify-content-between p-2 rounded">
                                    <section>
                                        {{$attention->title}}
                                    </section>
                                    <section class="d-flex flex-column fs-10 fw-bold text-danger">
                                        <span>Day: {{$days}}</span>
                                        <span>Hours: {{$hours}}</span>

                                    </section>

                                    <section>
                                        {{$attention->due_date}}
                                    </section>
                                </li>


                            @endforeach
                        </ul>
                    </div>
                </x-wrapper>
            </div>
            <div class="col-lg-12">
                <x-wrapper title="">
                    <div class="row">
                        <ul class="d-flex flex-column gap-2">
                            <h3>Lectures</h3>
                            @foreach($lectures as $lecture)
                                <li class="border border-3 d-flex justify-content-between p-2 rounded">
                                    <section>
                                        {{$lecture->title}}
                                    </section>
                                </li>


                            @endforeach
                        </ul>
                    </div>
                </x-wrapper>
            </div>
        </div>

    </div>


@endsection
