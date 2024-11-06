<form class="ajax reset" action="{{route('instructor.courses.announcement.update', $announcement->id)}}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body model-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17">{{ __('Update Announcement') }}</h4>
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
                        <label for="announcementType" class="form-label">{{ __('Announcement Type') }} <span
                                class="text-danger">*</span></label>
                        <select class="form-control" name="announcement_type" id="announcementType" required>
                            <option value="vote" {{ $announcement->announcement_type == 'vote' ? 'selected' : '' }}>
                                Vote
                            </option>
                            <option value="text" {{ $announcement->announcement_type == 'text' ? 'selected' : '' }}>
                                Text
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex flex-column gap-3 mt-17" id="voteSection"
                 style="display: {{ $announcement->announcement_type == 'vote' ? 'block' : 'none' }};">
                @if($announcement->announcement_type=='vote')
                    <div class="primary-form-group mt-2">
                        <div class="primary-form-group-wrap">
                            <label for="voteTitle" class="form-label">{{ __('Vote Title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="primary-form-control" name="vote_title"
                                   value="{{ $announcement->title }}">
                        </div>
                    </div>

                    <label for="choices" class="form-label">{{ __('Choices') }} <span
                            class="text-danger">*</span></label>
                    <div id="choices" class="row">
                        @foreach(json_decode($announcement->choices) as $index => $choice)
                            <div class="choice col-lg-4">
                                <label class="form-label" for="choice{{ $index + 1 }}">Choice {{ $index + 1 }}
                                    :</label>
                                <input class="form-control" type="text" name="choices[]"
                                       id="choice{{ $index + 1 }}"
                                       value="{{ $choice->name }}">
                            </div>
                        @endforeach
                    </div>

                @endif
                @if($announcement->announcement_type=='text')
                    <div class="primary-form-group mt-2 mb-25">
                        <div class="primary-form-group-wrap">
                            <label for="choices" class="form-label">{{ __('Title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="text_title" class="form-control"
                                   value="{{$announcement->title}}">
                        </div>
                    </div>
                    <div class="primary-form-group mt-2 mb-25">
                        <div class="primary-form-group-wrap">
                            <label for="choices" class="form-label">{{ __('Text') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="announcement_text" class="form-control"
                                   value="{{$announcement->text}}">
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit"
                    class="py-10 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one">{{ __('Update') }}</button>
        </div>
</form>
