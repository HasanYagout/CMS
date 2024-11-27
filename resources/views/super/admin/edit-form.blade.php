<form class="ajax reset" action="{{route('superAdmin.admin.update',$admin->user_id)}}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update admin') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                </button>
            </div>
        </div>
        {{--        <input type="hidden" name="id" value="{{ $admin->id }}">--}}
        <div class="row">
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="firstName" class="form-label">{{ __('First Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="first_name"
                               value="{{ $admin->first_name }}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="lastName" class="form-label">{{ __('Last Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="last_name"
                               value="{{ $admin->last_name }}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="department" class="form-label">{{ __('Department') }} <span
                                class="text-danger">*</span></label>
                        <select class="primary-form-control" name="department_id">
                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->id }}" {{ $department->id == $admin->department_id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
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
