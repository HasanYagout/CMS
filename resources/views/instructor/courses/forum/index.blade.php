@extends('layouts.app')

@section('content')
    <x-wrapper title="Create Forum">
        <form method="POST" action="{{route('instructor.courses.forums.store')}}">
            @csrf
            <label class="form-label" for="course">Course</label>
            <select class="form-control" name="course_id">
                <option value="" selected disabled>Select a Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
            <label class="form-label" for="title">Title</label>
            <input class="form-control" name="title" type="text" required>
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control" name="description" rows="5" required></textarea>
            <button class="zBtn-one mt-3" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <x-table id="forumTable">
            <input type="hidden" id="forum-route" value="{{route('instructor.courses.forums.index')}}">
            <th scope="col">
                <div>{{ __('Title') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Course') }}</div>
            </th>
            <th scope="col">
                <div class="text-center">{{ __('Status') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Action') }}</div>
            </th>

        </x-table>
    </x-wrapper>
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapters ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{route('instructor.courses.forums.updateStatus','')}}/${chapterId}`
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
    <script src="{{asset('assets/Instructor/js/forum.js')}}"></script>

@endpush
