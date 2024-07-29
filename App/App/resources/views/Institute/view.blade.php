@extends('layouts.layout')
@section('content')
    <style>
        /* .nav-tabs .nav-link {
                                                                                        padding: 0.75rem 13.15rem;
                                                                                    } */
        .nav-tabs .nav-link.active {
            background-color: #b2c9e1;
            color: #09497a;
        }

        @media (max-width: 768px) {
            .nav-tabs {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-tabs .nav-link {
                margin: 0.5rem;
                padding: 0.75rem 1.5rem;
            }
        }
    </style>
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li> --}}
                                <li class="breadcrumb-item active">Institutes</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Institutes</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title ">Institutes</h4>
                            <p class="text-muted font-13 mb-4 ">Manage your approved and waiting institutes here.</p>

                            <ul class="nav nav-tabs " id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="institutes-tab" data-toggle="tab" href="#institutes"
                                        role="tab" aria-controls="institutes" aria-selected="true">Approved</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab"
                                        aria-controls="other" aria-selected="false">Waiting</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="institutes" role="tabpanel"
                                    aria-labelledby="institutes-tab">
                                    <table class="table " id="tblInstitutesApproved">
                                        <thead>
                                            <tr>
                                                <th class="text">Institute Name</th>
                                                <th class="text">Course</th>
                                                <th class="text">District</th>
                                                <th class="text">Slot</th>
                                                <th class="text">User Status</th>
                                                <th class="text">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="fade" id="other" role="tabpanel" aria-labelledby="other-tab">

                                    <table class="table " id="tblInstitutesWaiting">
                                        <thead>
                                            <tr>
                                                <th class="text">Institute Name</th>
                                                <th class="text">Course</th>
                                                <th class="text">District</th>
                                                <th class="text">Slot</th>
                                                <th class="text">User Status</th>
                                                <th class="text">Status</th>
                                                <th class="text">Status Update</th>
                                                <th class="text">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>

            <!-- end row-->

        </div> <!-- container -->

    </div>

    <!-- Include Bootstrap's JavaScript dependencies -->

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include Bootstrap Multiselect JS -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"> --}}
    </script>

    <script>
        $(document).ready(function() {
            $('.multiselect').select2();
            let RootUrl = $('#txtRootUrl').val();

            const initializeDataTable = (selector, url, title) => {
                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().clear().destroy();
                }

                $(selector).DataTable({
                    "bProcessing": true,
                    "bServerSide": true,
                    "ordering": false,
                    "ajax": {
                        "url": url + "?_token=" + $('meta[name=_token]').attr('content'),
                        "headers": {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        "type": "POST"
                    },
                    deferRender: true,
                    responsive: true,
                    dom: 'Bfrtip',
                    "iDisplayLength": 10,
                    "lengthMenu": [
                        [10, 25, 50, 100, 250, 500, -1],
                        [10, 25, 50, 100, 250, 500, "All"]
                    ],
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            footer: true,
                            title: title,
                            "action": DataTableExportOption,
                            exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
                        },
                        {
                            extend: 'copy',
                            footer: true,
                            title: title,
                            "action": DataTableExportOption,
                            exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
                        },
                        {
                            extend: 'csv',
                            footer: true,
                            title: title,
                            "action": DataTableExportOption,
                            exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            title: title,
                            "action": DataTableExportOption,
                            exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
                        },
                        {
                            extend: 'pdf',
                            footer: true,
                            title: title,
                            "action": DataTableExportOption,
                            exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
                        }
                    ],
                    columnDefs: [{
                            "className": "dt-center",
                            "targets": 2
                        },
                        {
                            "className": "dt-center",
                            "targets": 3
                        }
                    ],
                    initComplete: function() {
                        var table = this.api();

                        // Apply dropdown filter to specific columns
                        [0, 1, 2, 3].forEach(function(index) {
                            var column = table.column(index);
                            var headerCell = $(column.header());

                            // Create a select element and append it to the header
                            var select = $('<select><option value=""></option></select>')
                                .appendTo(headerCell)
                                .on('change', function() {
                                    // Get the selected value
                                    var val = $(this).val();
                                    console.log("Selected Value:",
                                        val); // Debugging: Log the selected value

                                    // Perform the search without regex
                                    column
                                        .search(val ? val : '', false, false)
                                        .draw();
                                });

                            // Populate the select element with unique values from the column
                            column.data().unique().sort().each(function(d, j) {
                                console.log("Column Value:",
                                    d); // Debugging: Log each value in the column
                                select.append('<option value="' + d + '">' + d +
                                    '</option>');
                            });
                        });
                    }
                });
            };



            const LoadApprovedTable = () => {
                @if ($crud['view'] == 1)
                    initializeDataTable('#tblInstitutesApproved', RootUrl + "Institute/Approved/data",
                        'Approved Institutes');
                @endif
            };

            const LoadWaitingTable = () => {
                @if ($crud['view'] == 1)
                    initializeDataTable('#tblInstitutesWaiting', RootUrl + "Institute/Waiting/data",
                        'Waiting Institutes');
                @endif
            };

            const reloadTables = () => {
                $('#tblInstitutesApproved').DataTable().ajax.reload();
                $('#tblInstitutesWaiting').DataTable().ajax.reload();
            };

            $(document).on('click', '.btn-status', function() {
                let ID = $(this).attr('data-id');
                let status = $(this).attr('data-status');
                let action = status == 1 ? 'Approve' : 'Reject';

                swal({
                        title: "Are you sure?",
                        text: "You want to " + action + " this institute?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-outline-warning",
                        confirmButtonText: "Yes, " + action + " it!",
                        closeOnConfirm: false
                    },
                    function() {
                        swal.close();
                        $.ajax({
                            type: "post",
                            url: RootUrl + "Institute/statusUpdate",
                            headers: {
                                'X-CSRF-Token': $('meta[name=_token]').attr('content')
                            },
                            data: {
                                user_id: ID,
                                data: {
                                    status: status
                                }
                            },
                            dataType: "json",
                            success: function(response) {
                                swal.close();
                                if (response.status == true) {
                                    toastr.success(response.message, "Success", {
                                        positionClass: "toast-top-right",
                                        containerId: "toast-top-right",
                                        showMethod: "slideDown",
                                        hideMethod: "slideUp",
                                        progressBar: !0
                                    })

                                    window.location.reload();
                                    // reloadTables();

                                } else {
                                    toastr.error(response.message, "Failed", {
                                        positionClass: "toast-top-right",
                                        containerId: "toast-top-right",
                                        showMethod: "slideDown",
                                        hideMethod: "slideUp",
                                        progressBar: !0
                                    })
                                }
                            }
                        });
                    });
            });


            $(document).on('click', '.btn-edit', function() {
                let ID = $(this).attr('data-id');
                let row = $(this).closest('tr');

                $.ajax({
                    type: "GET",
                    url: RootUrl + "Institute/getCourses/" + ID,
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === false) {
                            alert(response.message);
                            return;
                        }
                        let currentCourses = response.currentCourses.map(course => course
                            .Course_Id);
                        let availableCourses = response.availableCourses;

                        row.find('td').each(function(index) {
                            if (index == 1) {
                                let inputId = "Institute_Course";
                                let courseOptions =
                                    '<select id="Institute_Course" class="form-control multiselect" multiple>';
                                availableCourses.forEach(course => {
                                    let isSelected = currentCourses.includes(
                                        course.Course_Id);
                                    courseOptions +=
                                        `<option value="${course.Course_Id}" ${isSelected ? 'selected' : ''}>${course.Course_Id} - ${course.C_Name}</option>`;
                                });
                                courseOptions += '</select>';
                                $(this).html(courseOptions);
                                $('.multiselect').select2({
                                    multiple: true
                                });
                            }
                        });

                        row.find('.btn-edit').hide();
                        row.find('.btn-submit').show();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });

            $(document).on('click', '.btn-submit', function() {
                let ID = $(this).attr('data-id');
                let row = $(this).closest('tr');

                let data = {
                    // Institute_Name: row.find('#Institute_Name').val(),
                    Institute_Course: row.find('#Institute_Course').val()
                    // DistrictID: row.find('#DistrictID').val(),
                    // Institute_Slot: row.find('#Institute_Slot').val()
                };

                $.ajax({
                    type: "post",
                    url: RootUrl + "Institute/update/data",
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    data: {
                        user_id: ID,
                        data: data
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == true) {
                            reloadTables();
                            row.find('td').each(function(index) {
                                let input = $(this).find('input');
                                if (input.length > 0) {
                                    $(this).html(input.val());
                                }
                            });

                            row.find('.btn-submit').hide();
                            row.find('.btn-edit').show();

                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                        } else {
                            toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                        }
                        // LoadWaitingTable();
                    }
                });

            });

            LoadWaitingTable();
            LoadApprovedTable();


            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let target = $(e.target).attr("href");
                if (target == "#institutes") {
                    $('#tblInstitutesApproved').DataTable().ajax.reload();
                } else if (target == "#other") {
                    $('#tblInstitutesWaiting').DataTable().ajax.reload();
                }
            });
        });
    </script>
@endsection
