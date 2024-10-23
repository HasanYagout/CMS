@extends('layouts.app')

@section('content')
    <div class="p-30">
        <div class="container bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <h2 class="text-primary-color">Add Materials</h2>

            <form action="{{ route('admin.courses.materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="container my-4 row gap-5">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="text-secondary-color" for="course">Select Course:</label>
                                <select id="course" name="course_id" class="form-control form-select" required>
                                    <option value="">-- Select Course --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="chapter">Select Chapter:</label>
                                <select id="chapter" name="chapter_id" class="form-control form-select" required>
                                    <option value="">-- Select Chapter --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="materials-container">
                        <h4 class="text-primary-color">Materials</h4>
                        <div class="col-lg-6">
                            <div class="material-item">
                                <div class="primary-form-group my-4">
                                    <div class="primary-form-group-wrap">
                                        <label class="form-label" for="titles[]">Title:</label>
                                        <input type="text" name="titles[]" class="primary-form-control" required>
                                    </div>
                                </div>

                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap zImage-upload-details">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12"><img src="{{ asset('public/assets/images/icon/upload-img-1.svg') }}" alt="" /></div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                                        </div>
                                        <label for="zImageUpload" class="form-label text-secondary-color">{{ __('Upload Image') }} <span class="text-mime-type">(jpg,jpeg,png)</span> <span class="text-danger">*</span></label>
                                        <div class="upload-img-box">
                                            <input type="file" name="images[]" accept="image/*" onchange="previewFile(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section>
                        <button type="button" id="add-material" class="zBtn-two">Add Another Material</button>
                        <button type="submit" class="zBtn-one">Submit</button>
                    </section>
            </form>
        </div>
    </div>

    <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
        <div class="primary-form-group mb-3">
            <div class="primary-form-group-wrap col-3">
                <select name="course_id" id="course_filter" class="primary-form-control" spellcheck="false">
                    <option value="" selected>-- Filter by Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="primary-form-group mb-3">
            <div class="primary-form-group-wrap col-3">
                <select name="chapter_id" id="chapter_filter" class="primary-form-control" spellcheck="false">
                    <option value="" selected>-- Filter by Chapter --</option>
                </select>
            </div>
        </div>

        <div class="table-responsive zTable-responsive">
            <table class="table zTable" id="materialTable">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Status</th>
                    <th class="w-110 text-center" scope="col">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const path = "{{ asset('public/assets/images/icon/upload-img-1.svg') }}";

            $('#course').change(function() {
                const courseId = $(this).val();
                const chapterSelect = $('#chapter');
                chapterSelect.empty().append('<option value="">-- Select Chapter --</option>');

                if (courseId) {
                    const url = `{{ route('admin.courses.chapters.get', '') }}/${courseId}`;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, chapter) {
                                chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching chapters:', error);
                        }
                    });
                }
            });

            $('#add-material').on('click', function() {
                const newMaterial = `
                <div class="col-lg-6">
                    <div class="primary-form-group my-4">
                        <div class="primary-form-group-wrap">
                            <label class="form-label text-secondary-color" for="titles[]">Title:</label>
                            <input type="text" name="titles[]" class="primary-form-control" required>
                        </div>
                    </div>
                    <div class="primary-form-group">
                        <div class="primary-form-group-wrap zImage-upload-details">
                            <div class="zImage-inside">
                                <div class="d-flex pb-12"><img src="${path}" alt="" /></div>
                                <p class="fs-15 fw-500 lh-16 text-1b1c17">Drag & drop files here</p>
                            </div>
                            <label for="zImageUpload" class="form-label">Upload Image <span class="text-mime-type">(jpg,jpeg,png)</span> <span class="text-danger">*</span></label>
                            <div class="upload-img-box">
                                <input type="file" name="images[]" accept="image/*" onchange="previewFile(this)">
                            </div>
                        </div>
                    </div>
                </div>`;
                $('#materials-container').append(newMaterial);
            });

            $('#course_filter').change(function() {
                const courseId = $(this).val();
                const chapterSelect = $('#chapter_filter');
                chapterSelect.empty().append('<option value="">-- Filter by Chapter --</option>');

                if (courseId) {
                    const url = `{{ route('admin.courses.chapters.get', '') }}/${courseId}`;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, chapter) {
                                chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching chapters:', error);
                        }
                    });
                }

                materialTable.ajax.reload();
            });

            $('#chapter_filter').change(function() {
                materialTable.ajax.reload();
            });

            const materialTable = $('#materialTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ route('admin.courses.materials.index') }}`,
                    data: function(d) {
                        d.course_id = $('#course_filter').val();
                        d.chapter_id = $('#chapter_filter').val();
                    }
                },
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
