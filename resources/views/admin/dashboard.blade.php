@extends('layouts.app')

@section('content')
    <div class="row">
        <x-wrapper title="">
            <h1 class=" text-center text-primary-color"><span
                    class=" text-third-color">Welcome </span>{{\Illuminate\Support\Facades\Auth::user()->admin->first_name.' '. \Illuminate\Support\Facades\Auth::user()->admin->last_name}}
            </h1>
        </x-wrapper>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <x-wrapper title="Courses">
                <h1 class=" text-third-color"> {{$courses}}</h1>
            </x-wrapper>
        </div>
        <div class="col-lg-4">
            <x-wrapper title="Instructors">
                <h1 class=" text-third-color"> {{$instructors}}</h1>
            </x-wrapper>
        </div>
        <div class="col-lg-4">
            <x-wrapper title="Students">
                <h1 class=" text-third-color"> {{$students}}</h1>
            </x-wrapper>
        </div>
    </div>

@endsection

