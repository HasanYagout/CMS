@extends('layouts.app')
@section('content')
    <x-wrapper title="Add Chapter">
        <form method="POST" action="{{route('instructor.courses.chapters.store')}}" enctype="multipart/form-data">
            @csrf
            <div>
                <div class="pb-30"></div>
                <div class="row rg-25">
                    <div class="col-4">
                        <div class="primary-form-group">
                            <div class="primary-form-group-wrap">
                                <label for="instructor" class="form-label">{{__('Courses')}} <span
                                        class="text-danger">*</span></label>
                                <select name="course_id" class="primary-form-control " id="body" spellcheck="false">
                                    <option value="" selected></option>
                                    @foreach($courses as $course)
                                        <option value="{{$course->course_id}}">{{$course->course->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="primary-form-group">
                            <div class="primary-form-group-wrap">
                                <label for="chapters" class="form-label">Chapters</label>
                                <select class="primary-form-control title-select" name="chapters[]" multiple="multiple">
                                </select>
                            </div>
                        </div>
                    </div>


                </div>

                <button type="submit" class="zBtn-three mt-3">{{__('Publish Now')}}</button>
            </div>
        </form>
        <div class="mt-3">
            <input type="hidden" id="chapter-route" value="{{ route('admin.courses.chapters.index') }}">
            <div class="primary-form-group mb-3">
                <div class="primary-form-group-wrap col-3">
                    <label for="course" class="form-label">Filter By Course</label>
                    <select name="course_id" id="course" class="primary-form-control" spellcheck="false">
                        <option value="" selected></option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_id }}">{{ $course->course->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <x-table id="chapterTable">
                <th scope="col">
                    <div>{{ __('Title') }}</div>
                </th>
                <th scope="col">
                    <div>{{ __('Course') }}</div>
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


        </div>
    </x-wrapper>

@endsection
@push('script')
    <script>
        $(document).on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapters ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{route('instructor.courses.chapters.updateStatus','')}}/${chapterId}`
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
        $('.title-select').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "Add your chapters",
            allowClear: true
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if ($.fn.dataTable.isDataTable('#chapterTable')) {
                $('#chapterTable').DataTable().clear().destroy();
            }
            const courseSelect = document.getElementById('course');
            const chapterTable = $('#chapterTable'); // Use jQuery for DataTables

            courseSelect.addEventListener('change', function () {
                const courseId = this.value;

                // Reload DataTable with the selected course ID
                chapterTable.DataTable().ajax.url(`{{ route('instructor.courses.chapters.index') }}?course_id=${courseId}`).load();
            });

            // Initialize the DataTable
            chapterTable.DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: `{{ route('instructor.courses.chapters.index') }}`,
                    data: function (d) {
                        d.course_id = courseSelect.value; // Include selected course ID
                    }
                },
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-angles-left'></i>",
                        next: "<i class='fa-solid fa-angles-right'></i>",
                    },
                    searchPlaceholder: "Search Courses",
                    search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
                },
                dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"d-flex align-items-center cg-5"<"tableSearch float-start"f><"z-filter-button">>><"col-sm-6"<"tableLengthInput float-end"l>><"col-sm-12"<"z-filter-block">>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',

                columns: [
                    // { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {data: 'title', name: 'title'},
                    {data: 'course', name: 'course'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action'},
                ]
            });
        });
    </script>
@endpush
