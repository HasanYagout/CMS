@extends('layouts.app')
@section('content')
    <x-wrapper title="">
        <form method="POST" action="{{route('admin.college.store')}}">
            @csrf
            <label for="" class="input-label ">Title</label>
            <input name="name" class="form-control w-25" type="text">
            <button class="zBtn-two mt-2" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="college-route" value="{{ route('admin.college.index') }}">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="collegeTable">
                <thead>
                <tr>
                    <th scope="col"><div>{{ __('Name') }}</div></th>
                    <th scope="col"><div>{{ __('Status') }}</div></th>
                    <th class="w-110 text-center" scope="col"><div>{{ __('Action') }}</div></th>
                </tr>
                </thead>
            </table>
        </div>
    </x-wrapper>
@endsection
@push('script')
    <script src="{{ asset('public/admin/js/college.js') }}"></script>

@endpush
