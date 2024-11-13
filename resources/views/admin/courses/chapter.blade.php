@extends('layouts.app')
@section('content')
    <div class="p-30">

        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <form method="POST" action="{{route('admin.courses.chapters.store')}}" enctype="multipart/form-data">
                @csrf
                <div>
                    <div class="pb-30"></div>
                    <div class="row rg-25">
                        <div class="col-4">
                            <div class="primary-form-group">
                                <div class="primary-form-group-wrap">
                                    <label for="skills" class="form-label">Skills</label>
                                    <select class="form-control title-select" name="titles[]" multiple="multiple">

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="primary-form-group">
                                <div class="primary-form-group-wrap">
                                    <label for="instructor" class="form-label">{{__('Courses')}} <span
                                            class="text-danger">*</span></label>

                                    <select name="course_id" class="primary-form-control " id="body" spellcheck="false">
                                        <option value="" selected></option>
                                        @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{$course->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="d-inline-flex py-13 px-26 bd-ra-12 bg-cdef84 fs-15 fw-500 lh-25 text-black mt-30 hover-bg-one border-0">{{__('Publish Now')}}</button>
                </div>
            </form>
        </div>

        <div>
            <input type="hidden" id="chapter-route" value="{{ route('admin.courses.chapters.index') }}">
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-16">
            </div>
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <div class="primary-form-group mb-3">
                    <div class="primary-form-group-wrap col-3">
                        <select name="course_id" id="course" class="primary-form-control" spellcheck="false">
                            <option value="" selected></option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive zTable-responsive">
                    <table class="table zTable" id="chapterTable">
                        <thead>
                        <tr>
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
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        $('.title-select').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "Add your skills",
            allowClear: true
        });
    </script>
    <script>document.addEventListener('DOMContentLoaded', function () {
            const courseSelect = document.getElementById('course');
            const chapterTable = $('#chapterTable'); // Use jQuery for DataTables

            courseSelect.addEventListener('change', function () {
                const courseId = this.value;

                // Reload DataTable with the selected course ID
                chapterTable.DataTable().ajax.url(`{{ route('admin.courses.chapters.index') }}?course_id=${courseId}`).load();
            });

            // Initialize the DataTable
            chapterTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ route('admin.courses.chapters.index') }}`,
                    data: function (d) {
                        d.course_id = courseSelect.value; // Include selected course ID
                    }
                },
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
