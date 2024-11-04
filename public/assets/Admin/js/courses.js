(function ($) {
    "use strict";
    if ($.fn.dataTable.isDataTable('#coursesTable')) {
        $('#coursesTable').DataTable().clear().destroy();
    }
    var table = $("#coursesTable").DataTable({
        pageLength: 10,
        ordering: true,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: true,
        ajax: {
            url: $('#course-route').val(),
            data: function (d) {
                d.selectedDepartment = $('#department :selected').val();
                d.selectedPassingYear = $('#passing-year :selected').val();
                d.isMember = $('#is-member :selected').val();
            }
        },
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search Instructors",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"d-flex align-items-center cg-5"<"tableSearch float-start"f><"z-filter-button">>><"col-sm-6"<"tableLengthInput float-end"l>><"col-sm-12"<"z-filter-block">>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {
                "data": "name",
                "name": "name",
                "responsivePriority": 1,
                "searchable": true,
                "orderable": true
            },

            {
                "data": "image",
                "name": "image",
                "responsivePriority": 1,
                "searchable": true,
                "orderable": true
            },
            {
                "data": "semester",
                "name": "semester",
                "responsivePriority": 1,
                "searchable": true,
                "orderable": true
            },
            {
                "data": "start_date",
                "name": "start_date",
                "responsivePriority": 1,
                "searchable": true,
                "orderable": true
            },
            {
                "data": "end_date",
                "name": "end_date",
                "responsivePriority": 1,
                "searchable": true,
                "orderable": true
            },
            {
                "data": "status",
                "name": "status",
                "responsivePriority": 1,
                "searchable": true,
                "orderable": true
            },
            {
                "data": "action",
                "name": "action",
                "responsivePriority": 1,
                "searchable": true,
                "orderable": true
            },

        ],
        initComplete: function (settings, json) {
            $('.z-filter-block').html($('#search-section').html());
            $('#search-section').remove();
        },
    });

    // Event handler for toggle status checkbox change
    $(document).on('change', '.toggle-status', function (e) {
        var $switch = $(this);
        var instructorId = $switch.data('id');
        var newStatus = $switch.is(':checked') ? 1 : 0;
        var updateUrl = $('#instructor-status-route').val().replace(':id', instructorId);
        Swal.fire({
            title: 'Sure! You want to change the status?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change It!'
        }).then((result) => {
            if (result.value) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: updateUrl,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        status: newStatus
                    },
                    success: function (response) {
                        if (response.message) {
                            toastr.success(response.message);
                        } else {
                            toastr.error('Failed to update status.');
                        }
                        table.ajax.reload();
                    },
                    error: function (error) {
                        toastr.error('Failed to update status.');
                        console.error(error);
                        table.ajax.reload();
                    }
                });
            } else {
                $switch.prop('checked', !newStatus);
                toastr.info('Status change cancelled.');
            }
        });
    });

    window.deleteItem = function (url, id) {
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deleted',
                            html: '<span style="color:red">Item has been deleted</span>',
                            timer: 2000,
                            icon: 'success'
                        });
                        toastr.success('Course deleted successfully.');
                        table.ajax.reload();
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.message || 'An error occurred while deleting.');
                    }
                });
            }
        });
    };
})(jQuery);
