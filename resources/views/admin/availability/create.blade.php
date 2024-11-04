@extends('layouts.app')

@section('content')
    <div class="p-30">
        <div class="">
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <form action="{{ route('admin.availability.store') }}" method="POST" id="availability-form">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="instructor" class="form-label">{{ __('Instructor') }} <span class="text-danger">*</span></label>
                                <select name="instructor_id" class="form-control" id="instructor" required>
                                    <option value="" selected disabled>Select an instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->first_name . ' ' . $instructor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="days" class="form-label">Days <span class="text-danger">*</span></label>
                                <select name="days[]" class="instructor-select select2" id="days" multiple="multiple" required></select>
                            </div>
                            <div class="col-lg-4">
                                <label for="available_times" class="form-label">Available Times <span class="text-danger">*</span></label>
                                <div id="available_times" class="d-flex flex-column"></div> <!-- Container for available times -->
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input name="start_time" class="form-control" type="time" id="start_time" required disabled>
                            </div>
                            <div class="col-lg-6">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input name="end_time" class="form-control" type="time" id="end_time" required disabled>
                            </div>
                        </div>
                    </div>
                    <button class="d-flex justify-content-center align-items-center border-0 fs-15 fw-500 lh-25 text-1b1c17 p-13 bd-ra-12 bg-cdef84 hover-bg-one" type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.instructor-select').select2({
                allowClear: true,
                placeholder: "Select days",
                width: '100%'
            });

            // Fetch available days and times when an instructor is selected
            $('#instructor').off('change').on('change', function() {
                const instructorId = $(this).val();
                $('#days').empty(); // Clear previous selections
                $('#available_times').empty(); // Clear previous available times
                $('#start_time').prop('disabled', true);
                $('#end_time').prop('disabled', true);

                if (instructorId) {
                    const url = `{{ route('admin.availability.get', '') }}/${instructorId}`;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            // Populate days based on the fetched availability
                            $.each(data, function(day) {
                                $('#days').append(new Option(day, day));
                            });

                            // Enable the days select
                            $('#days').prop('disabled', false);
                            $('.instructor-select').select2().val(null).trigger('change'); // Clear previous selections

                            // Handle day selection change to show available times
                            $('#days').off('change').on('change', function() {
                                const selectedDays = $(this).val();
                                $('#available_times').empty(); // Clear previous available times
                                $('#start_time').prop('disabled', true);
                                $('#end_time').prop('disabled', true);

                                if (selectedDays.length > 0) {
                                    let allAvailableTimes = []; // Collect all available times

                                    selectedDays.forEach(day => {
                                        const availableTimes = data[day];

                                        // Create checkboxes for each available time
                                        availableTimes.forEach(time => {
                                            // Store all available times in an array
                                            if (!allAvailableTimes.includes(time)) {
                                                allAvailableTimes.push(time);
                                            }
                                            $('#available_times').append(`
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input time-checkbox" value="${time}" id="${day}-${time}">
                                                    <label class="form-check-label" for="${day}-${time}">${time}</label>
                                                </div>
                                            `);
                                        });
                                    });

                                    // Enable time selection
                                    $('.time-checkbox').change(function() {
                                        const selectedTimes = $('.time-checkbox:checked').map(function() {
                                            return $(this).val();
                                        }).get();

                                        if (selectedTimes.length > 0) {
                                            // Set start time to the first selected time
                                            $('#start_time').val(selectedTimes[0]).prop('disabled', false);

                                            // Set end time to the last selected time
                                            $('#end_time').val(selectedTimes[selectedTimes.length - 1]).prop('disabled', false);

                                            // Ensure the end time matches the H:i format
                                            const startHour = $('#start_time').val().split(':')[0];
                                            const endHour = $('#end_time').val().split(':')[0];
                                            const startMinute = $('#start_time').val().split(':')[1];
                                            const endMinute = $('#end_time').val().split(':')[1];

                                            // Only set end time if it's valid
                                            if (endHour < startHour || (endHour === startHour && endMinute <= startMinute)) {
                                                alert('End time must be greater than start time.');
                                                $('#end_time').val(''); // Reset end time
                                            }
                                        } else {
                                            $('#start_time').prop('disabled', true);
                                            $('#end_time').prop('disabled', true);
                                        }
                                    });
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching availability:', error);
                        }
                    });
                }
            });
        });
    </script>
@endpush
