@extends('layouts.app')
@section('content')
    <x-wrapper title="add Lectures">
        <form method="POST" action="{{route('instructor.courses.lectures.store')}}" enctype="multipart/form-data">
            @csrf
            <label class="form-label" for="course">course</label>
            <select class="form-control" name="course_id" id="course">
            @foreach($courses as $course)
                    <option value="{{$course->course->id}}">{{$course->course->name}}</option>
            @endforeach
            </select>
            <label class="form-label" for="chapter">chapter:</label>
            <select class="form-control" name="chapter_id" id="chapter">

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

                    <label for="zImageUpload" class="form-label text-secondary-color">{{ __('Upload Image') }} <span class="text-mime-type">(jpg,jpeg,png)</span> <span class="text-danger">*</span></label>
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
                                        onerror="this.src='{{asset('public/assets/images/no-image.jpg')}}'"
                                        src="{{ asset('public/assets/images/icon/post-photo.svg') }}"
                                        alt="" /></label>
                                <input type="file" name="images[]"
                                       accept=".png,.jpg,.svg,.jpeg,.gif,.mp4,.mov,.avi,.mkv,.webm,.flv"
                                       id="mAttachment1" class="d-none" multiple />
                                <label for="mAttachment1"><img
                                        onerror="this.src='{{asset('public/assets/images/no-image.jpg')}}'"
                                        src="{{ asset('public/assets/images/icon/post-video.svg') }}"
                                        alt="" /></label>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <label class="form-label" for="chapter">Zoom Link:</label>

            <input name="zoom_link" class="form-control" type="text">
            <button type="submit">Save</button>
        </form>
    </x-wrapper>
@endsection
@push('script')
    <script>
        $('#course').off('change').on('change', function() {
            const courseId = $(this).val();
            const chapterSelect = $('#chapters');
            chapterSelect.empty().append('<option value="">-- Select Chapter --</option>');

            if (courseId) {
                const url = `{{ route('instructor.courses.chapters.get', '') }}/${courseId}`;
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

    </script>
@endpush
