@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Master</li>
					<li class="breadcrumb-item">Housing Type</li>
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
				<div class="card-header text-center"><h5 class="mt-10">Housing Type</h5></div>
				<div class="card-body " >
                    <div class="row mb-20  d-flex justify-content-center">
                        <div class="col-sm-6">
                            <input type="file" class="dropify" id="txtCImage" data-default-file="<?php if($isEdit==true){if($EditData[0]->CImage!=""){ echo url('/')."/".$EditData[0]->CImage;}}?>"  data-allowed-file-extensions="jpeg jpg png gif" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="txthtype"> Housing Type  <span class="required"> * </span></label>
                                <input type="text" class="form-control" id="txthtype" value="<?php if($isEdit==true){ echo $EditData[0]->htype;} ?>">
                                <div class="errors" id="txthtype-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="txtHTD">Housing Type Details  <span class="required"> * </span></label>
                                <input type="text" class="form-control" id="txtHTD" value="<?php if($isEdit==true){ echo $EditData[0]->htypedetail;} ?>">
                                <div class="errors" id="txtHTD-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="txtTSFC">Total SF Construction  <span class="required"> * </span></label>
                                <input type="text" class="form-control" id="txtTSFC" value="<?php if($isEdit==true){ echo $EditData[0]->totalsfcon;} ?>">
                                <div class="errors" id="txtTSFC-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="txtCPSF">Cost Per SF(Rs.)  <span class="required"> * </span></label>
                                <input type="text" class="form-control" id="txtCPSF" value="<?php if($isEdit==true){ echo $EditData[0]->costpersf;} ?>">
                                <div class="errors" id="txtCPSF-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label class="txtC">Caste<span class="required"> * </span></label>
                                <select class="form-control" id="txtcaste">
                                    <option value="">Select Caste</option>
                                    <option value="SC" <?php if($isEdit && $EditData[0]->Caste == 'SC') echo 'selected'; ?>>SC</option>
                                    <option value="ST" <?php if($isEdit && $EditData[0]->Caste == 'ST') echo 'selected'; ?>>ST</option>
                                    <option value="Narikuravar" <?php if($isEdit && $EditData[0]->Caste == 'Narikuravar') echo 'selected'; ?>>Narikuravar</option>
                                </select>
                            <div class="errors" id="txtcaste-err"></div>
                          </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="txtTC">Total Cost  <span class="required"> * </span></label>
                                <input type="text" class="form-control" id="txtTC" value="<?php if($isEdit==true){ echo $EditData[0]->totalcost;} ?>">
                                <div class="errors" id="txtTC-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="txtCategory"> Active Status</label>
                                <select class="form-control" id="lstActiveStatus">
                                    <option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
                                    <option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
                                </select>
                                <div class="errors" id="txtCategory-err"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a href="{{url('/')}}/master/housingType/" class="btn btn-sm  btn-outline-light btn-sm" id="btnCancel">Back</a>
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
        const formValidation=()=>{
            $('.errors').html('');
            let status=true;
            let HType=$('#txthtype').val();
            let HTD=$('#txtHTD').val();
            let TSFC=$('#txtTSFC').val();
            let CPSF=$('#txtCPSF').val();
            let TC=$('#txtTC').val();
            let Caste=$('#txtcaste').val();
            if(HType==""){
                $('#txthtype-err').html('The Housing Type is required.');status=false;
            }else if(HType.length<2){
                $('#txthtype-err').html('Housing Type  must be greater than 2 characters');status=false;
            }else if(HType.length>100){
                $('#txthtype-err').html('Housing Type  may not be greater than 100 characters');status=false;
            }
            if(HTD==""){
                $('#txtHTD-err').html('The Housing Type Details is required.');status=false;
            }else if(HTD.length<2){
                $('#txtHTD-err').html('Housing Type Details  must be greater than 2 characters');status=false;
            }else if(HTD.length>100){
                $('#txtHTD-err').html('Housing Type Details  may not be greater than 100 characters');status=false;
            }
            if(TSFC==""){
                $('#txtTSFC-err').html('Total SF Construction  Details is required.');status=false;
            }else if(TSFC.length<2){
                $('#txtTSFC-err').html('Total SF Construction  must be greater than 2 characters');status=false;
            }else if(TSFC.length>100){
                $('#txtTSFC-err').html('Total SF Construction  may not be greater than 100 characters');status=false;
            }
            if(CPSF==""){
                $('#txtCPSF-err').html('Cost per SF Construction  Details is required.');status=false;
            }else if(CPSF.length<2){
                $('#txtCPSF-err').html('Cost per sF Construction  must be greater than 2 characters');status=false;
            }else if(CPSF.length>100){
                $('#txtCPSF-err').html('Cost per SF Construction  may not be greater than 100 characters');status=false;
            }
            if(TC==""){
                $('#txtTC-err').html('Total Cost Construction  Details is required.');status=false;
            }else if(TC.length<2){
                $('#txtTC-err').html('Total Cost Construction  must be greater than 2 characters');status=false;
            }else if(TC.length>100){
                $('#txtTC-err').html('Total Cost  may not be greater than 100 characters');status=false;
            }
            
            if(Caste==""){
                $('#txtcaste-err').html('The Caste is required.');status=false;
            }
            return status;
        }
        $('#btnSave').click(function(){
            let status=formValidation();
            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this Housing Type!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-primary",
                    confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
                    closeOnConfirm: false
                },function(){
                    swal.close();
                    btnLoading($('#btnSave'));
                    let postUrl="{{url('/')}}/master/housingType/create";
                    let formData=new FormData();
                    
                    formData.append('htype',$('#txthtype').val());
                    formData.append('htypedetail',$('#txtHTD').val());
                    formData.append('totalsfcon',$('#txtTSFC').val());
                    formData.append('costpersf',$('#txtCPSF').val());
                    formData.append('totalcost',$('#txtTC').val());
                    formData.append('Caste',$('#txtcaste').val());
                    formData.append('ActiveStatus',$('#lstActiveStatus').val());
                    if($('#txtCImage').val()!=""){
                        formData.append('CImage', $('#txtCImage')[0].files[0]);
                    }
                    @if($isEdit==true)
                        formData.append('HID',"{{$EditData[0]->HID}}");
                        postUrl="{{url('/')}}/master/housingType/edit/{{$EditData[0]->HID}}";
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
                                        window.location.replace("{{url('/')}}/master/housingType");
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
                                        
                                        if(key=="htype"){$('#txthtype-err').html(KeyValue);}
                                        if(key=="htypedetail"){$('#txtHTD-err').html(KeyValue);}
                                        if(key=="totalsfcon"){$('#txtTSFC-err').html(KeyValue);}
                                        if(key=="costpersf"){$('#txtCPSF-err').html(KeyValue);}
                                        if(key=="totalcost"){$('#txtTC-err').html(KeyValue);}
                                        if(key=="Caste"){$('#txtcaste-err').html(KeyValue);}
                                        if(key=="CImage"){$('#txtCImage-err').html(KeyValue);}
                                    });
                                }
                            }
                        }
                    });
                });
            }
        });
    });
</script>
@endsection