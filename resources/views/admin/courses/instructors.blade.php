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
                                <select id="course" name="course_id" class="form-control form-select" required>
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
                                <select id="instructor" disabled name="instructor_id" class="form-control form-select" required>
                                    <option value=""></option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{$instructor->id}}">{{$instructor->first_name}}</option>
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
                    <section>
                        <button type="submit" class="zBtn-one">Submit</button>
                    </section>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">




        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="materialTable">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">days</th>
                    <th scope="col">start_time</th>
                    <th scope="col">end_time</th>
                    <th scope="col">status</th>
                    <th class="w-110 text-center" scope="col">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for days
            $('#days').select2({
                placeholder: "Select Days",
                allowClear: true
            });

            // Enable instructor dropdown based on course selection
            $('#course').change(function() {
                const selectedOption = $(this).find('option:selected');
                const lecturesPerWeek = selectedOption.data('lectures');
                const lectureHours = selectedOption.data('hours');

                // Set the values in the respective fields
                $('#lectures_per_week').val(lecturesPerWeek || '');
                $('#lecture_duration').val(lectureHours || '');

                // Enable instructor dropdown
                const instructorSelect = $('#instructor');
                instructorSelect.prop('disabled', false);
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

                            // Disable the days and times that are already booked
                            data.forEach(function(availability) {
                                let bookedDays = JSON.parse(availability.days);
                                bookedDays.forEach(function(day) {
                                    $('#days option[value="' + day + '"]').prop('disabled', true).trigger('change');
                                });

                                // Check and disable conflicting times
                                // Add your logic to disable conflicting time slots
                                if (availability.start_time && availability.end_time) {
                                    // Example logic can be added here
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching availabilities:', error);
                        }
                    });
                } else {
                    $('#days').find('option').prop('disabled', false).trigger('change');
                    $('#start_time, #end_time').prop('disabled', false);
                }
            });

            // DataTable initialization
            const materialTable = $('#materialTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ route('admin.availability.index') }}`,
                    data: function(d) {
                        d.course_id = $('#course_filter').val();
                        d.instructor_id = $('#chapter_filter').val();
                    }
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'days', name: 'days', orderable: false, searchable: false },
                    { data: 'start_time', name: 'start_time', orderable: false, searchable: false },
                    { data: 'end_time', name: 'end_time', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            // Reload DataTable on filter change
            $('#course_filter, #chapter_filter').change(function() {
                materialTable.draw();
            });
        });
    </script>
@endpush
