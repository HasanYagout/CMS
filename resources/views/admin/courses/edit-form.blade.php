<form class="ajax reset" action="{{ route('admin.courses.update', $course->id) }}" method="post" enctype="multipart/form-data" data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update Course') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/delete.svg') }}" alt="" />
                </button>
            </div>
        </div>
        <div class="row rg-25">
            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="courseTitle" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" id="courseTitle" name="title" value="{{ $course->name }}" placeholder="{{ __('Title') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="startDate" class="form-label">{{ __('Start Date') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="primary-form-control" id="startDate" name="start_date" value="{{ $course->start_date }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="endDate" class="form-label">{{ __('End Date') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="primary-form-control" id="endDate" name="end_date" value="{{ $course->end_date }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="semesterId" class="form-label">{{ __('Semester') }} <span class="text-danger">*</span></label>
                        <select class="primary-form-control sf-select-without-search" id="semesterId" name="semester_id">
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ $semester->id == $course->semester_id ? 'selected' : '' }}>
                                    {{ $semester->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 my-4">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="courseDescription" class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
                        <textarea name="description" class="primary-form-control min-h-180 summernoteOne" id="courseDescription" placeholder="{{ __('Description') }}" spellcheck="false">{{ $course->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pb-4 col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap zImage-upload-details">
                        <div class="zImage-inside">
                            <div class="d-flex pb-12"><img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" /></div>
                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                        </div>
                        <label for="courseImage" class="form-label">{{ __('Upload Image') }} <span class="text-mime-type">(jpg, jpeg, png)</span> <span class="text-danger">*</span></label>
                        <div class="upload-img-box">
                            <img src="{{ asset('storage/courses/'.$course->image) }}">
                            <input type="file" name="thumbnail" accept="image/*" onchange="previewFile(this)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="border-0 d-none d-sm-inline-block fs-15 fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one">{{ __('Update') }}</button>
    </div>
</form>
