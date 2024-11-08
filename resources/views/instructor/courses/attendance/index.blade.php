@extends('layouts.app')

@section('content')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        td {
            text-align: center;
        }
    </style>
    <label for="course">Course</label>
    <select class="form-control" id="course">
        <option value="">Select a Course</option>
        @foreach($courses as $course)
            <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
        @endforeach
    </select>
    <div class="container">
        <h2>Course Attendance</h2>
        <table id="attendanceTable">
            <thead>
            <tr>
                <th>Student Name</th>
                <th>Lecture</th>
                <th>Attended</th>
            </tr>
            </thead>
            <tbody>
            <!-- Add student and lecture rows dynamically -->
            </tbody>
        </table>
        <button id="submitAttendance" class="btn btn-primary mt-3">Submit Attendance</button>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            let attendanceData = [];

            $('#course').change(function () {
                const courseId = $(this).val();
                const students = [];

                if (courseId) {
                    $.ajax({
                        url: "{{ route('instructor.courses.attendance.index') }}",
                        data: {course_id: courseId},
                        method: 'GET',
                        success: function (response) {
                            if (response.data && Array.isArray(response.data)) {
                                response.data.forEach(student => {
                                    students.push({
                                        id: student.id,
                                        first_name: student.first_name,
                                        last_name: student.last_name,
                                        lectures: student.lectures
                                    });
                                });

                                const $tableBody = $('#attendanceTable tbody');
                                $tableBody.empty(); // Clear previous data

                                students.forEach(student => {
                                    student.lectures.forEach((lecture, index) => {
                                        const $tr = $('<tr></tr>');

                                        // Only display student name on the first lecture row
                                        if (index === 0) {
                                            const $tdName = $('<td></td>').text(student.first_name + ' ' + student.last_name).attr('rowspan', student.lectures.length);
                                            $tr.append($tdName);
                                        }

                                        // Lecture number
                                        const $tdLecture = $('<td></td>').text(lecture.name);
                                        $tr.append($tdLecture);

                                        // Attendance checkbox
                                        const $tdCheckbox = $('<td></td>');
                                        const $checkbox = $('<input></input>', {
                                            type: 'checkbox',
                                            class: 'attendance-checkbox',
                                            'data-student-id': student.id,
                                            'data-lecture-id': lecture.id
                                        });
                                        $tdCheckbox.append($checkbox);
                                        $tr.append($tdCheckbox);

                                        $tableBody.append($tr);
                                    });
                                });
                            } else {
                                console.error('Invalid response data format:', response.data);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching course info:', error);
                        }
                    });
                }
            });

            // Handle attendance checkbox toggle
            $('#attendanceTable').on('change', '.attendance-checkbox', function () {
                const studentId = $(this).data('student-id');
                const lectureId = $(this).data('lecture-id');
                const isChecked = $(this).is(':checked');

                const attendanceEntry = {student_id: studentId, lecture_id: lectureId, status: isChecked ? 1 : 0};

                // Remove any existing entry for the same student and lecture
                attendanceData = attendanceData.filter(entry => !(entry.student_id === studentId && entry.lecture_id === lectureId));

                // Add the new or updated entry
                attendanceData.push(attendanceEntry);

                console.log(attendanceData); // For debugging
            });

            // Handle submit button click
            $('#submitAttendance').click(function () {
                // Send an AJAX request to update attendance
                $.ajax({
                    url: "{{ route('instructor.courses.attendance.update') }}", // Replace with your actual route for updating attendance
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        attendanceData: attendanceData
                    },
                    success: function (response) {
                        console.log('Attendance updated successfully');
                    },
                    error: function (error) {
                        console.error('Error updating attendance:', error);
                    }
                });
            });
        });
    </script>
@endpush
