@extends('layouts.app_course')
@section('content')
    <div class="row">


        <x-breadcrumbs :items="[
    ['name' => 'Home', 'url' => route('student.courses.index')],
    ['name' => 'Courses', 'url' => route('student.courses.index')],
    ['name' => $activeLecture->title, 'url' => '']
        ]"/>


        <div class="bg-white col-lg-3 mt-30 rounded rounded-5">
            <ul class="zSidebar-menu my-5" id="sidebarMenu">

                @foreach($lectures as $lecture)
                    <li>
                        <a class="d-flex flex-column fs-12 fw-bold text-primary-color {{ request()->is('student/courses/lectures/view/' . $course->id . '/' . $lecture->id) ? 'active bg-custom-primary-light text-primary-color' : '' }}"
                           href="{{ route('student.courses.lectures.view', ['course_id' => $lecture->chapters->course->id, 'lecture_id' => $lecture->id]) }}">
                            <span class="fw-bold">{{ $lecture->title }}</span>
                            <span>{{ \Carbon\Carbon::parse($lecture->start_date)->format('j M, Y') }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-lg-9">
            <x-wrapper title="">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="assignments-tab" data-bs-toggle="tab" href="#class" role="tab"
                           aria-controls="assignments" aria-selected="true">Class</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="quizzes-tab" data-bs-toggle="tab" href="#content" role="tab"
                           aria-controls="quizzes" aria-selected="false">Content</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#assignments" role="tab"
                           aria-controls="contact" aria-selected="false">Assignments</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#quizzes" role="tab"
                           aria-controls="contact" aria-selected="false">Quizzes</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#activities" role="tab"
                           aria-controls="contact" aria-selected="false">Activities</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#grades" role="tab"
                           aria-controls="contact" aria-selected="false">Grades</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="class" role="tabpanel" aria-labelledby="assignments-tab">
                        <div class="row">
                            <div class="col-lg-12">

                                <h3 class="text-center text-secondary-color p-13">{{ $activeLecture->title }}</h3>
                                <hr class="m-0">
                                <p class="p-14 text-black text-break">{{ $activeLecture->description }}</p>
                                <hr class="m-0">
                                <section class="d-flex mt-20 fs-18 fw-bold justify-content-around text-black">
                                    <span>Starts at: {{ \Carbon\Carbon::parse($activeLecture->start_date)->format('j M, Y') }}</span>
                                    <span>Ends at: {{ \Carbon\Carbon::parse($activeLecture->end_date)->format('j M, Y') }}</span>
                                </section>
                                @if($activeLecture->zoom_link)
                                    <section>
                                        <a style="width: 200px" class="zBtn-two text-center mt-30 m-auto d-block"
                                           href="{{ $lecture->zoom_link }}">Join Class</a>
                                    </section>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content" role="tabpanel" aria-labelledby="contact-tab">
                        @if($activeLecture->materials)
                            <div class="row">
                                @foreach($activeLecture->materials as $material)
                                    <div class="col-lg-4">
                                        @if($material->type == 'image')
                                            <img src="{{ asset('storage/' . $material->file_path) }}" alt=""
                                                 style="max-width: 100%;">
                                        @elseif($material->type == 'video')
                                            <video controls style="max-width: 100%;">
                                                <source src="{{ asset('storage/' . $material->file_path) }}"
                                                        type="video/{{ pathinfo($material->file_path, PATHINFO_EXTENSION) }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="quizzes-tab">
                        <h3 class="text-black mt-3 mb-3 text-center">{{ $activeLecture->title }}</h3>
                        <hr>
                        @if(!empty($activeLecture->assignments) && $activeLecture->assignments->count() > 0)
                            @foreach($activeLecture->assignments as $assignment)
                                <div class="row mb-5 shadow rounded p-20">
                                    <div class="col-lg-6 mb-4 d-flex flex-column gap-3">
                                        <h4 class="text-secondary-color">{{ $assignment->title }}</h4>
                                        <hr>
                                        <h5 class="text-black">Delivery Time: {{ $assignment->due_date }}</h5>
                                        <h5 class="text-black">Instructions:</h5>
                                        {!!  $assignment->description  !!}
                                        <h5 class="text-third-color">Marks: {{ $assignment->grade }}</h5>
                                    </div>
                                    <div class="col-lg-6">
                                        <h4 class="text-secondary-color">Submit Your Assignment</h4>

                                        <form method="post"
                                              action="{{ route('student.courses.assignments.store', $assignment->id) }}"
                                              enctype="multipart/form-data"
                                              @if($assignment->submittedAssignments->isNotEmpty()) disabled @endif>
                                            @csrf
                                            <section class="d-flex flex-column gap-2 mt-5">
                                                <input type="text" name="comment" placeholder="Add your comment"
                                                       class="form-control mb-2"
                                                       @if($assignment->submittedAssignments->isNotEmpty()) disabled @endif>
                                                <input type="file" name="assignments[]" multiple
                                                       class="form-control mb-2"
                                                       @if($assignment->submittedAssignments->isNotEmpty()) disabled @endif>
                                            </section>
                                            <section class="mt-3">
                                                <button class="btn zBtn-three"
                                                        @if($assignment->submittedAssignments->isNotEmpty()) disabled @endif>
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn zBtn-two"
                                                        @if($assignment->submittedAssignments->isNotEmpty()) disabled @endif>
                                                    Save
                                                </button>
                                            </section>
                                            <section>
                                                @if($assignment->submittedAssignments->isNotEmpty())
                                                    @if(is_null($assignment->submittedAssignments->first()->grade))
                                                        <h5>Your Grade: <span class="text-danger"> still waiting </span>
                                                        </h5>
                                                    @else
                                                        <h5 class="text-primary-color">Your
                                                            Grade: {{ $assignment->submittedAssignments->first()->grade }}</h5>
                                                    @endif
                                                @endif
                                            </section>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <p>No assignments available for this chapter.</p>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="quizzes" role="tabpanel" aria-labelledby="contact-tab">
                        <h3 class="text-black mt-3 mb-3 text-center">{{$activeLecture->title}}</h3>
                        <hr>
                        @if(!is_null($quiz))

                            <h3 class="text-black">{{$quiz->title}}</h3>

                            <h6 class="text-black mt-5">instructions:</h6>
                            <ol class="mt-3 fs-16">
                                <li>Test time limit is {{$quiz->duration}} minutes</li>
                                <li>Once started, this short test must be completed in one sitting. Do not leave the
                                    “test” before clicking Save and Submit
                                </li>
                                <li>You will be notified when time expires and you can continue or submit</li>
                            </ol>
                            @if($alreadySubmitted)
                                <span
                                    class="fs-18 fw-1000 text-secondary-color">Your Grade is:{{$grade.'/'.$totalQuizGrade}}</span>
                            @else
                                <a href="{{ route('student.courses.quizzes.show', $quiz->id) }}"
                                   class="bg-secondary-color btn d-block m-auto text-white w-25">Start Exam</a>
                            @endif

                        @endif
                    </div>
                    <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="contact-tab">
                        <h3 class="text-black mt-3 mb-3 text-center">{{$activeLecture->title}}</h3>
                        <hr>
                        <h3 class="text-black">Activities</h3>
                        <hr>
                        <form id="activityForm">
                            @csrf
                            @foreach($activeLecture->activities as $activity)
                                <section class="d-flex justify-content-around bg-light-blue mb-20 p-20">
                                    <h3 class="align-content-center text-black">{{ $activity->title }}</h3>
                                    <ul>

                                        @foreach(json_decode($activity->options) as $option)
                                            <li>
                                                <input type="radio" name="activity_{{ $activity->id }}"
                                                       value="{{ $option }}"
                                                       @if($activity->studentActivity->isNotEmpty()) disabled @endif>
                                                {{ $option }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <section>
                                        <span class="text-black">{{ $activity->grade }} Grade</span>
                                        <div id="answer_{{ $activity->id }}" class="answer mt-3">
                                            @if($activity->studentActivity->isNotEmpty())
                                                @if($activity->studentActivity->first()->correct)
                                                    <span
                                                        class="p-2 rounded-4 text-white bg-secondary-color">Correct</span>
                                                @else
                                                    <span class="p-2 rounded-4 text-white bg-danger">Incorrect</span>
                                                @endif
                                            @else

                                            @endif
                                        </div>
                                    </section>
                                </section>
                            @endforeach

                            <button type="button" id="submitAnswers" class="btn btn-primary"
                                    @if($activeLecture->activities->isNotEmpty())
                                        @if($activeLecture->activities->first()->studentActivity->isNotEmpty()) disabled @endif>
                                Submit Answers
                                @endif

                            </button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="grades" role="tabpanel" aria-labelledby="contact-tab">
                        <h3 class="text-black mt-3 mb-3 text-center">{{$activeLecture->title}}</h3>
                        <hr>
                        <section>
                            <h3>Grades</h3>
                            <hr>
                            <div class="row gap-3">
                                <x-drop title="assignments" total="{{$totalAssignments}}"
                                        submitted="{{$submittedAssignments}}">

                                </x-drop>
                                <x-drop title="quizzes" total="{{$totalQuizzes}}" submitted="{{$submittedQuizzes}}">

                                </x-drop>
                                <x-drop title="activites" total="{{$totalActivities}}"
                                        submitted="{{$submittedActivities}}">

                                </x-drop>
                                <x-drop title="attendance" total="{{$totalLectures}}"
                                        submitted="{{$attendedLectures}}">

                                </x-drop>
                            </div>
                            <section>
                                Total Grades:{{$total}}
                            </section>

                        </section>
                    </div>

                </div>
            </x-wrapper>

        </div>
    </div>

@endsection
@push('script')
    <script>
        $(document).ready(function () {
            $('#submitAnswers').click(function () {
                let results = [];
                    @foreach($activeLecture->activities as $activity)
                    @if($activity->studentActivity->isEmpty())
                {
                    let selectedOption = $(`input[name="activity_{{ $activity->id }}"]:checked`).val();
                    let correctAnswer = "{{ $activity->correct_answer }}";
                    let isCorrect = false;

                    if (selectedOption) {
                        if (selectedOption.trim() === correctAnswer.trim()) {
                            $(`#answer_{{ $activity->id }}`)
                                .removeClass('bg-danger').addClass('answer p-2 rounded-4 text-white bg-secondary-color')
                                .text('Correct');
                            isCorrect = true;
                        } else {
                            $(`#answer_{{ $activity->id }}`)
                                .removeClass('bg-secondary-color').addClass('answer p-2 rounded-4 text-white bg-danger')
                                .text('Incorrect');
                        }
                    } else {
                        $(`#answer_{{ $activity->id }}`).css('background-color', 'yellow').text('Not answered');
                    }

                    results.push({
                        activity_id: "{{ $activity->id }}",
                        selected_option: selectedOption,
                        correct: isCorrect
                    });
                }
                @endif
                @endforeach

                $.ajax({
                    url: '{{ route("student.courses.lectures.activities.store") }}',
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        results: results
                    },
                    success: function (response) {
                        if (response.success) {
                            window.location.href = `{{ route('student.courses.lectures.view', ['course_id' => $course->id, 'lecture_id' => $activeLecture->id]) }}`;
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error submitting results:', error);
                    }
                });
            });
        });
    </script>
@endpush
