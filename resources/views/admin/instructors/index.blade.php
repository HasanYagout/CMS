@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Instructor">
        <form method="POST" action="{{route('admin.instructors.store')}}">
            @csrf
            <label class="form-label"  for="">First Name</label>
            <input class="form-control" name="first_name" type="text">
            <label class="form-label"  for="">Last Name</label>
            <input class="form-control" name="last_name" type="text">
            <label class="form-label" for="">Email</label>
            <input class="form-control" name="email" type="email">
            <button class="zBtn-one mt-3" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="admin-route" value="{{ route('admin.instructors.index') }}">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="adminTable">
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
    <script src="{{ asset('public/admin/js/admin.js') }}"></script>
@endpush
