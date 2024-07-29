@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Master</li>
					<li class="breadcrumb-item">Assignment</li>
					<li class="breadcrumb-item">Import View</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
	<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header ">
							<!-- <h5 class="text-center ">Import</h5> -->
							<a href="{{url('/')}}/uploads/format-excel/FormateExcelAssign.xlsx" class="btn btn-sm btn-outline-info text-right" id="btnFormatxl ">Formate Excel</a>
							<button  class="btn btn-sm btn-outline-warning text-right d-none" id="btnMapping">Mapping</button>
								
						</div>
						<div class="card-body">
								<div class="row mb-20  d-flex justify-content-center">
									<div class="col-sm-6">
										<input type="file" class="dropify" id="FileUpload" data-default-file=""  data-allowed-file-extensions='["xlsx","xls","csv"]' >
										<span class="errors" id="FileUpload-err"></span>
									</div>
									
								</div>
								<div class="row Mappingdiv" >
												<div class="col-md-4">
													<div class="form-group">
														<label for="FirstName">First Name <span class="required">*</span></label>	
														<input type="text" id="FirstName" class="form-control alap" maxlength="1" size="1" placeholder="FirstName" oninput="this.value = this.value.toUpperCase()" value="A">
														<span class="errors" id="FirstName-err"></span>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="LastName">Last Name <span class="required">*</span></label>	
														<input type="text" id="LastName" class="form-control alap" maxlength="1" size="1" placeholder="LastName" oninput="this.value = this.value.toUpperCase()" value="B">
														<span class="errors" id="LastName-err"></span>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="DOB">DOB <span class="required">*</span></label>	
														<input type="text" id="DOB" maxlength="1" size="1" class="form-control alap" placeholder="DOB" oninput="this.value = this.value.toUpperCase()" value="C">
														<span class="errors" id="DOB-err"></span>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="Gender">Gender <span class="required">*</span></label>	
														<input type="text" id="Gender" maxlength="1" size="1" class="form-control alap" placeholder="Gender" oninput="this.value = this.value.toUpperCase()" value="D">
														<span class="errors" id="Gender-err"></span>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="Email">Email <span class="required">*</span></label>	
														<input type="text" id="Email" maxlength="1" size="1" class="form-control alap" placeholder="Email" oninput="this.value = this.value.toUpperCase()" value="E">
														<span class="errors" id="Email-err"></span>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="PhoneNumber">PhoneNumber <span class="required">*</span></label>	
														<input type="text" id="PhoneNumber" maxlength="1" size="1" class="form-control alap" placeholder="PhoneNumber" oninput="this.value = this.value.toUpperCase()" value="F">
														<span class="errors" id="PhoneNumber-err"></span>
													</div>
												</div>						
											</div>
										<div class="row">
                                            @if($isEdit==false)
                                                <div class="col-sm-12 text-right ">
													<!--  -->
                                                    @if($crud['view']==true)
													
                                                    <a href="{{url('/')}}/import/" class="d-none btn btn-sm btn-outline-dark text-right" id="btnCancel">Back</a>
													
                                                    @endif
                                                    
                                                    @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                                        <button class="btn btn-sm btn-outline-success" id="btnSubmit">@if($isEdit==true) Update @else Import @endif</button>
                                                        @endif
                                                        
                                                    
                                                </div>
                                            @endif
                                        </div>
										
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.0/dist/xlsx.full.min.js"></script>

<script>
	
  var ExcelToJSON = function() {
    this.parseExcel = function(file) {
        var reader = new FileReader();

        reader.onload = function(e) {
        var data = e.target.result;
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
        workbook.SheetNames.forEach(function(sheetName) {
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            var productList = JSON.parse(JSON.stringify(XL_row_object));

            var rows = $('#tblItems tbody');
            // console.log(productList)
            for (i = 0; i < productList.length; i++) {
            var columns = Object.values(productList[i])
            rows.append(`
                            <tr>
                                <td>${columns[0]}</td>
                                <td>${columns[1]}</td>
                                <td>${columns[2]}</td>
                                <td>${columns[3]}</td>
                                <td>${columns[4]}</td>
                                <td>${columns[5]}</td>
                                <td>${columns[6]}</td>
                                <td>${columns[7]}</td>
                                <td>${columns[8]}</td>
                                <td>${columns[9]}</td>
                                <td>${columns[10]}</td>
                                <td>${columns[11]}</td>
                                <td>${columns[12]}</td>
                                <td>${columns[12]}</td>
                                <td>${columns[13]}</td>
                                <td>${columns[14]}</td>
                                <td>${columns[15]}</td>
                                <td>${columns[16]}</td>
                                <td>${columns[17]}</td>
                                <td>${columns[18]}</td>
                            </tr>
                        `);
            }

        })
        };
        reader.onerror = function(ex) {
        console.log(ex);
        };

        reader.readAsBinaryString(file);
    };
  };

    function handleFileSelect(evt) {
		var files = evt.target.files; // FileList object
		var xl2json = new ExcelToJSON();
		xl2json.parseExcel(files[0]);
    }

    document.getElementById('FileUpload').addEventListener('change', handleFileSelect, false);

    $("#FileUpload").change(function(){
        $("#PreviewtBody").empty();
    })
	
	const formValidation=()=>{
		
		$('.errors').html('');
		let status=true;
			if($('#FileUpload').val()==""){
				$('#FileUpload-err').html("Please select import file");status = false;
			}

			// if($('#FirstName').val()==""){
			// 	$('#FirstName-err').html("Please Enter correct order alphabet of FirstName ");status = false;
			// }else if(isNaN($('#FirstName').val())){
			// 	$('#FirstName-err').html("Please Enter correct order alphabet of FirstName");status = false;
			// }
			// if($('#LastName').val()==""){
			// 	$('#LastName-err').html("Please Enter correct order alphabet of LastName ");status = false;
			// }else if(isNaN($('#LastName').val())){
			// 	$('#LastName-err').html("Please Enter correct order alphabet of LastName");status = false;
			// }
			// if($('#DOB').val()==""){
			// 	$('#DOB-err').html("Please Enter correct order alphabet of DOB ");status = false;
			// }else if(isNaN($('#DOB').val())){
			// 	$('#DOB-err').html("Please Enter correct order alphabet of DOB");status = false;
			// }
			// if($('#Gender').val()==""){
			// 	$('#Gender-err').html("Please Enter correct order alphabet of Gender ");status = false;
			// }else if(isNaN($('#Gender').val())){
			// 	$('#Gender-err').html("Please Enter correct order alphabet of Gender");status = false;
			// }
			// if($('#Email').val()==""){
			// 	$('#Email-err').html("Please Enter correct order alphabet of Email ");status = false;
			// }else if(isNaN($('#Email').val())){
			// 	$('#Email-err').html("Please Enter correct order alphabet of Email");status = false;
			// }
			// if($('#PhoneNumber').val()==""){
			// 	$('#PhoneNumber-err').html("Please Enter correct order alphabet of PhoneNumber ");status = false;
			// }else if(isNaN($('#PhoneNumber').val())){
			// 	$('#PhoneNumber-err').html("Please Enter correct order alphabet of PhoneNumber");status = false;
			// }
		
		return status;
	}
	$('.Mappingdiv').hide();
	$('#btnSubmit').click(function(){

		console.log($('#FileUpload')[0].files[0]);

			
			let status=formValidation();
			
			console.log(status);
			if(status){
			swal({
				title: "Are you sure?",
				text: "You want @if($isEdit==true)Update @else Save @endif this ImportFile!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-outline-primary",
				confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
				closeOnConfirm: false
			},function(){
				swal.close();
				btnLoading($('#btnSubmit'));
				let postUrl="{{url('/')}}/master/AssignedOfficers/import/ASSGsave";
				let formData=new FormData();
				@if($isEdit==false)
				if($('#FileUpload').val()!=""){
					formData.append('importfile', $('#FileUpload')[0].files[0]);
					formData.append('FName',$('#FirstName').val());
					formData.append('LastName', $('#LastName').val());
					formData.append('DOB', $('#DOB').val());
					formData.append('Gender', $('#Gender').val());
					formData.append('Email', $('#Email').val());
					formData.append('PhoneNumber', $('#PhoneNumber').val());
				}
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
					complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));ajaxindicatorstop();},
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
								window.location.reload();
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
									
									if(key=="importfile"){$('#FileUpload-err').html(KeyValue);}
								});
							}
						}
					}
				});
			});
			}
	});
	$('#btnMapping').click(function(){
		
		$('.Mappingdiv').toggle();
		
	});
	
</script>
@endsection