<form class="ajax reset" action="{{route('instructor.courses.forums.update',$forum->id)}}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update Quiz') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="title" class="form-label">{{ __('Title') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="title" value="{{ $forum->title }}">
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="questions" class="form-label">{{ __('course') }} <span
                                class="text-danger">*</span></label>
                        <select name="course_id" class="form-control" id="">
                            @foreach($courses as $course)

                                <option value="{{$course['id']}}">{{$course['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="questions" class="form-label">{{ __('description') }} <span
                                class="text-danger">*</span></label>
                        <textarea name="description" class="primary-form-control min-h-180 summernoteOne"
                                  id="courseDescription" placeholder="{{ __('Description') }}"
                                  spellcheck="false">{{ $forum->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit"
                class="py-10 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one">{{ __('Update') }}</button>
    </div>
</form>
