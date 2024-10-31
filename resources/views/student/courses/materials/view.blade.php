@extends('layouts.app_course')

@section('content')
    <x-wrapper title="Material Viewer">

        @if($material->type == 'image')
            <div class="material-viewer">
                <img src="{{ asset('public/storage/' . $material->url) }}" alt="{{ $material->title }}" class="img-fluid">
                <a href="{{ asset('public/storage/' . $material->url) }}" class="btn btn-primary" download>Download</a>
            </div>
        @elseif($material->type == 'video')
            <div class="material-viewer">
                <video controls class="w-100">
                    <source src="{{ asset('public/storage/' . $material->url) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <a href="{{ asset('public/storage/' . $material->path) }}" class="btn btn-primary" download>Download</a>
            </div>
        @endif
    </x-wrapper>
@endsection
