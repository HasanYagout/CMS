@extends('layouts.app')
@section('content')
    <x-wrapper title="Chapters">
        <div class="container mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="assignments-tab" data-bs-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="true">Assignments</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="quizzes-tab" data-bs-toggle="tab" href="#quizzes" role="tab" aria-controls="quizzes" aria-selected="false">Quizzes</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            @if(!empty($assignments) && $assignments->count() > 0)
                                @foreach($assignments as $assignment)
                                    <div class="mb-4">
                                        <h4>{{ $assignment->title }}</h4>
                                        <h5>Delivery Time: {{ $assignment->due_date }}</h5>
                                        <p>Instructions:</p>
                                        <textarea class="summernoteOne" readonly>{{ $assignment->description }}</textarea>
                                        <h5>Marks: {{ $assignment->marks }}</h5>
                                    </div>
                                @endforeach

                                <h3>Submit Your Assignment</h3>
                                <form method="post" action="{{ route('student.courses.assignments.store', $assignment->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" name="comment" placeholder="Add your comment" class="form-control mb-2">
                                    <input type="file" name="assignments[]" multiple class="form-control mb-2">
                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <p>No assignments available for this chapter.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="quizzes" role="tabpanel" aria-labelledby="quizzes-tab">
                @foreach($quizzes as $quiz)
                    <h1>{{$quiz->title}}</h1>
                    <h6>instructions:</h6>
                    <ol>
                        <li>Test time limit is {{$quiz->duration}} minutes</li>
                        <li>Once started, this short test must be completed in one sitting. Do not leave the “test” before clicking Save and Submit</li>
                        <li>You will be notified when time expires and you can continue or submit</li>
                    </ol>
                        <a href="{{ route('student.courses.quiz.show', $quiz->id) }}" class="btn btn-primary">Start Exam</a>
                @endforeach
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <h5>Contact Tab</h5>
                    <p>This is the content for the Contact tab.</p>
                </div>
            </div>
        </div>
    </x-wrapper>
@endsection
