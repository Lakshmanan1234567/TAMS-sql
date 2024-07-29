@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Reports</li>
					<li class="breadcrumb-item">Beneficiary Reports</li>
					<!--<li class="breadcrumb-item">Create Beneficiary</li>-->
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header text-center">
							<div class="form-row d-flex justify-content-center align-items-center">
								<!-- <div class="col-md-4">	</div> -->
								<div class="col-md-4 my-2">
									<h5>Beneficiary </h5>
								</div>
								<!-- <div class="col-md-4 my-2 text-right text-md-right">
								<h5 class="d-none">User Count - ({{$UserCount}})</h5>
									
									@if($crud['add']==1)
										<a href="{{ url('/') }}/reports/beneficiaryreport/create" class="btn  btn-outline-primary btn-air-success btn-sm" type="button" >Create</a> 
									@endif
									@if($crud['view']==1)
										<a href="{{ url('/') }}/reports/beneficiaryreport/Import" class=" btn  btn-outline-success btn-air-success btn-sm" type="button" >Import</a> 
									@endif
									@if($crud['restore']==1)
										<a href="{{ url('/') }}/reports/beneficiaryreport/trash-view" class="btn  btn-outline-light btn-air-success btn-sm m-r-10" type="button" > Trash view </a>
									@endif
								</div> -->
							</div>
						</div>
						<div class="card-body " >
					
							<div id="techofficer_filter" class="form-row d-flex justify-content-center m-5 mb-2">

								
								<div class="col-sm-2 justify-content-Center d-none">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Work Status</label>
										<select id="lstSstatus" class="form-control multiselect"  multiple>
											<option value="1">Start Work</option>
											<option value="2">Active Work</option>
											<option value="3">Completed Work</option>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;
								<div class="col-sm-3 justify-content-Center">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Division</label>
										<select id="lstDivision" class="form-control multiselect"  multiple>
											<?php 
												foreach ($Division as $key => $Division) {
												?>
												<option value="<?php echo $Division->DName; ?>"><?php echo $Division->DivisionName; ?></option>
												<?php
												}
												?>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;
								<div class="col-sm-2 justify-content-Center">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">District</label>
										<select id="lstDistrict" class="form-control multiselect"  multiple>
											<?php 
												foreach ($District as $key => $district) {
												?>
												<option value="<?php echo $district->DName; ?>"><?php echo $district->DName; ?></option>
												<?php
												}
												?>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;
								<div class="col-sm-3 justify-content-Center ">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Community</label>
										
										<select id="lstCommunity" class="form-control multiselect"  multiple>
										<option value="SC">SC</option>
											<option value="ST">ST</option>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;
								
								<div class="col-sm-2 justify-content-Center d-none">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Contractor</label>
										<select id="lstContractor" class="form-control multiselect"  multiple>
											<?php 
												foreach ($Contractor as $key => $contractor) {
												?>
												<option value="<?php echo $contractor->ConID; ?>"><?php echo $contractor->Name; ?></option>
												<?php
												}
												?>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;
								<div class="col-sm-2 justify-content-Center">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Active Status</label>
										<select id="lstStatus" class="form-control multiselect"  multiple>
											<option value="1">Active</option>
											<option value="0">Inactive</option>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;

							</div>
							
                            <table class="table" id="tblBeneficiary">
                                <thead>
                                    <tr>
                                        <th class="text-center">Division</th>
                                        <th class="text-center">District</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Father / Husband Name</th>
										<th class="text-center">Mother Name</th>
										<th class="text-center">Occupation</th>
										<th class="text-center">Email</th>
										<th class="text-center">Phone</th>
										<th class="text-center">DOB</th>
										<th class="text-center">Gender</th>
										<th class="text-center">Religion</th>
										<th class="text-center">Community</th>
										<th class="text-center">Marital Status</th>
										<th class="text-center">Disability</th>
										<th class="text-center">Address</th>
										<th class="text-center">Taluk</th>
										<th class="text-center">Block</th>
										<th class="text-center">Panchayat</th>
										<th class="text-center">MLA</th>
										<th class="text-center">MP</th>
										<th class="text-center">Aadhaar</th>
										<th class="text-center">Family Card</th>
										<th class="text-center">MGNREGS Job card Number</th>
										<th class="text-center">Occupation2</th>
										<th class="text-center">Annual Income</th>
										<th class="text-center">own a house site?</th>
										<th class="text-center">Land Survey No</th>
										<th class="text-center">Land Sub Division No</th>
										<th class="text-center">own agriculture land?</th>
										<th class="text-center">Bank IFSC Code</th>
										<th class="text-center">Branch Name</th>
										<th class="text-center">Bank name</th>
										<th class="text-center">Bank Account Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
		$('.multiselect').select2();
        let RootUrl=$('#txtRootUrl').val();
        const LoadTable=async()=>{
			@if($crud['view']==1)
			$('#tblBeneficiary').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": RootUrl+"reports/beneficiaryreport/data?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
				buttons: [
					'pageLength' 
					@if($crud['excel']==1) ,{extend: 'excel',footer: true,title: 'Beneficiary',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif 
					@if($crud['copy']==1) ,{extend: 'copy',footer: true,title: 'Beneficiary',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['csv']==1) ,{extend: 'csv',footer: true,title: 'Beneficiary',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['print']==1) ,{extend: 'print',footer: true,title: 'Beneficiary',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['pdf']==1) ,{extend: 'pdf',footer: true,title: 'Beneficiary',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
				],
				columnDefs: [
					{"className": "dt-center", "targets":2},
					{"className": "dt-center", "targets":3}
				]
			});
			@endif
        }
		$(document).on('click','.btnEdit',function(){
			window.location.replace("{{url('/')}}/reports/beneficiaryreport/edit/"+ $(this).attr('data-id'));
		});
		$(document).on('click', '.btnPassword', function (e) {
			var id = $(this).attr('data-id');
			$.ajax({
				type: "post",
				url: "{{url('/')}}/reports/beneficiaryreport/get/password",
				headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
				data: { uid: id },
				dataType: "json",
				async: false,
				error: function (e, x, settings, exception) { ajax_errors(e, x, settings, exception); },
				complete: function (e, x, settings, exception) { ajax_errors(e, x, settings, exception); },
				success: function (response) {
					$('#pwd_'+response.id).html(response.pwd);
				}
			});
		});
		$(document).on('click','.btnDelete',function(){
			let ID=$(this).attr('data-id');
			swal({
                title: "Are you sure?",
                text: "You want Delete this Category!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-danger",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: false
            },
            function(){swal.close();
            	$.ajax({
            		type:"get",
                    url:"{{url('/')}}/reports/beneficiaryreport/delete/"+ID,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                    		$('#tblBeneficiary').DataTable().ajax.reload();
                    		toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                    	}else{
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
        LoadTable();
    });
		$('#lstDivision').multiselect({
			enableFiltering: true,
			maxHeight: 250,
			buttonClass: 'btn btn-link',
			buttonWidth: '150px', // Adjust the width as needed
			onChange: function(element, checked) {
				var Vselected = '';
				var citys = $('#lstDivision option:selected');
				$(citys).each(function(index, brand) {
					Vselected += (Vselected == "") ? " " : "','";
					Vselected += $(this).val(); 
					console.log(Vselected);
				});
				LoadTable();
			}
		});
		$('#lstDistrict').multiselect({
			enableFiltering: true,
			maxHeight: 250,
			buttonClass: 'btn btn-link',
			buttonWidth: '150px', // Adjust the width as needed
			onChange: function(element, checked) {
				var Vselected = '';
				var citys = $('#lstDistrict option:selected');
				$(citys).each(function(index, brand) {
					Vselected += (Vselected == "") ? " " : "','";
					Vselected += $(this).val(); 
					console.log(Vselected);
				});
				LoadTable();
			}
		});

    
//         $('#lstSstatus').on('change',function(){
// 			ReportLoad();
// 		});
// 		$('#lstDistrict').on('change',function(){
// 			ReportLoad();
// 		});
// 		$('#lstTechasst').on('change',function(){
// 			ReportLoad();
// 		});
// 		$('#lstContractor').on('change',function(){
// 			ReportLoad();
// 		});
// 		$('#lstDivision').on('change',function(){
// 			ReportLoad();
// 		});
</script>
@endsection