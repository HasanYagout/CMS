@extends('layouts.app')
@section('content')
    <x-wrapper title="">
        <form method="POST" action="{{route('admin.semesters.store')}}">
            @csrf
            <label for="" class="form-label">Semester Title</label>
            <input name="title" class="form-control w-25" type="text">
            <button class="zBtn-two mt-2" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="semesters-route" value="{{ route('admin.semesters.index') }}">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="semesterTable">
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
    <script src="{{ asset('public/admin/js/semester.js') }}"></script>

@endpush
