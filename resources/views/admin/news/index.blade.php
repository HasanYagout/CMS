@extends('layouts.app')
@section('content')
    <x-wrapper title="">
        <form method="POST" action="{{route('admin.news.store')}}">
            @csrf
            <label for="" class="form-label">News Title</label>
            <input name="title" class="form-control w-25" type="text">
            <label for="" class="form-label">News Description</label>
            <textarea name="description" class="form-control w-25 summernoteOne" type="text"></textarea>
            <button class="zBtn-two mt-2" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="news-route" value="{{ route('admin.news.index') }}">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="newsTable">
                <thead>
                <tr>
                    <th scope="col">
                        <div>{{ __('Name') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('posted By') }}</div>
                    </th>
                    <th scope="col">
                        <div class="text-center">{{ __('Status') }}</div>
                    </th>
                    <th class="w-110 text-center" scope="col">
                        <div>{{ __('Action') }}</div>
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </x-wrapper>
@endsection
@push('script')
    <script src="{{ asset('assets/admin/js/news.js') }}"></script>

@endpush
