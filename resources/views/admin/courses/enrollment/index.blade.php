@extends('layouts.app')
@section('content')
    <x-wrapper title="enrollments">
        <input type="hidden" value="{{route('admin.courses.students.index')}}">
        <x-table id="enrollmentTable">
            <th scope="col">
                <div>{{ __('Course') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Student') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Payment Status') }}</div>
            </th>
            <th scope="col">
                <div class="text-center">{{ __('Status') }}</div>
            </th>
        </x-table>
    </x-wrapper>
@endsection
@push('script')
    <script>
        $(document).on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapters ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{route('admin.courses.students.updateStatus','')}}/${chapterId}`
            $.ajax({
                url: url, // Update with your actual route
                type: 'POST',
                data: {
                    status: status,
                    _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                success: function (response) {
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    // Optionally, handle error response
                    console.error('Error updating status:', xhr);
                }
            });
        });

    </script>
    <script src="{{asset('assets/Instructor/js/students.js')}}"></script>
@endpush
