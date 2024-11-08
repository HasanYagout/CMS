(function ($) {
    "use strict";
    if ($.fn.dataTable.isDataTable("#attendanceTable")) {
        $("#attendanceTable").DataTable().clear().destroy();
    }

    var table = $("#attendanceTable").DataTable({
        pageLength: 10,
        ordering: true,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: true,
        ajax: {
            url: $("#attendance-route").val(),
            data: function (d) {
                d.selectedDepartment = $("#department :selected").val();
                d.selectedPassingYear = $("#passing-year :selected").val();
                d.isMember = $("#is-member :selected").val();
            },
        },
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search Activity",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"d-flex align-items-center cg-5"<"tableSearch float-start"f><"z-filter-button">>><"col-sm-6"<"tableLengthInput float-end"l>><"col-sm-12"<"z-filter-block">>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {
                data: "course",
                name: "course",
                responsivePriority: 1,
                searchable: true,
                orderable: true,
            },
            // {
            //     data: "chapter",
            //     name: "chapter",
            //     responsivePriority: 1,
            //     searchable: true,
            //     orderable: true,
            // },
            // {
            //     data: "lecture",
            //     name: "lecture",
            //     responsivePriority: 1,
            //     searchable: true,
            //     orderable: true,
            // },
            // {
            //     data: "title",
            //     name: "title",
            //     responsivePriority: 1,
            //     searchable: true,
            //     orderable: true,
            // },
            // {
            //     data: "due_date",
            //     name: "due_date",
            //     responsivePriority: 1,
            //     searchable: true,
            //     orderable: true,
            // },
            // {
            //     data: "status",
            //     name: "status",
            //     responsivePriority: 1,
            //     searchable: true,
            //     orderable: true,
            // },
            {
                data: "action",
                name: "action",
                responsivePriority: 1,
                searchable: true,
                orderable: true,
            },
        ],
        initComplete: function (settings, json) {
            $(".z-filter-block").html($("#search-section").html());
            $("#search-section").remove();
        },
    });
})(jQuery);
