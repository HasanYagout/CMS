@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Admin">
        <form method="POST" action="{{route('superAdmin.admin.store')}}">
            @csrf
            <label class="form-label" for="">First Name</label>
            <input class="form-control" name="first_name" type="text">
            <label class="form-label" for="">Last Name</label>
            <input class="form-control" name="last_name" type="text">
            <label class="form-label" for="">Email</label>
            <input class="form-control" name="email" type="email">
            <label class="form-label" for="">Department</label>
            <select class="form-control" name="department">
                <option value="" selected></option>
                @foreach($colleges as $college)
                    <option value="{{$college->id}}">{{$college->name}}</option>
                @endforeach
            </select>
            <button class="zBtn-one mt-3" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="admin-route" value="{{ route('superAdmin.admin.index') }}">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="adminTable">
                <thead>
                <tr>
                    <th scope="col">
                        <div>{{ __('Name') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('Email') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('department') }}</div>
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
    <div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content zModalTwo-content">

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).on('change', '.toggle-status', function () {
            var adminId = $(this).data('id');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("superAdmin.admin.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: adminId,
                    status: status
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        toastr.success('Status updated successfully.');
                    } else {
                        toastr.error('Failed to update status.');
                    }
                },
                error: function () {
                    toastr.error('Error updating status.');
                }
            });
        });

    </script>
    <script src="{{ asset('assets/super/js/admin.js') }}"></script>
@endpush
