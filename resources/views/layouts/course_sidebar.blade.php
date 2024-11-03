

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
                        <span class="">{{ __('Chapters') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{route('student.courses.announcements.index',['course_id'=>$courseId])}}"
                        class="{{ $activeAnnouncement ?? '' }} d-flex align-items-center cg-10">
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
                        <span class="">{{ __('Announcements') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{route('student.courses.assignments.index',['course_id'=>$courseId])}}"
                        class="{{ $activeAssignment ?? '' }} d-flex align-items-center cg-10">
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
                        <span class="">{{ __('Assignments') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{route('student.courses.quizzes.index',['course_id'=>$courseId])}}"
                        class="{{ $activeQuizzes ?? '' }} d-flex align-items-center cg-10">
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
                        <span class="">{{ __('Quizzes') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{route('student.courses.content.index',['course_id'=>$courseId])}}"
                        class="{{ $activeContent ?? '' }} d-flex align-items-center cg-10">
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
                        <span class="">{{ __('Content') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{route('student.courses.forum.index',['course_id'=>$courseId])}}"
                        class="{{ $activeContent ?? '' }} d-flex align-items-center cg-10">
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
                        <span class="">{{ __('Forum') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>


