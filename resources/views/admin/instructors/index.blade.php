@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Instructor">
        <form method="POST" action="{{ route('admin.instructors.store') }}">
            @csrf
            <div class="row">
                <div class="mb-3 col-lg-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <input class="form-control" id="first_name" name="first_name" type="text" required>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input class="form-control" id="last_name" name="last_name" type="text" required>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" required>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label" for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label" for="degree">Degree</label>
                    <input class="form-control" id="degree" name="degree" type="text" required>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label" for="birth_date">Birth Date</label>
                    <input class="form-control" id="birth_date" name="birth_date" type="date" required>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label" for="birth_date">Phone</label>
                    <input class="form-control" id="phone" name="phone" type="number" required>
                </div>
                <button class="zBtn-one mt-3" type="submit">Save</button>
            </div>

        </form>

    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="admin-route" value="{{ route('admin.instructors.index') }}">
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
                        <div>{{ __('Gender') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('Degree') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('Phone') }}</div>
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
        <div class="modal fade zModalTwo modal-lg" id="edit-modal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content zModalTwo-content">

                </div>
            </div>
        </div>
    </x-wrapper>
@endsection
@push('script')
    <script>
        $(document).on('change', '.toggle-status', function () {
            var adminId = $(this).data('id');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("admin.instructors.updateStatus") }}',
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
    <script src="{{ asset('assets/admin/js/admin.js') }}"></script>
@endpush
