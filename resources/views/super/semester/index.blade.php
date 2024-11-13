@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Semester">
        <form method="POST" action="{{route('superAdmin.semesters.store')}}">
            @csrf
            <label for="" class="form-label">Semester Title</label>
            <input name="title" class="form-control w-25" type="text">
            <button class="zBtn-two mt-2" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="Semesters">
        <input type="hidden" id="semesters-route" value="{{ route('superAdmin.semesters.index') }}">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="semesterTable">
                <thead>
                <tr>
                    <th scope="col">
                        <div>{{ __('Name') }}</div>
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
        <div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
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
                url: '{{ route("superAdmin.semesters.updateStatus") }}',
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
    <script src="{{ asset('assets/super/js/semester.js') }}"></script>

@endpush
