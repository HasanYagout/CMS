<!-- In your layout file (e.g., layout.blade.php) -->
<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.header')

<body>

<div class="overflow-x-hidden">

    <div id="preloader">
        <div id="preloader_status">
            <img src="{{ asset('frontend/images/liu-logo.png') }}" alt="{{ getOption('app_name') }}" />
        </div>
    </div>
    <!-- Main Content -->
    <div class="zMain-wrap">
        <!-- Sidebar -->
        @include('layouts.course_sidebar')
        <!-- Main Content -->
        <div style="width: 80%" class="zMainContent  ms-auto">


            @yield('content')

        </div>
    </div>
</div>

@include('layouts.script')

<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

@stack('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    function resetPassword(url, id) {
        Swal.fire({
            title: 'Reset Password',
            text: 'Are you sure you want to reset this Admin\'s password?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Reset It!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.message) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                if (response.logout) {
                                    // Redirect to login page if logout is true
                                    {{--                                    window.location.href = {{route('auth.login')}}; // Change to your login URL--}}
                                } else {
                                    // Optionally handle success for other instructors
                                    window.location.reload(); // Reload the current page
                                }
                            });
                        } else {
                            Swal.fire('Error', 'Failed to reset password.', 'error');
                        }
                    },
                    error: function (error) {
                        Swal.fire('Error', 'Failed to reset password: ' + error.responseJSON.error, 'error');
                    }
                });
            }
        });
    }
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });

    @if(Session::has('success'))
    toastr.success("{{ session('success') }}");
    @endif
    @if(Session::has('error'))
    toastr.error("{{ session('error') }}");
    @endif
    @if(Session::has('info'))
    toastr.info("{{ session('info') }}");
    @endif
    @if(Session::has('warning'))
    toastr.warning("{{ session('warning') }}");
    @endif

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    toastr.error("{{ $error }}");
    @endforeach
    @endif
</script>
</body>
</html>
