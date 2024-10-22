@extends('layouts.app')
@section('content')
    <div class="p-30">
        <div>
{{--            <input type="hidden" id="job-post-update-route" value="{{ route('admin.jobs.update.status',':id') }}">--}}
            <input type="hidden" id="course-route" value="{{ route('admin.courses.index') }}">
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-16">
{{--                <h4 class="fs-24 fw-500 lh-34 text-black">{{$title}}</h4>--}}
            </div>
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <!-- Table -->
                <div class="table-responsive zTable-responsive">
                    <table class="table zTable" id="coursesTable">
                        <thead>
                        <tr>

                            <th scope="col"><div>{{ __('Name') }}</div></th>
                            <th scope="col"><div>{{ __('Instructor') }}</div></th>
                            <th scope="col"><div>{{ __('Days') }}</div></th>
                            <th scope="col"><div>{{ __('Status') }}</div></th>
                            <th class="w-110 text-center" scope="col"><div>{{ __('Action') }}</div></th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('public/admin/js/courses.js') }}"></script>
@endpush
