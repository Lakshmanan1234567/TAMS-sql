@extends('layouts.layout')
@section('content')




    <div class="container-fluid">
        <div class="page-header">


            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="f-16 fa fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item "><a href="{{ url('/') }}/Enroll">Enroll</a></li>
                                <li class="breadcrumb-item active">
                                    @if ($isEdit)
                                        Update
                                    @else
                                        Create
                                    @endif
                                </li>
                            </ol>
                        </div>
                        <h4 class="page-title">Enroll</h4>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h5>Enroll</h5>
                    </div>

                    <div class="card-body p-20">
                        <div class="row mt-20">
                            <div class="col-md-6">


                                <div class="form-group">
                                    <label for="District">District <span class="required">*</span></label><br>
                                    <select class="form-control select2" id="District">
                                        <option value="">Select a District</option>
                                    </select>
                                    <span class="errors fcsForm2" id="District-err"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Institute">Institute <span class="required">*</span></label><br>
                                    <select class="form-control select2" id="Institute">
                                        <option value="">Select a Institute</option>
                                    </select>
                                    <span class="errors fcsForm2" id="Institute-err"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course">Course <span class="required">*</span></label><br>
                                    <select class="form-control select2" id="course">
                                        <option value="">Select a course</option>
                                    </select>
                                    <span class="errors fcsForm2" id="course-err"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Student">Students <span class="required">*</span></label><br>
                                    <select class="form-control select2 multiselect" id="Student" multiple>
                                        <option value="">Select Students</option>
                                    </select>
                                    <span class="errors" id="slot-err"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration">From Date <span class="required">*</span></label>
                                    <input type="date" id="From_Date" class="form-control" placeholder="From Date"
                                        value="{{ $isEdit ? $EditData->From_Date : '' }}">
                                    <span class="errors" id="duration-err"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration">To Date <span class="required">*</span></label>
                                    <input type="date" id="To_Date" class="form-control" placeholder="To Date"
                                        value="{{ $isEdit ? $EditData->To_Date : '' }}">
                                    <span class="errors" id="duration-err"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="Class_Status">Class Status <span class="required">*</span></label>
                                <select class="form-control" id="Class_Status" required>
                                    <option value="1"
                                        {{ $isEdit && $EditData->Class_Status == '1' ? 'selected' : '' }}>Weekdays</option>
                                    <option value="0"
                                        {{ $isEdit && $EditData->Class_Status == '0' ? 'selected' : '' }}>Weekend</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration">Class Timing <span class="required">*</span></label>
                                    <input type="time" id="Class_Timing" class="form-control" placeholder="Class_Timing"
                                        value="{{ $isEdit ? $EditData->Class_Timing : '' }}">
                                    <span class="errors" id="duration-err"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="lstActiveStatus">Active Status <span class="required">*</span></label>
                                <select class="form-control" id="lstActiveStatus" required>
                                    <option value="1"
                                        {{ $isEdit && $EditData->ActiveStatus == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0"
                                        {{ $isEdit && $EditData->ActiveStatus == '0' ? 'selected' : '' }}>InActive</option>
                                </select>
                            </div>

                        </div>

                        {{-- <input type="hidden" id="IsEditval" class="form-control" value="{{ $isEdit }}"> --}}

                        <div class="row">
                            <div class="col-sm-12 text-right">
                                @if ($crud['view'])
                                    <a href="{{ url('/Course') }}" class="btn btn-outline-light btn-air-success btn-sm"
                                        id="btnCancel">Back</a>
                                @endif

                                @if (($crud['add'] && !$isEdit) || ($crud['edit'] && $isEdit))
                                    <button class="btn btn-outline-primary btn-air-success btn-sm" id="btnSubmit">
                                        @if ($isEdit)
                                            Update
                                        @else
                                            Enroll
                                        @endif
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.multiselect').select2();
            let RootUrl = $('#txtRootUrl').val();

            function loadDistricts() {
                $.ajax({
                    url: '{{ url('/') }}/Enroll/getDistricts',
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            var $districtSelect = $('#District');
                            $districtSelect.empty().append(
                                '<option value="">Select a District</option>');

                            $.each(response.data, function(index, district) {
                                $districtSelect.append('<option value="' + district.DID + '">' +
                                    district.DName + '</option>');
                            });

                            $districtSelect.select2();
                        } else {
                            console.error('Error:', response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            function loadInstitutes(districtId) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: '{{ url('/') }}/Enroll/getInstitutes',
                    type: 'POST',
                    data: {
                        district_id: districtId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            var $instituteSelect = $('#Institute');
                            $instituteSelect.empty().append(
                                '<option value="">Select an Institute</option>');

                            $.each(response.data, function(index, institute) {
                                $instituteSelect.append('<option value="' + institute
                                    .Institute_Id + '">' +
                                    institute.Institute_Name + '</option>');
                            });

                            $instituteSelect.select2();
                        } else {
                            console.error('Error:', response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

           function loadCourses(instituteId) {
    console.log('Loading courses for instituteId:', instituteId); // Log the instituteId
    $.ajax({
        headers: {
            'X-CSRF-Token': $('meta[name=_token]').attr('content')
        },
        url: '{{ url('/') }}/Enroll/getCourses',
        type: 'POST',
        data: {
            institute_id: instituteId
        },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                var $courseSelect = $('#course');
                $courseSelect.empty().append('<option value="">Select a Course</option>');

                $.each(response.data, function(index, course) {
                    $courseSelect.append('<option value="' + course.Course_Id + '">' + course.C_Name + '</option>');
                });

                $courseSelect.select2();
            } else {
                console.error('Error:', response.message);
            }
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr.responseText);
        }
    });
}


            function loadStudents(districtId, instituteId) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: '{{ url('/') }}/Enroll/getStudents',
                    type: 'POST',
                    data: {
                        district_id: districtId,
                        institute_id: instituteId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            var $studentSelect = $('#Student');
                            $studentSelect.empty().append('<option value="">Select Students</option>');

                            $.each(response.data, function(index, student) {
                                $studentSelect.append('<option value="' + student.Student_Id +
                                    '">' +
                                    student.Name + '</option>');
                            });

                            $('.multiselect').select2();
                        } else {
                            console.error('Error:', response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            // Load districts on page load
            loadDistricts();

            // Event listener for district selection
            $('#District').change(function() {
                var districtId = $(this).val();
                if (districtId) {
                    loadInstitutes(districtId);
                } else {
                    $('#Institute').empty().append('<option value="">Select an Institute</option>')
                        .select2();
                    $('#Course').empty().append('<option value="">Select a Course</option>').select2();
                }
            });

            // Event listener for institute selection
            $('#Institute').change(function() {
                var instituteId = $(this).val();
                if (instituteId) {
                    loadCourses(instituteId);
                } else {
                    $('#Course').empty().append('<option value="">Select a Course</option>').select2();
                }
            });
            $('#course').on('change', function() {
                var courseId = $(this).val();
                if (courseId) {
                    loadStudents(courseId);
                } else {
                    $('#Student').empty().append('<option value="">Select Students</option>').select2();
                }
            });

            // Also clear the student select when district changes
            // var $studentSelect = $('#Student');
            // $studentSelect.empty().append('<option value="">Select Students</option>');
            // $studentSelect.select2();
            // });
            var isEdit = $('#IsEditval').val() == "1";

            const formValidation = () => {
                $('.errors').html('');
                let status = true;

                let Institute = $('#Institute').val();
                let Student = $('#Student').val();
                let From_Date = $('#From_Date').val();
                let To_Date = $('#To_Date').val();
                let Class_Status = $('#Class_Status').val();
                let Class_Timing = $('#Class_Timing').val();
                let lstActiveStatus = $('#lstActiveStatus').val();

                if (Institute == "") {
                    $('#Institute-err').html('Institute  is required');
                    status = false;
                }
                if (Student == "") {
                    $('#Student-err').html('Student is required');
                    status = false;
                }
                if (From_Date == "") {
                    $('#From_Date-err').html('From_Date is required');
                    status = false;
                }
                if (To_Date == "") {
                    $('#To_Date-err').html('To_Date is required');
                    status = false;
                }
                if (Class_Status == "") {
                    $('#Class_Status-err').html('Class_Status is required');
                    status = false;
                }
                if (Class_Timing == "") {
                    $('#Class_Timing-err').html('Class_Timing is required');
                    status = false;
                }

                return status;
            }

            $('#btnSubmit').click(function() {
                let status = formValidation();
                if (status) {
                    swal({
                        title: "Are you sure?",
                        text: "You want @if ($isEdit) Update @else Enroll @endif this Course!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-outline-primary",
                        confirmButtonText: "Yes, @if ($isEdit) Update @else Enroll @endif it!",
                        closeOnConfirm: false
                    }, function() {
                        swal.close();
                        btnLoading($('#btnSubmit'));
                        let formData = new FormData();
                        formData.append('Institution_ID', $('#Institute').val());
                        formData.append('Student_Id', $('#Student').val());
                        formData.append('From_Date', $('#From_Date').val());
                        formData.append('To_Date', $('#To_Date').val());
                        formData.append('Class_Status', $('#Class_Status').val());
                        formData.append('Class_Timing', $('#Class_Timing').val());
                        formData.append('ActiveStatus', $('#lstActiveStatus').val());

                        @if ($isEdit == true)
                            formData.append('Enroll_Id', "{{ $EditData->Enroll_Id }}");
                            var submiturl =
                                "{{ url('/') }}/Enroll/edit/{{ $EditData->Enroll_Id }}";
                        @else
                            var submiturl = "{{ url('/') }}/Enroll/save";
                        @endif

                        $.ajax({
                            type: "post",
                            url: submiturl,
                            headers: {
                                'X-CSRF-Token': $('meta[name=_token]').attr('content')
                            },
                            data: formData,
                            cache: false,
                            processData: false,
                            contentType: false,
                            xhr: function() {
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = (evt.loaded / evt
                                            .total) * 100;
                                        percentComplete = parseFloat(
                                            percentComplete).toFixed(2);
                                        $('#divProcessText').html(
                                            percentComplete +
                                            '% Completed.<br> Please wait for until upload process complete.'
                                        );
                                    }
                                }, false);
                                return xhr;
                            },
                            beforeSend: function() {
                                ajaxindicatorstart("Please wait.");
                            },
                            success: function(response) {
                                ajaxindicatorstop();
                                btnReset($('#btnSubmit'));
                                if (response['status']) {
                                    swal({
                                        title: "@if ($isEdit)Updated @else Created @endif Successfully",
                                        text: "",
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonClass: "btn-outline-primary",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    }, function() {
                                        window.location.href =
                                            "{{ url('/') }}/Enroll";
                                    });
                                } else {
                                    if (response['errors'] != undefined) {
                                        $('.errors').html('');
                                        $.each(response['errors'], function(KeyName,
                                            KeyValue) {
                                            if (KeyName == "Institution_ID") {
                                                $('#Institute-err').html(
                                                    KeyValue);
                                            } else if (KeyName ==
                                                "Student_Id") {
                                                $('#student-err').html(
                                                    KeyValue);
                                            } else if (KeyName == "From_Date") {
                                                $('#From_Date-err').html(
                                                    KeyValue);
                                            } else if (KeyName == "To_Date") {
                                                $('#To_Date-err').html(
                                                    KeyValue);
                                            } else if (KeyName ==
                                                "Class_Timing") {
                                                $('#Class_Timing-err').html(
                                                    KeyValue);
                                            }
                                        });
                                    }
                                }
                            }
                        });
                    });
                }
            });
        });

        function btnLoading(button) {
            button.prop('disabled', true);
            button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        }

        function btnReset(button) {
            button.prop('disabled', false);
            button.html('Submit');
        }

        function ajaxindicatorstart(text) {
            if ($('body').find('#resultLoading').attr('id') != 'resultLoading') {
                $('body').append('<div id="resultLoading" style="display:none"><div><img src="/path/to/loading.gif"><div>' +
                    text + '</div></div><div class="bg"></div></div>');
            }
            $('#resultLoading').css({
                'width': '100%',
                'height': '100%',
                'position': 'fixed',
                'z-index': '10000000',
                'top': '0',
                'left': '0',
                'right': '0',
                'bottom': '0',
                'margin': 'auto'
            });
            $('#resultLoading .bg').css({
                'background': '#000000',
                'opacity': '0.7',
                'width': '100%',
                'height': '100%',
                'position': 'absolute',
                'top': '0'
            });
            $('#resultLoading>div:first').css({
                'width': '250px',
                'height': '75px',
                'text-align': 'center',
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'right': '0',
                'bottom': '0',
                'margin': 'auto',
                'font-size': '16px',
                'z-index': '10',
                'color': '#ffffff'
            });
            $('#resultLoading .bg').height('100%');
            $('#resultLoading').fadeIn(300);
            $('body').css('cursor', 'wait');
        }

        function ajaxindicatorstop() {
            $('#resultLoading .bg').height('100%');
            $('#resultLoading').fadeOut(300);
            $('body').css('cursor', 'default');
        }
    </script>

@endsection
