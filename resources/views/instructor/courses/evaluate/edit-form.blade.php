<form class="ajax reset" action="{{route('instructor.courses.forums.update',$student->id)}}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Final Evaluation') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h1 class="text-primary-color">{{\Illuminate\Support\Str::ucfirst( $student->first_name .' ' .$student->last_name )}}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="questions" class="form-label">{{ __('Final Comment') }} <span
                                class="text-danger">*</span></label>
                        <textarea name="description"
                                  class="primary-form-control summernoteOne min-h-180" id="description"
                                  placeholder="Details" spellcheck="false"></textarea>
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
