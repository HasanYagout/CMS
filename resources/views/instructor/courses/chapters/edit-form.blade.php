<form class="ajax reset" action="{{route('instructor.courses.chapters.update',$chapter->id)}}" method="post" data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update chapter') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/delete.svg') }}" alt="" />
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="firstName" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="title" value="{{ $chapter->title }}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="course" class="form-label">{{ __('Course') }} <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-control" id="course">
                            @foreach($availabilities as $availability)
                                @if($availability->course) <!-- Ensure course exists -->
                                <option value="{{ $availability->course->id }}">
                                    {{ $availability->course->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="py-10 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one">{{ __('Update') }}</button>
    </div>
</form>
