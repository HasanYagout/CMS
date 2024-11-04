@extends('layouts.app')
@section('content')
    <div class="p-30">
        <div>
            {{--            <input type="hidden" id="job-post-update-route" value="{{ route('admin.jobs.update.status',':id') }}">--}}
            <input type="hidden" id="availability-route" value="{{ route('admin.availability.index') }}">
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-16">
                {{--                <h4 class="fs-24 fw-500 lh-34 text-black">{{$title}}</h4>--}}
            </div>
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <!-- Table -->
                <div class="table-responsive zTable-responsive">
                    <table class="table zTable" id="availabilityTable">
                        <thead>
                        <tr>

                            <th scope="col"><div>{{ __('Name') }}</div></th>
                            <th scope="col"><div>{{ __('Days') }}</div></th>
                            <th scope="col"><div>{{ __('From') }}</div></th>
                            <th scope="col"><div>{{ __('To') }}</div></th>
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
                <!-- Facebook Modal -->
                <div class="modal fade zModalTwo" id="alumniEmail" tabindex="-1" aria-labelledby="alumniFacebookLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content zModalTwo-content">
                            <div class="modal-body zModalTwo-body">
                                <div class="text-center py-30">
                                    <p class="fs-14 fw-500 lh-18 text-707070 pb-10">{{ __('Contact with') }} <span
                                            class="contact-name"></span>
                                    </p>
                                    <h4 class="fs-32 fw-500 lh-42 text-black show-email"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/admin/js/course.js') }}"></script>
@endpush
