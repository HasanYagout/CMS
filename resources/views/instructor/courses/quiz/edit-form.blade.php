<form class="ajax reset" action="{{route('instructor.courses.quiz.update',$quiz->id)}}" method="post"
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
                        <input type="text" class="primary-form-control" name="title" value="{{ $quiz->title }}">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="due_date" class="form-label">{{ __('Due Date') }} <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="due_date" name="due_date"
                               value="{{$quiz->due_date}}" required>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="duration" class="form-label">{{ __('Duration') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration"
                               value="{{$quiz->duration}}" required>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="grade" class="form-label">{{ __('Grade') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="grade" name="grade"
                               value="{{$quiz->grade}}" required>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="questions" class="form-label">{{ __('Questions') }} <span
                                class="text-danger">*</span></label>
                        <div id="questionsContainer" class="gap-3 row">
                            @foreach($quiz->questions as $index => $question)
                                <div class="gap-2 gy-3 question-group row">
                                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                                    <h5 class="text-secondary-color">Question {{ $index + 1 }}</h5>
                                    <div class="col-lg-4 d-flex flex-column form-group gap-2">
                                        <label for="question{{ $index + 1 }}Text">Question Text:</label>
                                        <input type="text" class="form-control" value="{{ $question->text }}"
                                               name="questions[{{ $index }}][text]" required>
                                    </div>
                                    <div class="col-lg-4 d-flex flex-column form-group gap-2">
                                        <label>Answer Type:</label>
                                        <select class="form-control question-type"
                                                name="questions[{{ $index }}][type]">
                                            <option value="mcq" {{ $question->type == 'mcq' ? 'selected' : '' }}>
                                                Multiple Choice
                                            </option>
                                            <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>
                                                Essay
                                            </option>
                                        </select>
                                    </div>
                                    <div class="options-container row"
                                         style="{{ $question->type == 'essay' ? 'display:none;' : '' }}">
                                        <div class="col-lg-4 d-flex flex-column form-group gap-2">
                                            <label>Choices (separate by commas):</label>
                                            <input type="text" class="form-control"
                                                   name="questions[{{ $index }}][options]"
                                                   value="{{$question->options}}"
                                                   placeholder="Option 1, Option 2">
                                        </div>
                                        <div class="col-lg-4 d-flex flex-column form-group gap-2">
                                            <label for="correctAnswer">Correct Answer:</label>
                                            <input type="text" class="form-control"
                                                   name="questions[{{ $index }}][correct_answer]"
                                                   value="{{ $question->correct_answer }}"
                                                   placeholder="Enter correct answer">
                                        </div>
                                    </div>
                                    <span class="remove-question text-decoration-underline text-third-color"
                                          onclick="$(this).closest('.question-group').remove()">Remove Question</span>
                                </div>
                            @endforeach
                        </div>
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
