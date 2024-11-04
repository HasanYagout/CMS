<form class="ajax reset" action="{{ route('Admin.courses.update') }}" method="post"
      enctype="multipart/form-data"
    data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{__('Update New')}}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img
                        src="{{asset('assets/images/icon/delete.svg')}}" alt="" /></button>
            </div>
        </div>
        <div class="row rg-25">
            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Title') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" value="{{ $course->name }}" name="title"
                            placeholder="{{ __('Title') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="category_id" for="BatchName" class="form-label">{{ __('Category') }} <span
                                class="text-danger">*</span></label></label>

                        <select class="primary-form-control sf-select-without-search" id="category_id"
                            name="category_id">
                            @foreach ($instructors as $instructor)
                            <option {{$instructor->id==$course->instructor_id?'selected':''}}>{{ $instructor->first_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="BatchName" class="form-label">{{ __('Status') }} <span
                                class="text-danger">*</span></label>
                        <select class="primary-form-control sf-select-without-search" id="BatchName" name="status">
                            @foreach($availabilities as $availability)
                                @php
                                    $days = json_decode($availability->days, true); // Decode to an array
                                @endphp
                                <option value="{{$availability->id}}">{{ implode(', ', $days) }}</option> <!-- Join the days with a comma -->
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="BatchName" class="form-label">{{ __('Status') }} <span
                                class="text-danger">*</span></label>
                        <select class="primary-form-control sf-select-without-search" id="BatchName" name="status">
                            @foreach($semesters as $semester)

                                <option value="{{$semester->id}}">{{$semester->name}}</option> <!-- Join the days with a comma -->
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 my-4">
                <div class="primary-form-group">
                  <div class="primary-form-group-wrap">
                    <label for="eventDescription" class="form-label">{{__('Description')}} <span
                        class="text-danger">*</span></label></label>
                    <textarea name="details" class="primary-form-control min-h-180 summernoteOne" id="eventDescription" placeholder="{{ __("Description") }}" spellcheck="false">{{ $course->description }}</textarea>
                  </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit"
            class="border-0 d-none d-sm-inline-block fs-15 fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one">{{
            __('Update') }}</button>
    </div>
</form>
