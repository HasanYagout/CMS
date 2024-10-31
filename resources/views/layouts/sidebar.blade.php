@php
    use Illuminate\Support\Facades\Auth;

    $authenticatedUser = Auth::user();
    $userInfo = null;

    if ($authenticatedUser) {
        switch ($authenticatedUser->role_id) {
            case 1:
                $userInfo = $authenticatedUser->super_admin;
                $role = 'super_admin';
                break;
            case 2:
                $userInfo = $authenticatedUser->admin;
                $role = 'admin';
                break;
            case 3:
                $userInfo = $authenticatedUser->instructor;
                $role = 'instructor';
                break;
                case 4:
                $userInfo = $authenticatedUser->student;
                $role = 'student';
                break;
        }
    }
@endphp

@if($authenticatedUser)

    <div class="zSidebar border border-1 shadow-sm zSidebar">
        <div class="zSidebar-overlay"></div>
        <!-- Logo -->
{{--        <a href="{{ route('index') }}" class="d-block mx-26">--}}
{{--            <img class="max-h-69 d-block m-auto" src="{{ asset('public/frontend/images/liu-logo.png') }}"--}}
{{--                 alt="LIU Logo"/>--}}
{{--        </a>--}}
        <!-- Menu & Logout -->
        <div class="zSidebar-fixed">
            <ul class="zSidebar-menu" id="sidebarMenu">

                    <li>
                        <a href="{{ route($role.'.dashboard') }}"
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


                @if($role == 'admin'||$role=='student'||$role=='instructor')
                    @if($role=='admin')
                        <li>
                            <a href="{{ route($role.'.semesters.index') }}"
                               class="{{ $activeSemester ?? '' }} d-flex align-items-center cg-10">
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
                                <span class="">{{ __('Semester') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route($role.'.college.index') }}"
                               class="{{ $activeSemester ?? '' }} d-flex align-items-center cg-10">
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
                                <span class="">{{ __('College') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route($role.'.news.index') }}"
                               class="{{ $activeSemester ?? '' }} d-flex align-items-center cg-10">
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
                                <span class="">{{ __('News') }}</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="#coursesMenu" data-bs-toggle="collapse" role="button"
                           aria-expanded="{{ isset($showCourseManagement) ? 'true' : 'collapsed' }}"
                           aria-controls="storyMenu"
                           class="d-flex align-items-center cg-10 {{ isset($showCourseManagement) ? 'active' : 'collapsed' }}">
                            <div class="d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26"
                                     fill="none">
                                    <path
                                        d="M20.0934 4.00108L20.1195 4.75063V4.75063L20.0934 4.00108ZM16.7072 4.4451L16.4921 3.72661V3.72661L16.7072 4.4451ZM14.0356 5.6885L13.6599 5.03937L13.6599 5.03937L14.0356 5.6885ZM4.87402 4.0551L4.82801 4.80369L4.87402 4.0551ZM7.72663 4.4451L7.91799 3.71992L7.91799 3.71992L7.72663 4.4451ZM10.8294 5.75723L10.4788 6.42024L10.8294 5.75723ZM13.9919 20.1203L14.3447 20.7822L13.9919 20.1203ZM17.1799 18.7629L16.9885 18.0378L17.1799 18.7629ZM20.0018 18.3748L20.0487 19.1234L20.0018 18.3748ZM10.9147 20.1203L10.5619 20.7822H10.5619L10.9147 20.1203ZM7.72663 18.7629L7.91799 18.0378H7.91799L7.72663 18.7629ZM4.9047 18.3748L4.85788 19.1234H4.85788L4.9047 18.3748ZM3.75 16.4093V5.87288H2.25V16.4093H3.75ZM22.6565 16.4093V5.81181H21.1565V16.4093H22.6565ZM20.0673 3.25154C18.9906 3.28904 17.5796 3.40106 16.4921 3.72661L16.9223 5.16359C17.8143 4.89658 19.0624 4.78745 20.1195 4.75063L20.0673 3.25154ZM16.4921 3.72661C15.5467 4.00964 14.4853 4.56168 13.6599 5.03937L14.4113 6.33762C15.2146 5.87269 16.1517 5.39429 16.9223 5.16359L16.4921 3.72661ZM4.82801 4.80369C5.74061 4.85978 6.77153 4.96874 7.53527 5.17028L7.91799 3.71992C7.00565 3.47917 5.8534 3.36388 4.92003 3.30651L4.82801 4.80369ZM7.53527 5.17028C8.44003 5.40902 9.55596 5.93224 10.4788 6.42024L11.18 5.09421C10.2385 4.59634 8.99693 4.00463 7.91799 3.71992L7.53527 5.17028ZM14.3447 20.7822C15.281 20.283 16.4378 19.7344 17.3713 19.4881L16.9885 18.0378C15.8773 18.331 14.5924 18.9503 13.6391 19.4585L14.3447 20.7822ZM17.3713 19.4881C18.1263 19.2889 19.1429 19.18 20.0487 19.1234L19.955 17.6263C19.0279 17.6843 17.891 17.7996 16.9885 18.0378L17.3713 19.4881ZM11.2675 19.4585C10.3142 18.9503 9.02921 18.331 7.91799 18.0378L7.53527 19.4881C8.46871 19.7344 9.6255 20.283 10.5619 20.7822L11.2675 19.4585ZM7.91799 18.0378C7.01549 17.7996 5.8786 17.6843 4.95152 17.6263L4.85788 19.1234C5.76364 19.18 6.78023 19.2889 7.53527 19.4881L7.91799 18.0378ZM21.1565 16.4093C21.1565 17.0343 20.6378 17.5836 19.955 17.6263L20.0487 19.1234C21.4623 19.035 22.6565 17.8848 22.6565 16.4093H21.1565ZM22.6565 5.81181C22.6565 4.40713 21.5375 3.20032 20.0673 3.25154L20.1195 4.75063C20.6757 4.73125 21.1565 5.18885 21.1565 5.81181H22.6565ZM2.25 16.4093C2.25 17.8848 3.44425 19.035 4.85788 19.1234L4.95152 17.6263C4.26877 17.5836 3.75 17.0343 3.75 16.4093H2.25ZM13.6391 19.4585C12.9022 19.8513 12.0044 19.8513 11.2675 19.4585L10.5619 20.7822C11.7398 21.4101 13.1667 21.4101 14.3447 20.7822L13.6391 19.4585ZM13.6599 5.03937C12.8986 5.47998 11.9509 5.50181 11.18 5.09421L10.4788 6.42024C11.7131 7.07288 13.2095 7.03314 14.4113 6.33762L13.6599 5.03937ZM3.75 5.87288C3.75 5.23574 4.25397 4.76841 4.82801 4.80369L4.92003 3.30651C3.42171 3.21443 2.25 4.43377 2.25 5.87288H3.75Z"
                                        fill="white" fill-opacity="0.7"/>
                                    <path d="M12.4531 6.68213V21" stroke="rgb(95 108 118)" stroke-opacity="0.7"
                                          stroke-width="1.5"/>
                                    <path d="M5.83594 9.65613L9.61725 10.6015" stroke="rgb(95 108 118)" stroke-opacity="0.7"
                                          stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M5.83594 13.4375L9.61725 14.3828" stroke="rgb(95 108 118)" stroke-opacity="0.7"
                                          stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M19.0703 13.4375L15.289 14.3828" stroke="rgb(95 108 118)" stroke-opacity="0.7"
                                          stroke-width="1.5" stroke-linecap="round"/>
                                    <path
                                        d="M19.0704 6.34753V10.1386C19.0704 10.3991 19.0704 10.5293 18.9807 10.582C18.891 10.6346 18.7684 10.5764 18.5231 10.4599L17.3488 9.90207C17.2658 9.86265 17.2243 9.84294 17.1797 9.84294C17.1351 9.84294 17.0936 9.86265 17.0106 9.90207L15.8363 10.4599C15.591 10.5764 15.4684 10.6346 15.3787 10.582C15.2891 10.5293 15.2891 10.3991 15.2891 10.1386V7.71828"
                                        stroke="rgb(95 108 118)" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Manage Courses')}}</span>
                        </a>
                        <div class="collapse {{ $showCourseManagement ?? '' }}" id="coursesMenu"
                             data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                @if($role=='instructor'||$role=='student')
                                    <li>
                                        <a class="{{ $activeCourseALL ?? '' }}"
                                           href="{{ route($role.'.courses.index') }}">{{ __('Courses') }}</a>
                                    </li>
                                @endif

                                @if($role=='admin')
                                    <li>
                                        <a class="{{ $activeCourseCreate ?? '' }}"
                                           href="{{ route('admin.courses.create') }}">{{ __('Create Course') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ $activeCourseMaterial ?? '' }}"
                                           href="{{ route('admin.courses.instructors.index') }}">{{ __('Instructor') }}</a>
                                    </li>

                                @endif
                                @if($role=='instructor')

                                    <li>
                                        <a class="{{ $activeCourseChapter ?? '' }}"
                                           href="{{ route('instructor.courses.chapters.index') }}">{{ __('Course Chapter') }}</a>
                                    </li>
                                        <li>
                                            <a class="{{ $activeCourseChapter ?? '' }}"
                                               href="{{ route('instructor.courses.lectures.index') }}">{{ __('Chapter Lecture') }}</a>
                                        </li>
                                    <li>
                                        <a class="{{ $activeCourseMaterial ?? '' }}"
                                           href="{{ route('instructor.courses.materials.index') }}">{{ __('Material') }}</a>
                                    </li>

                                    <li>
                                        <a class="{{ $activeCourseMaterial ?? '' }}"
                                           href="{{ route('instructor.courses.assignments.index') }}">{{ __('Assignment') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ $activeCourseMaterial ?? '' }}"
                                           href="{{ route('instructor.courses.quiz.index') }}">{{ __('Quiz') }}</a>
                                    </li>
                                        <li>
                                            <a class="{{ $activeCourseMaterial ?? '' }}"
                                               href="{{ route('instructor.courses.announcement.index') }}">{{ __('Announcement') }}</a>
                                        </li>
                                        <li>
                                            <a class="{{ $activeCourseMaterial ?? '' }}"
                                               href="{{ route('instructor.courses.lectures.activities.index') }}">{{ __('Activities') }}</a>
                                        </li>

                                @endif


                            </ul>
                        </div>
                    </li>

                @if($role=='student')
                        <li>
                            <a href="{{ route($role.'.enrollment.courses') }}"
                               class="{{ $activeEnrolled ?? '' }} d-flex align-items-center cg-10">
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
                                <span class="">{{ __('Enrolled Courses') }}</span>
                            </a>
                        </li>
                @endif

                @endif

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
@endif
