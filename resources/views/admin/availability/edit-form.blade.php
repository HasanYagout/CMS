<form class="ajax reset" action="{{ route('admin.availability.update', $availability->id) }}" method="post" data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update Availability') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/delete.svg') }}" alt="" />
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="courseName" class="form-label">{{ __('Course') }} <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-control" id="course_id">
                        @foreach($courses as $course)
                                <option {{$course->id==$availability->course->id ? 'selected':''}} class="primary-form-control" id="course_id" name="course_id" value="{{$course->id}}">{{$course->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="instructorId" class="form-label">{{ __('Instructor') }} <span class="text-danger">*</span></label>
                        <select name="instructor_id" class="form-control" id="instructorId">
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->user_id }}" {{ $instructor->user_id == $availability->instructor->user_id ? 'selected' : '' }}>
                                    {{ $instructor->first_name . ' ' . $instructor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label class="form-label" for="days">Select Days:</label>
                    <select id="days2" name="days[]" class="form-control select2" multiple="multiple" required>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <option value="{{ $day }}" {{ in_array($day, json_decode($availability->days)) ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label class="form-label" for="days">Start time</label>
                    <input name="start_time" value="{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}" type="time" class="form-control">                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label class="form-label" for="days">End time</label>
                    <input name="end_time" value="{{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}" type="time" class="form-control">                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="py-10 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one">{{ __('Update') }}</button>
    </div>
</form>
