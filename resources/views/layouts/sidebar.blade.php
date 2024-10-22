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
                                    <path d="M1.71387 11.4286L10.9996 2.14285L20.2853 11.4286" stroke="white"
                                          stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                    <path d="M4.57129 8.57144L4.57129 17.8572H17.4284V8.57144" stroke="white"
                                          stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Home') }}</span>
                        </a>
                    </li>


                @if($role == 'admin'||$role=='student')

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
                                    <path d="M12.4531 6.68213V21" stroke="white" stroke-opacity="0.7"
                                          stroke-width="1.5"/>
                                    <path d="M5.83594 9.65613L9.61725 10.6015" stroke="white" stroke-opacity="0.7"
                                          stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M5.83594 13.4375L9.61725 14.3828" stroke="white" stroke-opacity="0.7"
                                          stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M19.0703 13.4375L15.289 14.3828" stroke="white" stroke-opacity="0.7"
                                          stroke-width="1.5" stroke-linecap="round"/>
                                    <path
                                        d="M19.0704 6.34753V10.1386C19.0704 10.3991 19.0704 10.5293 18.9807 10.582C18.891 10.6346 18.7684 10.5764 18.5231 10.4599L17.3488 9.90207C17.2658 9.86265 17.2243 9.84294 17.1797 9.84294C17.1351 9.84294 17.0936 9.86265 17.0106 9.90207L15.8363 10.4599C15.591 10.5764 15.4684 10.6346 15.3787 10.582C15.2891 10.5293 15.2891 10.3991 15.2891 10.1386V7.71828"
                                        stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="">{{__('Manage Courses')}}</span>
                        </a>
                        <div class="collapse {{ $showCourseManagement ?? '' }}" id="coursesMenu"
                             data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">

                                <li>
                                    <a class="{{ $activeCourseALL ?? '' }}"
                                       href="{{ route($role.'.courses.index') }}">{{ __('Courses') }}</a>
                                </li>
                                @if($role=='admin')
                                    <li>
                                        <a class="{{ $activeCourseCreate ?? '' }}"
                                           href="{{ route('admin.courses.create') }}">{{ __('Create Course') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ $activeCourseCreate ?? '' }}"
                                           href="{{ route('admin.courses.chapters.index') }}">{{ __('Course Chapter') }}</a>
                                    </li>
                                @endif


                            </ul>
                        </div>
                    </li>
                    <li>
                            <a href="#companiesMenu" data-bs-toggle="collapse" role="button"
                               aria-expanded="{{ isset($showCompanyManagement) ? 'true' : 'collapsed' }}"
                               aria-controls="companiesMenu"
                               class="d-flex align-items-center cg-10 {{ isset($showCompanyManagement) ? 'active' : 'collapsed' }}">
                                <div class="d-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-building text-white-70" viewBox="0 0 16 16">
                                        <path
                                            d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                                        <path
                                            d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
                                    </svg>
                                </div>
                                <span class="">{{__('Manage Availabilities')}}</span>
                            </a>
                            <div class="collapse {{ $showCompanyManagement ?? '' }}" id="companiesMenu"
                                 data-bs-parent="#sidebarMenu">
                                <ul class="zSidebar-submenu">
                                    <li>
                                        <a class="{{ $activeAllCompanyList ?? '' }}"
                                           href="{{ route('admin.availability.index') }}">{{ __('All') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ $activeAllCompanyList ?? '' }}"
                                           href="{{ route('admin.availability.create') }}">{{ __('Create') }}</a>
                                    </li>




                                </ul>
                            </div>
                        </li>
                    <li>
                            <a href="#manage-news-menu" data-bs-toggle="collapse" role="button"
                               aria-expanded="{{ isset($showManageNews) ? 'true' : 'false' }}"
                               aria-controls="manage-news-menu"
                               class="d-flex align-items-center cg-10 {{ isset($showManageNews) ? 'active' : 'collapsed' }}">
                                <div class="d-flex">
                                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1 1V15C1 15.5304 1.21071 16.0391 1.58579 16.4142C1.96086 16.7893 2.46957 17 3 17H17C17.5304 17 18.0391 16.7893 18.4142 16.4142C18.7893 16.0391 19 15.5304 19 15V5H15"
                                            stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                        <path
                                            d="M1 1H15V15C15 15.5304 15.2107 16.0391 15.5858 16.4142C15.9609 16.7893 16.4696 17 17 17M11 5H5M11 9H7"
                                            stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="">{{ __('Manage News') }}</span>
                            </a>
                            <div class="collapse {{ $showManageNews ?? '' }}" id="manage-news-menu"
                                 data-bs-parent="#sidebarMenu">

                            </div>
                        </li>
                    <li>
                            <a href="#manage-notice-menu" data-bs-toggle="collapse" role="button"
                               aria-expanded="{{ isset($showManageNotice) ? 'true' : 'false' }}"
                               aria-controls="manage-notice-menu"
                               class="d-flex align-items-center cg-10 {{ isset($showManageNotice) ? 'active' : 'collapsed' }}">
                                <div class="d-flex">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="9.25" stroke="white" stroke-opacity="0.7"
                                                stroke-width="1.5"/>
                                        <path
                                            d="M9.18262 11.7393L9.08008 4.81445H10.7207L10.6113 11.7393H9.18262ZM9.90039 15.0957C9.61328 15.0957 9.37174 15.0023 9.17578 14.8154C8.98438 14.624 8.88867 14.3893 8.88867 14.1113C8.88867 13.8288 8.98438 13.5941 9.17578 13.4072C9.37174 13.2158 9.61328 13.1201 9.90039 13.1201C10.1829 13.1201 10.4199 13.2158 10.6113 13.4072C10.8073 13.5941 10.9053 13.8288 10.9053 14.1113C10.9053 14.3893 10.8073 14.624 10.6113 14.8154C10.4199 15.0023 10.1829 15.0957 9.90039 15.0957Z"
                                            fill="white" fill-opacity="0.7"/>
                                    </svg>
                                </div>
                                <span class="">{{ __('Manage Notice') }}</span>
                            </a>
                            <div class="collapse {{ $showManageNotice ?? '' }}" id="manage-notice-menu"
                                 data-bs-parent="#sidebarMenu">

                            </div>
                        </li>
                    <li>

                    </li>

                @endif

                @if ($role=='admin'||$role=='alumni')
                                    <li>
                                        <a href="#myEvent" data-bs-toggle="collapse" role="button" aria-controls="myEvent" class="d-flex align-items-center cg-10 {{ isset($showEvent) ? 'active' : 'collapsed' }}" aria-expanded="{{ isset($showEvent) ? 'true' : 'false' }}">
                                            <div class="d-flex">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="3" y="6" width="18" height="15" rx="2" stroke="white" stroke-width="1.5"/>
                                                    <path d="M4 11H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M9 16H15" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M8 3L8 7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M16 3L16 7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <span class="">{{ __('Manage Events') }}</span>
                                        </a>
                                        <div class="collapse {{ $showEvent ?? '' }}" id="myEvent" data-bs-parent="#sidebarMenu">
{{--                                            <ul class="zSidebar-submenu">--}}
{{--                                                @if ($role=='admin')--}}
{{--                                                    <li><a class="{{ $activeEventCategory ?? '' }}" href="{{ route($role.'.eventCategory.index') }}">{{ __('Event Category') }}</a></li>--}}
{{--                                                    <li><a class="{{ $activeEventCreate ?? '' }}" href="{{ route($role.'.event.create') }}">{{ __('Create Event') }}</a></li>--}}
{{--                                                    <li><a class="{{ $activeMyEvent ?? '' }}" href="{{ route($role.'.event.my-event') }}">{{ __('My Event') }}</a></li>--}}
{{--                                                @endif--}}
{{--                                                <li><a class="{{ $activeEventPending ?? '' }}" href="{{ route($role.'.event.pending') }}">{{ __('Pending Events') }}</a></li>--}}
{{--                                                <li><a class="{{ $activeAllEvent ?? '' }}" href="{{ route($role.'.event.all') }}">{{ __('All Events') }}</a></li>--}}
{{--                                            </ul>--}}
                                        </div>
                                    </li>
                                @endif



{{--                --}}
{{--                                @if($authenticatedUser=='admin'&&auth('admin')->user()->role_id==USER_ROLE_INSTRUCTOR)--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('admin.instructor.dashboard') }}" class="{{ $activeDashboard ?? '' }} d-flex align-items-center cg-10">--}}
{{--                                            <div class="d-flex">--}}
{{--                                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                    <path d="M6.88979 10.3929C6.14657 10.3929 5.62851 10.3924 5.22349 10.3635C4.82565 10.3351 4.59466 10.2819 4.4186 10.2051C3.89833 9.97813 3.48308 9.56288 3.25609 9.04261C3.17928 8.86655 3.12616 8.63556 3.09774 8.23773C3.0688 7.83271 3.06836 7.31465 3.06836 6.57143C3.06836 5.82821 3.0688 5.31015 3.09774 4.90513C3.12616 4.50729 3.17928 4.2763 3.25609 4.10024C3.48308 3.57997 3.89833 3.16473 4.4186 2.93773C4.59466 2.86092 4.82565 2.80781 5.22349 2.77938C5.6285 2.75045 6.14657 2.75 6.88979 2.75C7.63301 2.75 8.15107 2.75045 8.55609 2.77938C8.95392 2.80781 9.18491 2.86092 9.36097 2.93773C9.88124 3.16473 10.2965 3.57997 10.5235 4.10024C10.6003 4.2763 10.6534 4.50729 10.6818 4.90513C10.7108 5.31015 10.7112 5.82821 10.7112 6.57143C10.7112 7.31465 10.7108 7.83271 10.6818 8.23773C10.6534 8.63556 10.6003 8.86655 10.5235 9.04261C10.2965 9.56288 9.88124 9.97813 9.36097 10.2051C9.18491 10.2819 8.95392 10.3351 8.55609 10.3635C8.15107 10.3924 7.63301 10.3929 6.88979 10.3929Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>--}}
{{--                                                    <path d="M6.88979 21.25C6.14657 21.25 5.62851 21.2496 5.22349 21.2207C4.82565 21.1922 4.59466 21.1391 4.4186 21.0623C3.89833 20.8353 3.48308 20.4201 3.25609 19.8998C3.17928 19.7237 3.12616 19.4927 3.09774 19.0949C3.0688 18.6899 3.06836 18.1718 3.06836 17.4286C3.06836 16.6854 3.0688 16.1673 3.09774 15.7623C3.12616 15.3645 3.17928 15.1335 3.25609 14.9574C3.48308 14.4372 3.89833 14.0219 4.4186 13.7949C4.59466 13.7181 4.82565 13.665 5.22349 13.6366C5.6285 13.6076 6.14657 13.6072 6.88979 13.6072C7.63301 13.6072 8.15107 13.6076 8.55609 13.6366C8.95392 13.665 9.18491 13.7181 9.36097 13.7949C9.88124 14.0219 10.2965 14.4372 10.5235 14.9574C10.6003 15.1335 10.6534 15.3645 10.6818 15.7623C10.7108 16.1673 10.7112 16.6854 10.7112 17.4286C10.7112 18.1718 10.7108 18.6899 10.6818 19.0949C10.6534 19.4927 10.6003 19.7237 10.5235 19.8998C10.2965 20.4201 9.88124 20.8353 9.36097 21.0623C9.18491 21.1391 8.95392 21.1922 8.55609 21.2207C8.15107 21.2496 7.63301 21.25 6.88979 21.25Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>--}}
{{--                                                    <path d="M17.7472 10.3929C17.004 10.3929 16.4859 10.3924 16.0809 10.3635C15.6831 10.3351 15.4521 10.2819 15.276 10.2051C14.7558 9.97813 14.3405 9.56288 14.1135 9.04261C14.0367 8.86655 13.9836 8.63556 13.9552 8.23773C13.9262 7.83271 13.9258 7.31465 13.9258 6.57143C13.9258 5.82821 13.9262 5.31015 13.9552 4.90513C13.9836 4.50729 14.0367 4.2763 14.1135 4.10024C14.3405 3.57997 14.7558 3.16473 15.276 2.93773C15.4521 2.86092 15.6831 2.80781 16.0809 2.77938C16.4859 2.75045 17.004 2.75 17.7472 2.75C18.4904 2.75 19.0085 2.75045 19.4135 2.77938C19.8113 2.80781 20.0423 2.86092 20.2184 2.93773C20.7387 3.16473 21.1539 3.57997 21.3809 4.10024C21.4577 4.2763 21.5108 4.50729 21.5393 4.90513C21.5682 5.31015 21.5686 5.82821 21.5686 6.57143C21.5686 7.31465 21.5682 7.83271 21.5393 8.23773C21.5108 8.63556 21.4577 8.86655 21.3809 9.04261C21.1539 9.56288 20.7387 9.97813 20.2184 10.2051C20.0423 10.2819 19.8113 10.3351 19.4135 10.3635C19.0085 10.3924 18.4904 10.3929 17.7472 10.3929Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>--}}
{{--                                                    <path d="M17.7472 21.25C17.004 21.25 16.4859 21.2496 16.0809 21.2207C15.6831 21.1922 15.4521 21.1391 15.276 21.0623C14.7558 20.8353 14.3405 20.4201 14.1135 19.8998C14.0367 19.7237 13.9836 19.4927 13.9552 19.0949C13.9262 18.6899 13.9258 18.1718 13.9258 17.4286C13.9258 16.6854 13.9262 16.1673 13.9552 15.7623C13.9836 15.3645 14.0367 15.1335 14.1135 14.9574C14.3405 14.4372 14.7558 14.0219 15.276 13.7949C15.4521 13.7181 15.6831 13.665 16.0809 13.6366C16.4859 13.6076 17.004 13.6072 17.7472 13.6072C18.4904 13.6072 19.0085 13.6076 19.4135 13.6366C19.8113 13.665 20.0423 13.7181 20.2184 13.7949C20.7387 14.0219 21.1539 14.4372 21.3809 14.9574C21.4577 15.1335 21.5108 15.3645 21.5393 15.7623C21.5682 16.1673 21.5686 16.6854 21.5686 17.4286C21.5686 18.1718 21.5682 18.6899 21.5393 19.0949C21.5108 19.4927 21.4577 19.7237 21.3809 19.8998C21.1539 20.4201 20.7387 20.8353 20.2184 21.0623C20.0423 21.1391 19.8113 21.1922 19.4135 21.2207C19.0085 21.2496 18.4904 21.25 17.7472 21.25Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>--}}
{{--                                                </svg>--}}
{{--                                            </div>--}}
{{--                                            <span class="">{{ __('Dashboard') }}</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @endif--}}
                    @if($role=='instructor')
                        <li>
                            <a href="{{ route('admin.instructor.profile') }}" class="{{ $activeProfile ?? '' }} d-flex align-items-center cg-10">
                                <div class="d-flex">
                                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.7274 21.3923C19.2716 20.1165 18.2672 18.9892 16.8701 18.1851C15.4729 17.381 13.7611 16.9452 12 16.9452C10.2389 16.9452 8.52706 17.381 7.12991 18.1851C5.73276 18.9892 4.72839 20.1165 4.27259 21.3923" stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"/>
                                        <circle cx="12" cy="8.94522" r="4" stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <span class="">{{ __('Profile') }}</span>
                            </a>
                        </li>
                    @endif
                                @if($role!='alumni'&&$role!='instructor')

{{--                                    <li>--}}
{{--                                        <a href="{{ route($role.'.profile.index') }}" class="{{ $activeProfile ?? '' }} d-flex align-items-center cg-10">--}}
{{--                                            <div class="d-flex">--}}
{{--                                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                                    <path d="M19.7274 21.3923C19.2716 20.1165 18.2672 18.9892 16.8701 18.1851C15.4729 17.381 13.7611 16.9452 12 16.9452C10.2389 16.9452 8.52706 17.381 7.12991 18.1851C5.73276 18.9892 4.72839 20.1165 4.27259 21.3923" stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"/>--}}
{{--                                                    <circle cx="12" cy="8.94522" r="4" stroke="white" stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"/>--}}
{{--                                                </svg>--}}
{{--                                            </div>--}}
{{--                                            <span class="">{{ __('Profile') }}</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                @endif
                    @if($role!='instructor')
                        <li>
                            <a href="#jobPost" data-bs-toggle="collapse" role="button"
                               aria-expanded="{{ isset($showJobPostManagement) ? 'true' : '' }}" aria-controls="jobPost"
                               class="d-flex align-items-center cg-10 {{ isset($showJobPostManagement) ? 'active' : 'collapsed' }}">
                                <div class="d-flex">
                                    <svg width="25" height="26" viewBox="0 0 25 26" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <rect x="5.20801" y="5.11185" width="14.5833" height="17.7083" rx="2" stroke="white"
                                              stroke-opacity="0.7" stroke-width="1.5"/>
                                        <path d="M9.375 10.3202H15.625" stroke="white" stroke-opacity="0.7" stroke-width="1.5"
                                              stroke-linecap="round"/>
                                        <path d="M9.375 14.4868H15.625" stroke="white" stroke-opacity="0.7" stroke-width="1.5"
                                              stroke-linecap="round"/>
                                        <path d="M9.375 18.6535H13.5417" stroke="white" stroke-opacity="0.7" stroke-width="1.5"
                                              stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <span class="">{{__('Manage Jobs')}}</span>
                            </a>
                            <div class="collapse {{ $showJobPostManagement ?? '' }}" id="jobPost" data-bs-parent="#sidebarMenu">
                                <ul class="zSidebar-submenu">
                                    @if($role != 'alumni')
{{--                                        <li>--}}
{{--                                            <a class="{{ $activeJobPostCreate ?? '' }}"--}}
{{--                                               href="{{ route($role.'.jobs.create') }}">{{ __('Create Post') }}</a>--}}
{{--                                        </li>--}}
                                        @if($role == 'admin')
{{--                                            <li>--}}
{{--                                                <a class="{{ $activeMyJobPostList ?? '' }}"--}}
{{--                                                   href="{{ route($role.'.jobs.my-job-post') }}">{{ __('My Post') }}</a>--}}
{{--                                            </li>--}}
                                        @endif
                                    @endif
{{--                                    <li>--}}
{{--                                        <a class="{{ $activePendingJobPostList ?? '' }}"--}}
{{--                                           href="{{ route($role.'.jobs.pending') }}">{{ __('Active Jobs') }}</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a class="{{ $activeAllJobPostList ?? '' }}"--}}
{{--                                           href="{{ route($role.'.jobs.all-job-post') }}">{{ __('All Jobs') }}</a>--}}
{{--                                    </li>--}}
                                </ul>
                            </div>
                        </li>
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
