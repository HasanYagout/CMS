@extends('layouts.app')

@section('content')
    <x-wrapper title="Add Announcement">
        <form id="announcementForm" method="POST" action="{{ route('instructor.courses.announcement.store') }}">
            @csrf
            <label for="course" class="form-label">{{ __('Course') }} <span class="text-danger">*</span></label>

            <select class="form-control" name="course_id" id="course">
                <option value=""></option>
                @foreach($courses as $course)
                    <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                @endforeach
            </select>

            <label class="form-label" for="announcementType">Announcement Type:</label>
            <select class="form-control" name="announcement_type" id="announcementType">
                <option value=""></option>
                <option value="vote">Vote</option>
                <option value="text">Text</option>
            </select>

            <div id="voteSection">
                <label class="form-label" for="voteTitle">Vote Title:</label>
                <input class="form-control" type="text" name="vote_title" id="voteTitle" placeholder="Enter vote title">

                <div id="choices">
                    <div class="choice">
                        <label class="form-label" for="choice1">Choice 1:</label>
                        <input class="form-control" type="text" name="choices[]" id="choice1" placeholder="Enter choice 1">
                    </div>
                </div>
            </div>

            <div id="textSection">

                <label class="form-label" for="textTitle">Announcement Title:</label>
                <input class="form-control" type="text" name="text_title" id="textTitle" placeholder="Enter announcement title">

                <label class="form-label" for="announcementText">Announcement Text:</label>
                <input type="text" class="form-control"  id="announcementText" name="announcement_text" placeholder="Enter announcement text">
            </div>

            <button class="zBtn-three mt-17" type="button" id="addChoice">Add Choice</button>
            <button class="zBtn-two mt-17" type="submit">Create Announcement</button>
        </form>
    </x-wrapper>
    <x-wrapper title="">
        <x-table id="announcementTable">
            <input type="hidden" id="announcement-route" value="{{route('instructor.courses.announcement.index')}}">
            <th scope="col"><div>{{ __('Course') }}</div></th>
            <th scope="col"><div>{{ __('Type') }}</div></th>
            <th scope="col"><div>{{ __('Title') }}</div></th>
            <th scope="col"><div>{{ __('Status') }}</div></th>
            <th scope="col"><div>{{ __('Action') }}</div></th>

        </x-table>
    </x-wrapper>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#addChoice').hide();

            $('#announcementType').off('change').on('change', function() {
                var type = $(this).val();
                if (type === 'vote') {
                    $('#addChoice').show();
                    $('#voteSection').show();
                    $('#textSection').hide();
                } else if (type === 'text') {
                    $('#voteSection').hide();
                    $('#textSection').show();
                }
            });

            $('#voteSection').hide();
            $('#textSection').hide();

            let maxChoices = 5;
            let currentChoice = 1;

            $('#addChoice').off('click').on('click', function() {
                if (currentChoice < maxChoices) {
                    currentChoice++;
                    $('#choices').append(`
                    <div class="choice">
                        <label class="form-label" for="choice${currentChoice}">Choice ${currentChoice}:</label>
                        <input class="form-control" type="text" name="choices[]" id="choice${currentChoice}" placeholder="Enter choice ${currentChoice}">
                    </div>
                `);
                }
            });
        });
    </script>
    <script src="{{asset('instructor/js/announcement.js')}}"></script>
@endpush
