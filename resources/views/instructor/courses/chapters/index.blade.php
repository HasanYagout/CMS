@extends('layouts.app')

@section('content')
    <x-wrapper title="Add Chapter">
        <form method="POST" action="{{ route('instructor.courses.chapters.store') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <div class="pb-30"></div>
                <div class="row rg-25">
                    <div class="col-4">
                        <div class="primary-form-group">
                            <div class="primary-form-group-wrap">
                                <label for="instructor" class="form-label">{{ __('Courses') }} <span
                                        class="text-danger">*</span></label>

                                <select name="course_id" class="primary-form-control" id="body" spellcheck="false">
                                    <option value="" selected></option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course->id }}">{{ $course->course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="primary-form-group">
                            <div class="primary-form-group-wrap">
                                <label for="chapters" class="form-label">Chapters</label>
                                <select class="primary-form-control title-select" name="chapters[]"
                                        multiple="multiple"></select>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="zBtn-three mt-3">{{ __('Publish Now') }}</button>
            </div>
        </form>

        <div class="mt-3">
            <input type="hidden" id="chapter-route" value="{{ route('instructor.courses.chapters.index') }}">

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
                    <div class="modal-content"></div>
                </div>
            </div>

        </div>
    </x-wrapper>

    <div id="search-section">
        <div class="collapse" id="collapseExample">
            <div class="alumniFilter">
                <h4 class="fs-18 fw-500 lh-38 text-1b1c17 pb-10">{{ __('Filter your search') }}</h4>
                <div class="filterOptions">
                    <div class="item">
                        <div class="primary-form-group mb-3">
                            <div class="primary-form-group-wrap">
                                <label for="course" class="form-label">Filter By Course</label>

                                <select name="course_id" id="course" class="primary-form-control" spellcheck="false">
                                    <option value="" selected></option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button
                        class="bg-cdef84 border-0 bd-ra-12 py-13 px-26 fs-15 fw-500 lh-25 text-black hover-bg-one advance-filter">{{ __('Search Now') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $(document).on('change', '.toggle-status', function () {
            var chapterId = $(this).data('id'); // Get the chapter ID
            var status = $(this).is(':checked') ? 1 : 0; // Get the new status (1 for checked, 0 for unchecked)
            const url = `{{ route('instructor.courses.chapters.updateStatus', '') }}/${chapterId}`;
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    status: status,
                    _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                success: function (response) {
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    console.error('Error updating status:', xhr);
                }
            });
        });

        $('.title-select').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "Add your chapters",
            allowClear: true
        });

    </script>
    <script src="{{asset('assets/Instructor/js/chapters.js')}}"></script>
@endpush
