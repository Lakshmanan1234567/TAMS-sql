@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Operations</li>
					<li class="breadcrumb-item">Phase Update </li>
                    <li class="breadcrumb-item">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row d-flex justify-content-center">
		<div class="col-sm-6">
			<div class="card">
				<div class="card-header text-center"><h5 class="mt-10">Phase Update </h5></div>
				<div class="card-body " >
                    
                    <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstAssignedOfficers">  Technical Assistant <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstAssignedOfficers" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->THID; }?>" disabled>
  
                                </select>
                                <div class="errors" id="lstAssignedOfficers-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstBeneficiary"> Beneficiary’s   <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstBeneficiary" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->BID; }?>" disabled>
  
                                </select>
                                <div class="errors" id="lstBeneficiary-err"></div>
                            </div>
                        </div>
                        
                         <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstControctor">  Contractor  <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstControctor" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->CID; }?>" disabled>
  
                                </select>
                                <div class="errors" id="lstControctor-err"></div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstHousingType">  Housing Type  <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstHousingType" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->HTID; }?>" disabled>
  
                                </select>
                                <div class="errors" id="lstHousingType-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstHousingphase">  Housing Phase  <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstHousingphase" disabled data-HTID="<?php if($isEdit==true){ echo $EditData[0]->HTID; }?>" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->HPID; }?>" >
  
                                </select>
                                <div class="errors" id="lstHousingphase-err"></div>
                            </div>
                        </div>
                        
<!--<div class="col-sm-6">-->
<!--    <div class="form-group">-->
<!--        <label class="lsthousingphase">Status <span class="required">*</span></label>-->
<!--        <select class="form-control select2" id="Status">-->
<!--            <option value="">Select Any Option</option>-->
<!--            <option value="1">Complete</option>-->
<!--            <option value="0">INprogress</option>-->
<!--            <option value="0">Start</option>-->
<!--        </select>-->
<!--        <div class="errors" id="Status-err"></div>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="col-sm-6" id="startDateTimeContainer" >-->
<!--    <div class="form-group">-->
<!--        <label class="txtSDT">Started At Date&Time <span class="required">*</span></label>-->
<!--        <input type="datetime-local" class="form-control" id="started_at" value="">-->
<!--        <div class="errors" id="started_at-err"></div>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="col-sm-6" id="endDateTimeContainer" >-->
<!--    <div class="form-group">-->
<!--        <label class="txtSDT">End At Date&Time <span class="required">*</span></label>-->
<!--        <input type="datetime-local" class="form-control" id="end_at" value="">-->
<!--        <div class="errors" id="end_at-err"></div>-->
<!--    </div>-->
<!--</div>-->
<div class="col-sm-6">
    <div class="form-group">
        <label class="lsthousingstatus">Status <span class="required">*</span></label>
        <select class="form-control select2" id="lstStatus">
            <option value="">Select Any Option</option>
            <option value="1">Complete</option>
            <option selected value="2">INprogress</option>
            <!--<option value="0">Start</option>  Change value to '2' for Start -->
        </select>
        <div class="errors" id="Status-err"></div>
    </div>
</div>
<div class="col-sm-6" id="startDateTimeContainer" style="display: none;"> <!-- Initially hidden -->
    <div class="form-group">
        <label class="txtSDT">Started At Date & Time <span class="required">*</span></label>
        <input type="datetime-local" class="form-control" id="started_at" value="">
        <div class="errors" id="started_at-err"></div>
    </div>
</div>
<div class="col-sm-6" id="endDateTimeContainer" style="display: none;"> <!-- Initially hidden -->
    <div class="form-group">
        <label class="txtSDT">End At Date & Time <span class="required">*</span></label>
        <input type="datetime-local" class="form-control" id="end_at" value="">
        <div class="errors" id="end_at-err"></div>
    </div>
</div>

                
 <div class="col-sm-6">
 <div class="form-group text-center">
    <label class="txtSDT"> CImage <span class="required">*</span></label>
    <input type="file" class="form-control" id="CImage" accept="image/*">
    <img src="#" id="previewImage" style="margin: 10px; max-width: 200px; max-height: 200px; display: none;">
    <div class="errors" id="CImage-err">
        
    </div>
</div>
</div>
                       
                  <div class="col-sm-12">
<div class="form-group">
    <label for="Remarks">Remarks <span class="required">*</span></label>
    <textarea class="form-control" id="Remarks" rows="4" ></textarea>
                                    <div class="errors" id="Remarks-err"></div>

</div>
</div>
                        </div>
 <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a href="{{url('/')}}/master/PhaseUpdate/" class="btn btn-sm btn-outline-dark" id="btnCancel">Back</a>
                            @endif
                            
                            @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                <button class="btn btn-sm btn-outline-primary" id="btnSave">@if($isEdit==true) Update @else Save @endif</button>
                            @endif
                        </div>

                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
        const formValidation = () => {
    $('.errors').html('');
    let status = true;
    let lstAssignedOfficers= $('#lstAssignedOfficers').val();
    let lstBeneficiary = $('#lstBeneficiary').val();
    let lstControctor = $('#lstControctor').val();
    let lstHousingType = $('#lstHousingType').val();
    let remarks = $('#Remarks').val();
    let lstStatus=$('#lstStatus').val();
    // alert(lstStatus);
    let lstCImage=$('#CImage').val();
    let lststartdate=$('#started_at').val();
    // alert(lststartdate);
    let lstenddate=$('#end_at').val();
    let lstHousingphase=$("lstHousingphase").val();
    

    
    
    // alert(lstBeneficiary);
    if (lstAssignedOfficers== "") {
        $('#lstAssignedOfficers-err').html('The Th officer name is required.');
        status = false;
    }
    if (lstBeneficiary == "") {
        $('#lstBeneficiary-err').html('The Beneficiary name is required.');
        status = false;
    }
    if (lstControctor == "") {
        $('#lstControctor-err').html('The Contractor name is required.');
        status = false;
    }
     if (lstHousingphase == "") {
        $('#lstHousingphase-err').html('The House Phase name is required.');
        status = false;
    }
    if (lstHousingType == "") {
        $('#lstHousingType-err').html('The Housing Type name is required.');
        status = false;
    }
     if (remarks == "") {
        $('#Remarks-err').html('The Remarks is required.');
        status = false;
    }
    if (lstStatus == "") {
        $('#Status-err').html('Status is required.');
        status = false;
    }
    if (lstCImage == "") {
        $('#CImage-err').html('CImage is required.');
        status = false;
    }
    if(lstStatus == 0){
        if(lststartdate == ''){
            $('#started_at-err').html('Start  Date is required.');
             status = false;
                
        }
    }else if(lstStatus == 1){
        if(lstenddate == ''){
            $('#end_at-err').html('End Date is required.');
            status = false;
        }
         
    }
   
    return status;
}

        const getASP=async()=>{
            let editHPID=$('#lstAssignedOfficers').attr('data-HPID');
			$('#lstAssignedOfficers').select2('destroy');
            $('#lstAssignedOfficersoption').remove();
			$('#lstAssignedOfficers').append('<option value="">Select a Officers</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/master/AssignedOfficers/getoff",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
					for(item of response){
						let selected="";
                        console.log(item);
						if(item.UserID==editHPID){selected="selected";}
						$('#lstAssignedOfficers').append('<option '+selected+'  value="'+item.UserID+'">'+item.Name+'</option>');
					}
				}
			});
			$('#lstAssignedOfficers').select2();
        }
        const getBen=async()=>{
            let editHPID=$('#lstBeneficiary').attr('data-HPID');
			$('#lstBeneficiary').select2('destroy');
            $('#lstBeneficiary option').remove();
			$('#lstBeneficiary').append('<option value="">Select a Beneficiary</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/master/housingphase/getbenAll",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
					for(item of response){
						let selected="";
						if(item.BID==editHPID){selected="selected";}
						$('#lstBeneficiary').append('<option '+selected+'  value="'+item.BID+'">'+item.Name+'</option>');
					}
				}
			});
			$('#lstBeneficiary').select2();
        }
        const gethp=async()=>{
            let editHPID=$('#lstHousingType').attr('data-HPID');
			$('#lstHousingType').select2('destroy');
            $('#lstHousingType option').remove();
			$('#lstHousingType').append('<option value="">Select a HOUSING TYPE</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/master/housingphase/gethp",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
					for(item of response){
						let selected="";
						if(item.HID==editHPID){selected="selected";}
						$('#lstHousingType').append('<option '+selected+'  value="'+item.HID+'">'+item.htype+'</option>');
					}
				}
			});
			$('#lstHousingType').select2();
        }
        const getCONP=async()=>{
            let editHPID=$('#lstControctor').attr('data-HPID');
        
			$('#lstControctor').select2('destroy');
            $('#lstControctor option').remove();
			$('#lstControctor').append('<option value="">Select a Contractor</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/master/housingphase/getCONP",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
					for(item of response){
						let selected="";
						if(item.ConID==editHPID){selected="selected";}
						$('#lstControctor').append('<option '+selected+'  value="'+item.ConID+'">'+item.Name+'</option>');
					}
				}
			});
			$('#lstControctor').select2();
        }
        const gethpp = async () => {
            let editHTID = $('#lstHousingphase').attr('data-HTID');
            let editHPIDValue = $('#lstHousingphase').attr('data-HPID');
            // alert(editHPIDValue);
            $('#lstHousingphase').select2('destroy');
            $('#lstHousingphase option').remove();
            $('#lstHousingphase').append('<option value="">Select a HOUSING TYPE</option>'); // Add "Select" option
            $.ajax({
                type: "post",
                url: "{{url('/')}}/master/PhaseUpdate/housephasedata",
                data: { editHPID: editHTID }, // Pass editHPID as data
                headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
                error: function (e, x, settings, exception) { ajax_errors(e, x, settings, exception); },
                complete: function (e, x, settings, exception) { btnReset($('#btnSave')); ajaxindicatorstop(); },
                success: function (response) {
                    for (item of response) {
                        let selected = "";
                        if (item.HPID == editHPIDValue) { selected = "selected"; }
                        $('#lstHousingphase').append('<option ' + selected + '  value="' + item.HPID + '">' + item.PhaseName + '</option>');
                    }
                }
            });
            $('#lstHousingphase').select2();
        }
        $('#lstStatus').change(function() {
            var selectedStatus = $('#lstStatus').val();
            // alert(selectedStatus);
            // Show/hide div based on selected status
            if (selectedStatus == '1') { // 'Start' status
                $('#startDateTimeContainer').hide(); // Show startDateTimeContainer
                $('#endDateTimeContainer').show();   // Hide endDateTimeContainer
            } else if(selectedStatus == '0') { // 'Complete' or 'INprogress'
                $('#startDateTimeContainer').show(); // Hide startDateTimeContainer
                $('#endDateTimeContainer').hide();   // Hide endDateTimeContainer
            }else{
                $('#startDateTimeContainer').hide(); // Hide startDateTimeContainer
                $('#endDateTimeContainer').hide();   // Hide endDateTimeContainer
            }
        });
        $('#btnSave').click(function(){
            
            let status=formValidation();
            // alert(status);
            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this Phase Details !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-primary",
                    confirmButtonText: "Yes, @if($isEdit==true)Phase Update @else Save @endif it!",
                    closeOnConfirm: false
                },function(){
                    swal.close();
                    btnLoading($('#btnSave'));
                    let postUrl="{{url('/')}}/master/PhaseUpdate/create";
                    let formData=new FormData();
                   
                    formData.append('THID',$('#lstAssignedOfficers').val());
                    formData.append('BID',$('#lstBeneficiary').val());
                    formData.append('HTID',$('#lstHousingType').val());
                    formData.append('CID',$('#lstControctor').val());
                    formData.append('HPID',$('#lstHousingphase').val());
                    formData.append('Status',$('#lstStatus').val());
                    formData.append('Remarks',$('#Remarks').val());
                    formData.append('start_at',$('#started_at').val());
                    formData.append('end_at',$('#end_at').val());
                    // formData.append('CImage',$('#CImage').val());
                    if($('#CImage').val()!=""){
                        formData.append('CImage', $('#CImage')[0].files[0]);
                    }
                    
                    console.log(formData);
                    
                    @if($isEdit==true)
                    // alert();
                        // formData.append('BID',"{{$EditData[0]->BID}}");
                        postUrl="{{url('/')}}/master/PhaseUpdate/create";
                    @endif
                    $.ajax({
                        type:"post",
                        url:postUrl,
                        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                        data:formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total) * 100;
                                    percentComplete=parseFloat(percentComplete).toFixed(2);
                                    $('#divProcessText').html(percentComplete+'% Completed.<br> Please wait for until upload process complete.');
                                    //Do something with upload progress here
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function() {
                            ajaxindicatorstart("Please wait Upload Process on going.");

                            var percentVal = '0%';
                            setTimeout(() => {
                            $('#divProcessText').html(percentVal+' Completed.<br> Please wait for until upload process complete.');
                            }, 100);
                        },
                        error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                        complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
                        success:function(response){
                            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                            if(response.status==true){
                                swal({
                                    title: "SUCCESS",
                                    text: response.message,
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonClass: "btn-outline-primary",
                                    confirmButtonText: "Okay",
                                    closeOnConfirm: false
                                },function(){
                                    @if($isEdit==true)
                                        window.location.replace("{{url('/')}}/master/PhaseUpdate");
                                    @else
                                        window.location.reload();
                                    @endif
                                    
                                });
                                
                            }else{
                                toastr.error(response.message, "Failed", {
                                    positionClass: "toast-top-right",
                                    containerId: "toast-top-right",
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    progressBar: !0
                                })
                                if(response['errors']!=undefined){
                                    $('.errors').html('');
                                    $.each( response['errors'], function( KeyName, KeyValue ) {
                                        var key=KeyName;
                                        if(key=="HID"){$('#lstAssignedOfficers-err').html(KeyValue);}
                                        if(key=="BID"){$('#lstBeneficiary-err').html(KeyValue);}
                                        if(key=="CID"){$('#lstControctor-err').html(KeyValue);}
                                        if(key=="HTID"){$('#lstHousingType-err').html(KeyValue);}
                                        if(key=="HPID"){$('#lstHousingphase-err').html(KeyValue);}
                                        
                                        if(key=="Status"){$('#Status-err').html(KeyValue);}
                                        if(key=="Remarks"){$('#Remarks-err').html(KeyValue);}
                                        if(key=="start_at"){$('#started_at-err').html(KeyValue);}
                                        if(key=="end_at"){$('#end_at-err').html(KeyValue);}
                                        if(key=="CImage"){$('#CImage-err').html(KeyValue);}

                                    });
                                }
                            }
                        }
                    });
                });
            }
        });
        getASP();
        getBen();
        gethp();
        getCONP();
        gethpp();
        
    });
    
   function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImage').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        // If no file is selected, hide the preview image
        $('#previewImage').attr('src', '').hide();
    }
}

// Trigger preview function when a file is selected
$('#CImage').change(function() {
    previewImage(this);
});
    

</script>
@endsection