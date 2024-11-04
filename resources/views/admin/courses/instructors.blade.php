@extends('layouts.app')

@section('content')
    <div class="p-30">
        <div class="container bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <h2 class="text-primary-color">Link Course with Instructor</h2>

            <form action="{{ route('admin.courses.instructors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="container my-4 row gap-5">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="text-secondary-color" for="course">Select Course:</label>
                                <select id="course_id" name="course_id" class="form-control form-select" required>
                                    <option value="">-- Select Course --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" data-lectures="{{ $course->lectures_per_week }}" data-hours="{{ $course->lecture_hours }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="lectures_per_week">Lectures per Week:</label>
                                <input type="number" disabled id="lectures_per_week" name="lectures_per_week" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="lecture_duration">Lecture Duration (hours):</label>
                                <input type="number" disabled id="lecture_duration" name="lecture_duration" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="chapter">Select Instructor:</label>
                                <select id="instructor" name="instructor_id" class="form-control form-select" required>
                                    <option value=""></option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->user_id }}">{{ $instructor->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="start_time">Start Time:</label>
                                <input type="time" id="start_time" name="start_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="end_time">End Time:</label>
                                <input type="time" id="end_time" name="end_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="days">Select Days:</label>
                                <select id="days" name="days[]" class="form-control select2" multiple="multiple" required>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="availability-section mt-3">
                                <h5 class="text-third-color">Instructor Availability</h5>
                                <table id="availability-table" class="table table-bordered" style="display: none;">
                                    <thead>
                                    <tr>
                                        <th class="bg-third-color text-white">Day</th>
                                        <th class="bg-secondary-color text-white">Available Times</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Availability data will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <section>
                        <button type="submit" class="zBtn-one">Submit</button>
                    </section>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="AvailabilityTable">
                <thead>
                <tr>
                    <th scope="col"><div>{{ __('Name') }}</div></th>
                    <th scope="col"><div>{{ __('Instructor') }}</div></th>
                    <th scope="col"><div>{{ __('days') }}</div></th>
                    <th scope="col"><div>{{ __('start time') }}</div></th>
                    <th scope="col"><div>{{ __('end time') }}</div></th>
                    <th scope="col"><div>{{ __('status') }}</div></th>
                    <th class="w-110 text-center" scope="col"><div>{{ __('Action') }}</div></th>
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
    </div>
@endsection

@push('script')
    <script>
        $(document).on('change', '.toggle-status', function() {
            var adminId = $(this).data('id');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("admin.courses.instructors.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: adminId,
                    status: status
                },
                success: function(response) {
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

        $(document).ready(function() {
            // Initialize Select2 for days
            $('#days').select2({
                placeholder: "Select Days",
                allowClear: true
            });

            // Initially disable the instructor select
            $('#instructor').prop('disabled', true);

            $('#course_id').change(function() {
                const courseId = $(this).val();
                const lectureDuration = $('#lecture_duration');
                const lecturesPerWeek = $('#lectures_per_week');
                const instructors = $('#instructor');

                if (courseId) {
                    // Enable the instructor select
                    instructors.prop('disabled', false);

                    // Fetch course details and instructors
                    const url = `{{ route('admin.courses.info', '') }}/${courseId}`;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            // Update course details
                            lecturesPerWeek.val(data.course.lectures);
                            lectureDuration.val(data.course.hours);
                            // Update instructor options
                            instructors.empty();
                            instructors.append(`<option value="">-- Select Instructor --</option>`);

                            data.instructors.forEach(function(instructor) {
                                instructors.append(`<option value="${instructor.instructor.user_id}">${instructor.instructor.first_name} ${instructor.instructor.last_name}</option>`);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching course info:', error);
                        }
                    });
                } else {
                    // Disable the instructor select if no course is selected
                    instructors.prop('disabled', true);
                    instructors.empty();
                    lecturesPerWeek.val('');
                    lectureDuration.val('');
                }
            });

            // Fetch and disable the already booked days and times for the selected instructor
            $('#instructor').change(function() {
                const instructorId = $(this).val();

                if (instructorId) {
                    const url = `{{ route('admin.availability.get', '') }}/${instructorId}`;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            // Reset days and times
                            $('#days').find('option').prop('disabled', false).trigger('change');
                            $('#start_time, #end_time').prop('disabled', false);

                            // Clear previous availability
                            $('#availability-table tbody').empty();

                            // Process the availability data
                            Object.keys(data).forEach(day => {
                                const times = data[day];
                                const timeSlots = Array.isArray(times) ? times.join(', ') : Object.values(times).join(', ');

                                // Append row to table
                                let row = `<tr><td>${day}</td><td>${timeSlots}</td></tr>`;
                                $('#availability-table tbody').append(row);

                                // Disable the booked times
                                if (Array.isArray(times)) {
                                    times.forEach(time => {
                                        $(`#start_time option[value="${time}"], #end_time option[value="${time}"]`).prop('disabled', true).trigger('change');
                                    });
                                } else {
                                    Object.values(times).forEach(time => {
                                        $(`#start_time option[value="${time}"], #end_time option[value="${time}"]`).prop('disabled', true).trigger('change');
                                    });
                                }
                            });

                            // Show the availability table
                            $('#availability-table').show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching availabilities:', error);
                        }
                    });
                } else {
                    $('#days').find('option').prop('disabled', false).trigger('change');
                    $('#start_time, #end_time').prop('disabled', false);
                    $('#availability-table').hide(); // Hide the table if no instructor is selected
                }
            });

            if ($.fn.dataTable.isDataTable('#AvailabilityTable')) {
                $('#AvailabilityTable').DataTable().clear().destroy();
            }
            const AvailabilityTable = $('#AvailabilityTable').DataTable({
                pageLength: 10,
                ordering: true,
                serverSide: true,
                processing: true,
                responsive: true,
                searching: true,
                ajax: {
                    url: `{{ route('admin.availability.index') }}`,
                    data: function(d) {
                        d.course_id = $('#course_filter').val();
                        d.instructor_id = $('#chapter_filter').val();
                    }
                },
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-angles-left'></i>",
                        next: "<i class='fa-solid fa-angles-right'></i>",
                    },
                    searchPlaceholder: "Search Instructors",
                    search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
                },
                dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"d-flex align-items-center cg-5"<"tableSearch float-start"f><"z-filter-button">>><"col-sm-6"<"tableLengthInput float-end"l>><"col-sm-12"<"z-filter-block">>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',

                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'instructor', name: 'instructor' },
                    { data: 'days', name: 'days', orderable: false, searchable: false },
                    { data: 'start_time', name: 'start_time', orderable: false, searchable: false },
                    { data: 'end_time', name: 'end_time', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            // Reload DataTable on filter change
            $('#course_filter, #chapter_filter').change(function() {
                AvailabilityTable.draw();
            });
        });
    </script>
@endpush
