@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Department">
        <form method="POST" action="{{route('superAdmin.department.store')}}">
            @csrf
            <label for="" class="input-label ">Title</label>
            <input name="name" class="form-control w-25" type="text">
            <button class="zBtn-two mt-2" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <input type="hidden" id="college-route" value="{{ route('superAdmin.department.index') }}">
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
    <div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content zModalTwo-content">

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).on('change', '.toggle-status', function() {
            var adminId = $(this).data('id');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("superAdmin.department.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: adminId,
                    status: status
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        toastr.success('Status updated successfully.');
                    } else {
                        toastr.error('Failed to update status.');
                    }
                },
                error: function() {
                    toastr.error('Error updating status.');
                }
            });
        });
    </script>
    <script src="{{ asset('public/super/js/department.js') }}"></script>

@endpush
