@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Operations</li>
					<li class="breadcrumb-item">Housing Work Status </li>
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
				<div class="card-header text-center"><h5 class="mt-10">Housing Work Status </h5></div>
				<div class="card-body " >
                    
                    <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstAssignedOfficers">  Th-Officers Name <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstAssignedOfficers" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->THID; }?>">
  
                                </select>
                                <div class="errors" id="lstAssignedOfficers-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstBeneficiary"> Beneficiary’s Name  <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstBeneficiary" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->BID; }?>">
  
                                </select>
                                <div class="errors" id="lstBeneficiary-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lsthousingtype"> Hosing Type  <span class="required"> * </span></label>
                                <select class="form-control select2" id="lsthousingtype" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->BID; }?>">
  
                                </select>
                                <div class="errors" id="lsthousingtype-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lsthousingphase"> Hosing Phase  <span class="required"> * </span></label>
                                <select class="form-control select2" id="lsthousingphase" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->BID; }?>">
  
                                </select>
                                <div class="errors" id="lsthousingphase-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="txtSDT"> Start Date&Time <span class="required"> * </span></label>
                                <input type="datetime-local" class="form-control" id="txtSDT" value="">
                                <div class="errors" id="txtSDT-err"></div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 d-none">
                            <div class="form-group">
                                <label class="txtAssignedOfficers"> Active Status</label>
                                <select class="form-control" id="lstActiveStatus">
                                    <option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
                                    <option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
                                </select>
                                <div class="errors" id="txtAssignedOfficers-err"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a href="{{url('/')}}/master/WorkStatus/" class="btn btn-sm btn-outline-dark" id="btnCancel">Back</a>
                            @endif
                            
                            @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                <button class="btn btn-sm btn-outline-primarys" id="btnSave">@if($isEdit==true) Update @else Save @endif</button>
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
    let txtSDT = $('#txtSDT').val();
    let lsthousingphase = $('#lsthousingphase').val();
    let lsthousingtype = $('#lsthousingtype').val();
    
    // alert(lstBeneficiary);
    if (lstAssignedOfficers== "") {
        $('#lstAssignedOfficers-err').html('The Housing Type name is required.');
        status = false;
    }
    if (lstBeneficiary == "") {
        $('#lstBeneficiary-err').html('The Beneficiary name is required.');
        status = false;
    }
    if (lsthousingtype == "") {
        $('#lsthousingtype-err').html('The Housing Type name is required.');
        status = false;
    }
    if (lsthousingphase == "") {
        $('#lsthousingphase-err').html('The Housing Phase name is required.');
        status = false;
    }
    if (txtSDT == "") {
        $('#txtSDT-err').html('The Start date and Time is required.');
        status = false;
    }
    
    return status;
}

        const gethp=async()=>{
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
				url:"{{url('/')}}/master/housingphase/getben",
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
            // getHT();
        }
        const getHT=async()=>{
            let editHTID=$('#lstBeneficiary').attr('data-HPID');
            let editHPID="";
			$('#lsthousingtype').select2('destroy');
            $('#lsthousingtype option').remove();
			$('#lsthousingtype').append('<option value="">Select a HOUSING TYPE</option>');
			$.ajax({
				type:"post",
				// url:"{{url('/')}}/master/WorkStatus/gethp/"+editHTID,
                url:"{{url('/')}}/master/WorkStatus/gethp/"+editHTID,
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                dataType:"json",
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
                   
                    console.log(response);
					for(item of response){
                        
                        console.log(item.HID);
                       
						let selected="";
						if(item.HID==editHPID){selected="selected";}
						$('#lsthousingtype').append('<option selected  value="'+item.HID+'">'+item.htype+'</option>');
					}
				}
			});
			$('#lsthousingtype').select2();
            
        }
        const Gethousephase=async()=>{
            let editHTID=$('#lsthousingtype').val();
            let editid= "HT2024-000004";

            // alert(editHTID);
            let editHPID="";
			$('#lsthousingphase').select2('destroy');
            $('#lsthousingphase option').remove();
			$('#lsthousingphase').append('<option value="">Select a HOUSING Phase</option>');
			$.ajax({
				type:"post",
                url:"{{url('/')}}/master/WorkStatus/Gethousephase/"+editid,
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                dataType:"json",
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
                    console.log(response);
					for(item of response){
                        console.log(item.HID);
						let selected="";
						if(item.HID==editHPID){selected="selected";}
						$('#lsthousingphase').append('<option '+selected+'  value="'+item.HPID+'">'+item.PhaseName+'</option>');
					}
				}
			});
			$('#lsthousingphase').select2();
            
        }
        $('#btnSave').click(function(){
            let status=formValidation();
            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this Start Work !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-primary",
                    confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
                    closeOnConfirm: false
                },function(){
                    swal.close();
                    btnLoading($('#btnSave'));
                    let postUrl="{{url('/')}}/master/WorkStatus/create";
                    let formData=new FormData();
                    
                    formData.append('THID',$('#lstAssignedOfficers').val());
                    formData.append('BID',$('#lstBeneficiary').val());
                    formData.append('HTID',$('#lsthousingtype').val());
                    formData.append('HPID',$('#lsthousingphase').val());
                    formData.append('Sdatetime',$('#txtSDT').val());
                    formData.append('ActiveStatus',$('#lstActiveStatus').val());
                    
                    @if($isEdit==true)
                        formData.append('ASOFFID',"{{$EditData[0]->ASOFFID}}");
                       postUrl="{{url('/')}}/master/WorkStatus/create";
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
                                        window.location.replace("{{url('/')}}/master/WorkStatus");
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
                                    });
                                }
                            }
                        }
                    });
                });
            }
        });
        gethp();
        getBen();
        getHT();
        Gethousephase();
        
    });
</script>
@endsection