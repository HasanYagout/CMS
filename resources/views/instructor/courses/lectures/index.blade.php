@extends('layouts.app')
@section('content')
    <x-wrapper title="add Lectures">
        <form method="POST" action="{{route('instructor.courses.lectures.store')}}" class="d-flex flex-column gap-2"
              enctype="multipart/form-data">
            @csrf
            <label class="form-label" for="course">course</label>
            <select class="form-control" name="course_id" id="course">
                <option value=""></option>
                @foreach($courses as $course)
                    <option value="{{$course->course->id}}">{{$course->course->name}}</option>
                @endforeach
            </select>
            <label class="form-label" for="chapter">chapter:</label>
            <select class="form-control" name="chapter_id" id="chapters">

            </select>
            <label class="form-label" for="chapter">start date:</label>
            <input class="form-control" name="start_date" type="datetime-local">
            <label class="form-label" for="chapter">end date:</label>
            <input class="form-control" name="end_date" type="datetime-local">
            <label class="form-label" for="chapter">title:</label>
            <input class="form-control" name="title" type="text">
            <label class="form-label" for="chapter">Description:</label>

            <textarea name="description" class="summernoteOne">

            </textarea>
            <label class="form-label" for="chapter">Materials:</label>
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details">

                    <label for="zImageUpload" class="form-label text-secondary-color">{{ __('Upload Image') }} <span
                            class="text-mime-type">(jpg,jpeg,png)</span></label>
                    <!-- Attachment preview -->
                    <div id="files-area" class="pb-10">
                                    <span id="filesList">
                                        <span id="files-names"></span>
                                    </span>
                    </div>
                    <!-- Add image/video - post button -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap g-10">
                        <!-- Add image/video -->
                        <div class="d-flex align-items-center cg-15">
                            <p class="fs-16 lh-18 fw-500 text-707070">{{ __('Add to your post') }}:</p>
                            <div class="align-items-center cg-10 d-flex flex-shrink-0">
                                <label for="mAttachment1"><img
                                        onerror="this.src='{{asset('assets/images/no-image.jpg')}}'"
                                        src="{{ asset('assets/images/icon/post-photo.svg') }}"
                                        alt=""/></label>
                                <input type="file" name="images[]"
                                       accept=".png,.jpg,.svg,.jpeg,.gif,.mp4,.mov,.avi,.mkv,.webm,.flv"
                                       id="mAttachment1" class="d-none" multiple/>
                                <label for="mAttachment1"><img
                                        onerror="this.src='{{asset('assets/images/no-image.jpg')}}'"
                                        src="{{ asset('assets/images/icon/post-video.svg') }}"
                                        alt=""/></label>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <label class="form-label" for="chapter">Zoom Link:</label>

            <input name="zoom_link" class="form-control" type="text">
            <button class="zBtn-one" type="submit">Save</button>
        </form>
    </x-wrapper>
    <x-wrapper title="Lectures">
        <x-table id="lectureTable">
            <th scope="col">
                <div>{{ __('Course') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Chapter') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Lecture') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Zoom Link') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('Start Date') }}</div>
            </th>
            <th scope="col">
                <div>{{ __('End Date') }}</div>
            </th>
            <th scope="col">
                <div class="text-center">{{ __('Status') }}</div>
            </th>
            <th class="w-110 text-center" scope="col">
                <div>{{ __('Action') }}</div>
            </th>
        </x-table>
        <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                </div>
            </div>
        </div>
    </x-wrapper>
@endsection
@push('script')
    <script>
        $(document).off().on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapters ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{route('instructor.courses.lectures.updateStatus','')}}/${chapterId}`
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            $('#course').on('change', function () {
                const courseId = $(this).val();
                const chapterSelect = $('#chapters');
                chapterSelect.empty().append('<option value="">-- Select Chapter --</option>');

                if (courseId) {
                    // Fetch chapters
                    $.ajax({
                        url: `{{ route('instructor.courses.chapters.get', '') }}/${courseId}`,
                        method: 'GET',
                        success: function (data) {
                            $.each(data.chapters, function (index, chapter) {
                                chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                            });
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching chapters:', error);
                        }
                    });
                }
            });


            if ($.fn.dataTable.isDataTable('#lectureTable')) {
                $('#lectureTable').DataTable().clear().destroy();
            }

            const courseSelect = document.getElementById('course');
            const lectureTable = $('#lectureTable'); // Use jQuery for DataTables

            courseSelect.addEventListener('change', function () {
                const courseId = this.value;

                // Reload DataTable with the selected course ID
                lectureTable.DataTable().ajax.url(`{{ route('instructor.courses.lectures.index') }}?course_id=${courseId}`).load();
            });

            // Initialize the DataTable
            lectureTable.DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: `{{ route('instructor.courses.lectures.index') }}`,
                    data: function (d) {
                        d.course_id = courseSelect.value; // Include selected course ID
                    }
                },
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-angles-left'></i>",
                        next: "<i class='fa-solid fa-angles-right'></i>",
                    },
                    searchPlaceholder: "Search Lectures",
                    search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
                },
                dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"d-flex align-items-center cg-5"<"tableSearch float-start"f><"z-filter-button">>><"col-sm-6"<"tableLengthInput float-end"l>><"col-sm-12"<"z-filter-block">>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',

                columns: [
                    // { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {data: 'course', name: 'course'},
                    {data: 'chapter', name: 'chapter'},
                    {data: 'lecture', name: 'lecture'},
                    {data: 'zoom_link', name: 'zoom_link'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action'},
                ]
            });
        });
    </script>

@endpush
