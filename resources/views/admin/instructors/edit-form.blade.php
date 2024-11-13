<form class="ajax reset" action="{{ route('admin.instructors.update', $instructor->user_id) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update Instructor') }}</h4>
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
                        <label for="firstName" class="form-label">{{ __('First Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="first_name"
                               value="{{ $instructor->first_name }}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="lastName" class="form-label">{{ __('Last Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="last_name"
                               value="{{ $instructor->last_name }}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="email" class="form-label">{{ __('Email') }} <span
                                class="text-danger">*</span></label>
                        <input type="email" class="primary-form-control" name="email"
                               value="{{ $instructor->user->email }}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="gender" class="form-label">{{ __('Gender') }} <span
                                class="text-danger">*</span></label>
                        <select class="primary-form-control" name="gender">
                            <option
                                value="male" {{ $instructor->gender == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option
                                value="female" {{ $instructor->gender == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="degree" class="form-label">{{ __('Degree') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="degree" value="{{ $instructor->degree }}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="birth_date" class="form-label">{{ __('Birth Date') }} <span
                                class="text-danger">*</span></label>
                        <input type="date" class="primary-form-control" name="birth_date"
                               value="{{ $instructor->birth_date}}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="birth_date" class="form-label">{{ __('Phone') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" class="primary-form-control" name="phone" maxlength="9"
                               value="{{ $instructor->phone}}">
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
