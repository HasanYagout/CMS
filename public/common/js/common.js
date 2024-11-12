(function ($) {
    "use strict";

    window.getEditModal = function (url, modalId) {
        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {
                var $modal = $(document).find(modalId);

                $modal.find(".modal-content").html(data);

                // Initialize Select2 for skills
                var skillsSelect = $modal.find(".skills-select");
                if (skillsSelect.length) {
                    skillsSelect.select2({
                        tags: true,
                        tokenSeparators: [",", " "],
                        placeholder: "Add your skills",
                        allowClear: true,
                        maximumSelectionLength: 5,
                    });

                    var selectedSkills = skillsSelect.data("skills");
                    skillsSelect.val(selectedSkills).trigger("change");
                }

                // Other Select2 initializations
                var selectModal = $modal.find(".sf-select-edit-modal");
                if (selectModal.length) {
                    selectModal.select2({
                        dropdownCssClass: "sf-select-dropdown",
                        selectionCssClass: "sf-select-section",
                        dropdownParent: $modal,
                    });
                }

                // Initialize NiceSelect for dropdowns without search
                var niceSelect = $modal.find(".sf-select-without-search");
                if (niceSelect.length) {
                    niceSelect.niceSelect();
                }

                // Add custom class to date-time picker elements
                var dateTimePicker = $modal.find(".date-time-picker");
                if (dateTimePicker.length) {
                    dateTimePicker.each(function () {
                        $(this)
                            .closest(".primary-form-group-wrap")
                            .addClass("calendarIcon");
                    });
                }

                // Initialize date-time picker
                if (dateTimePicker.length) {
                    dateTimePicker.daterangepicker({
                        singleDatePicker: true,
                        timePicker: true,
                        locale: {
                            format: "Y-M-D h:mm",
                        },
                    });
                }

                // Initialize Summernote
                var summernote = $modal.find(".summernoteOne");
                if (summernote.length) {
                    summernote.summernote({
                        placeholder: "Write description...",
                        tabsize: 2,
                        minHeight: 183,
                        toolbar: [
                            ["font", ["bold", "italic", "underline"]],
                            ["para", ["ul", "ol", "paragraph"]],
                        ],
                    });
                }

                // Show the modal
                $modal.modal("show");
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
            },
        });
    };

    window.deleteItem = function (url, id) {
        Swal.fire({
            title: "Sure! You want to delete?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Delete It!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: url,

                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },
                    success: function (data) {
                        Swal.fire({
                            title: "Deleted",
                            html: ' <span style="color:red">Item has been deleted</span> ',
                            timer: 2000,
                            icon: "success",
                        });
                        toastr.success(data.message);
                        $("#" + id)
                            .DataTable()
                            .ajax.reload();
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.message);
                    },
                });
            }
        });
    };
})(jQuery);
