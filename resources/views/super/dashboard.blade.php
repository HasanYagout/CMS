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
            <a href="{{route('superAdmin.admin.index')}}">
                <x-wrapper title="admins">
                    <h1 class=" text-third-color"> {{$admins}}</h1>
                </x-wrapper>
            </a>
        </div>
        <div class="col-lg-4">
            <a href="{{route('superAdmin.department.index')}}">

                <x-wrapper title="departments">
                    <h1 class=" text-third-color"> {{$departments}}</h1>
                </x-wrapper>
            </a>
        </div>
        <div class="col-lg-4">
            <a href="{{route('superAdmin.semesters.index')}}">
                <x-wrapper title="semesters">
                    <h1 class=" text-third-color"> {{$semesters}}</h1>
                </x-wrapper>
            </a>
        </div>
    </div>

@endsection
