<div class="zSidebar border border-1 shadow-sm zSidebar">
    <div class="zSidebar-overlay"></div>
    <div class="zSidebar-fixed">
        <ul class="zSidebar-menu" id="sidebarMenu">
            <li>
                <a
                    href="{{route('student.enrollment.index',$courseSlug)}}"
                    class="{{ $activeHome ?? '' }} d-flex align-items-center cg-10">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 22 20"
                             fill="none">
                            <path d="M1.71387 11.4286L10.9996 2.14285L20.2853 11.4286" stroke="rgb(95 108 118)"
                                  stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                            <path d="M4.57129 8.57144L4.57129 17.8572H17.4284V8.57144" stroke="rgb(95 108 118)"
                                  stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="">{{ __('Home') }}</span>
                </a>
            </li>
            <li>
                <a
                    href="{{route('student.courses.chapters.view',$courseSlug)}}"
                    class="{{ $activeChapters ?? '' }} d-flex align-items-center cg-10">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" class="bi bi-grid"
                             viewBox="0 0 16 16">
                            <path stroke="rgb(95 108 118)"
                                  d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                        </svg>
                    </div>
                    <span class="">{{ __('Chapters') }}</span>
                </a>
            </li>
            <li>
                <a
                    href="{{route('student.courses.announcements.index',['course_id'=>$courseId])}}"
                    class="{{ $activeAnnouncement ?? '' }} d-flex align-items-center cg-10">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" class="bi bi-bell"
                             viewBox="0 0 16 16">
                            <path stroke="rgb(95 108 118)"
                                  d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                        </svg>
                    </div>
                    <span class="">{{ __('Announcements') }}</span>
                </a>
            </li>
            <li>
                <a
                    href="{{route('student.courses.assignments.index',['course_id'=>$courseId])}}"
                    class="{{ $activeAssignment ?? '' }} d-flex align-items-center cg-10">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             class="bi bi-file-text-fill" viewBox="0 0 16 16">
                            <path stroke="rgb(95 108 118)"
                                  d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1m-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5M5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1m0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1"/>
                        </svg>
                    </div>
                    <span class="">{{ __('Assignments') }}</span>
                </a>
            </li>
            <li>
                <a
                    href="{{route('student.courses.quizzes.index',['course_id'=>$courseId])}}"
                    class="{{ $activeQuizzes ?? '' }} d-flex align-items-center cg-10">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             class="bi bi-layout-text-window" viewBox="0 0 16 16">
                            <path stroke="rgb(95 108 118)"
                                  d="M3 6.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/>
                            <path stroke="rgb(95 108 118)"
                                  d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v1H1V2a1 1 0 0 1 1-1zm1 3v10a1 1 0 0 1-1 1h-2V4zm-4 0v11H2a1 1 0 0 1-1-1V4z"/>
                        </svg>
                    </div>
                    <span class="">{{ __('Quizzes') }}</span>
                </a>
            </li>
            <li>
                <a
                    href="{{route('student.courses.content.index',['course_id'=>$courseId])}}"
                    class="{{ $activeContent ?? '' }} d-flex align-items-center cg-10">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             class="bi bi-journal-arrow-up" viewBox="0 0 16 16">
                            <path stroke="rgb(95 108 118)" fill-rule="evenodd"
                                  d="M8 11a.5.5 0 0 0 .5-.5V6.707l1.146 1.147a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 .5.5"/>
                            <path stroke="rgb(95 108 118)"
                                  d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path stroke="rgb(95 108 118)"
                                  d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                    </div>
                    <span class="">{{ __('Content') }}</span>
                </a>
            </li>
            <li>
                <a
                    href="{{route('student.courses.forum.index',['course_id'=>$courseId])}}"
                    class="{{ $activeForumSide ?? '' }} d-flex align-items-center cg-10">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             class="bi bi-chat-left-dots" viewBox="0 0 16 16">
                            <path stroke="rgb(95 108 118)"
                                  d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            <path stroke="rgb(95 108 118)"
                                  d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                        </svg>
                    </div>
                    <span class="">{{ __('Forum') }}</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center  cg-8" href="{{ route('logout') }}" id="logout-link">
                    <div class="d-flex">
                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.49935 17.8333C7.28921 17.8333 5.1696 16.9553 3.60679 15.3925C2.04399 13.8297 1.16602 11.7101 1.16602 9.49996C1.16602 7.28982 2.04399 5.17021 3.60679 3.6074C5.1696 2.0446 7.28921 1.16663 9.49935 1.16663"
                                stroke="#707070" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M7.41602 9.5H17.8327M17.8327 9.5L14.7077 6.375M17.8327 9.5L14.7077 12.625"
                                  stroke="#707070" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span>{{ __('Logout') }}</span>
                </a>
                <!-- Hidden Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>


