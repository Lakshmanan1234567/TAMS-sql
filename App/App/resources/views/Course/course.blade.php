@extends('layouts.layout')
@section('content')




<div class="container-fluid">
    <div class="page-header">


    <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="f-16 fa fa-home"></i></a></li>
                                <li class="breadcrumb-item "><a href="{{ url('/') }}/Course">Course</a></li>
                                <li class="breadcrumb-item active">@if($isEdit) Update @else Create @endif</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Course</h4>
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
                    <h5>Course</h5>
                </div>

                <div class="card-body p-20">
                    <div class="row mt-20">
                        <div class="col-md-6">
                        
                                
                            <div class="form-group">
                                <label for="course">Course <span class="required">*</span></label>
                                <input type="text" id="course" class="form-control" placeholder="Course" value="{{ $isEdit ? $EditData->C_Name : '' }}">
                                <span class="errors" id="course-err"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject">Subject <span class="required">*</span></label>
                                <input type="text" id="subject" class="form-control" placeholder="Subject" value="{{ $isEdit ? $EditData->C_Description : '' }}">
                                <span class="errors" id="subject-err"></span>
                            </div>
                        </div>
                        

                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slot">Slot <span class="required">*</span></label>
                                <input type="text" id="slot" class="form-control" placeholder="Slot" value="{{ $isEdit ? $EditData->C_Slot : '' }}">
                                <span class="errors" id="slot-err"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration">Duration <b>(HOURS)</b><span class="required">*</span></label>
                                <input type="text" id="duration" class="form-control" placeholder="Duration" value="{{ $isEdit ? $EditData->C_Duration : '' }}">
                                <span class="errors" id="duration-err"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="lstActiveStatus">Active Status</label>
                            <select class="form-control" id="lstActiveStatus">
                                <option value="1" {{ $isEdit && $EditData->ActiveStatus == "1" ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $isEdit && $EditData->ActiveStatus == "0" ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>  

                        <div class="col-md-6">
</div>
<div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                          
                                <label for="documents">Documents </label>
                                <input type="file" id="documents" class="dropify" data-default-file="{{ $isEdit && $EditData->C_Documents ? url('/').$EditData->C_Documents : '' }}">
                                </div>
                        </div>
                        <div class="col-md-4"></div>

                    </div>
                    
                    <input type="hidden" id="IsEditval" class="form-control" value="{{ $isEdit }}">

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view'])
                            <a href="{{ url('/Course') }}" class="btn btn-outline-light btn-air-success btn-sm" id="btnCancel">Back</a>
                            @endif

                            @if((($crud['add'] && !$isEdit) || ($crud['edit'] && $isEdit)))
                            <button class="btn btn-outline-primary btn-air-success btn-sm" id="btnSubmit">@if($isEdit) Update @else Save @endif</button>
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

    $('.dropify').dropify();

    var isEdit = $('#IsEditval').val() == "1";

    const formValidation = () => {
        $('.errors').html('');
        let status = true;

        let course = $('#course').val();
        let subject = $('#subject').val();
        let documents = $('#documents').val();
        let slot = $('#slot').val();
        let duration = $('#duration').val();
        let lstActiveStatus = $('#lstActiveStatus').val();

        if (course == "") {
            $('#course-err').html('Course  is required');
            status = false;
        }
        if (subject == "") {
            $('#subject-err').html('Subject is required');
            status = false;
        }
       
        if (slot == "") {
            $('#slot-err').html('Slot is required');
            status = false;
        }
        if (duration == "") {
            $('#duration-err').html('Duration is required');
            status = false;
        }
        
        return status;
    }

    $('#btnSubmit').click(function() {
        let status = formValidation();
        if (status) {
            swal({
                title: "Are you sure?",
                text: "You want @if($isEdit) Update @else Save @endif this Course!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-primary",
                confirmButtonText: "Yes, @if($isEdit) Update @else Save @endif it!",
                closeOnConfirm: false
            }, function() {
                swal.close();
                btnLoading($('#btnSubmit'));
                let formData = new FormData();
                formData.append('C_Name', $('#course').val());
                formData.append('C_Description', $('#subject').val());
                formData.append('C_Documents', $('#documents')[0].files[0]);
                formData.append('C_Slot', $('#slot').val());
                formData.append('C_Duration', $('#duration').val());
                formData.append('ActiveStatus', $('#lstActiveStatus').val());
                
                @if($isEdit == true)
                formData.append('Course_Id', "{{ $EditData->Course_Id }}");
                var  submiturl = "{{ url('/') }}/Course/edit/{{$EditData->Course_Id}}";
                @else
                var submiturl = "{{ url('/') }}/Course/create";
                @endif

                $.ajax({
                    type: "post",
                    url: submiturl,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                percentComplete = parseFloat(percentComplete).toFixed(2);
                                $('#divProcessText').html(percentComplete + '% Completed.<br> Please wait for until upload process complete.');
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
                                title: "@if($isEdit)Updated @else Created @endif Successfully",
                                text: "",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-outline-primary",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            }, function() {
                                window.location.href = "{{ url('/') }}/Course";
                            });
                        } else {
                            if (response['errors'] != undefined) {
                                $('.errors').html('');
                                $.each(response['errors'], function(KeyName, KeyValue) {
                                    if (KeyName == "C_Name") {
                                        $('#course-err').html(KeyValue);
                                    } else if (KeyName == "C_Description") {
                                        $('#subject-err').html(KeyValue);
                                    } else if (KeyName == "C_Documents") {
                                        $('#documents-err').html(KeyValue);
                                    } else if (KeyName == "C_Slot") {
                                        $('#slot-err').html(KeyValue);
                                    } else if (KeyName == "C_Duration") {
                                        $('#duration-err').html(KeyValue);
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
        $('body').append('<div id="resultLoading" style="display:none"><div><img src="/path/to/loading.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
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
