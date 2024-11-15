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
    <div class="modal fade zModalTwo modal-lg" id="edit-modal" aria-hidden="true" tabindex="-1">
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
                url: '{{ route("admin.news.updateStatus") }}',
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
    <script src="{{ asset('assets/admin/js/news.js') }}"></script>
@endpush
