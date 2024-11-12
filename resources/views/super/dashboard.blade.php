@extends('layouts.app')

@section('content')
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
        <div class="col-lg-4">
            <x-wrapper title="admins">
                <h1 class=" text-third-color"> {{$admins}}</h1>
            </x-wrapper>
        </div>
        <div class="col-lg-4">
            <x-wrapper title="departments">
                <h1 class=" text-third-color"> {{$departments}}</h1>
            </x-wrapper>
        </div>
    </div>

@endsection
