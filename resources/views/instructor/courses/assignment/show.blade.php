<form class="ajax reset"
      action="{{route('instructor.courses.assignments.submitGrade',['student_id'=>$assignment->student->id,'assignment'=>$assignment->instructor_assignments_id])}}"
      method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update Assignment') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">

                        <h1 class="text-center text-primary-color">{{ $assignment->student->first_name .' '.$assignment->student->last_name }}</h1>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">


            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap flex-wrap d-flex gap-4">
                        @if(!empty($assignment->path))
                            @php
                                $filePaths = json_decode($assignment->path, true);
                            @endphp

                            @foreach($filePaths as $filePath)
                                <div class="d-flex file-item flex-column justify-content-center">
                                    <span>{{ basename($filePath) }}</span>
                                    <a href="{{ asset('storage/' . $filePath) }}" class="zBtn-two" download>Download</a>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <div class="row">
            <div class="col-4">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="course" class="form-label">{{ __('Grade') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="grade" value="{{$assignment->grade}}">
                    </div>
                </div>
            </div>
            <div class="col-4">
                <button type="submit"
                        class="py-10 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one">{{ __('Update') }}</button>
            </div>
        </div>

    </div>
</form>
