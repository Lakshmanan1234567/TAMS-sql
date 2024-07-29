@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Reports</li>
					<li class="breadcrumb-item">Tech Officers</li>
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
							<h5>Tech Officers</h5>
						</div>
						<div class="card-body">
							<div id="techofficer_filter" class="form-row d-flex justify-content-center m-5 mb-2">

								<div class="col-sm-2 justify-content-Center">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Active Status</label>
										<select id="lstStatus" class="form-control multiselect"  multiple>
											<option value="1">Active</option>
											<option value="0">Deactive</option>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;
								<div class="col-sm-2 justify-content-Center">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Work Status</label>
										<select id="lstSstatus" class="form-control multiselect"  multiple>
											<option value="1">Start Work</option>
											<option value="2">Active Work</option>
											<option value="3">Completed Work</option>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;
								<div class="col-sm-2 justify-content-Center">
									<div class="form-group text-center mh-60"
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
								<div class="col-sm-2 justify-content-Center">
									<div class="form-group text-center mh-60"
										<label style="margin-bottom:0px;">Technical Officer</label>
										<select id="lstTechasst" class="form-control multiselect"  multiple>
											<?php 
												foreach ($TechOfficer as $key => $techofficer) {
												?>
												<option value="<?php echo $techofficer->THID; ?>"><?php echo $techofficer->Name; ?></option>
												<?php
												}
												?>
										</select>
									</div>
								</div>&nbsp;&nbsp;&nbsp;&nbsp;
								<div class="col-sm-2 justify-content-Center">
									<div class="form-group text-center mh-60"
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
								</div>

							</div>
							<table class="table" id="tblTechOfficer">
								<thead>
									<th class="text-center">ThMeID</th>
									<th class="text-center">Name</th>
									<th class="text-center">Company Name</th>
									<th class="text-center">HID</th>
									<th class="text-center">Housing Type</th>
									<th class="text-center">Phase Name</th>
									<th class="text-center">Housing Type Details</th>
									<th class="text-center">Total SF Construction</th>
									<th class="text-center">Cost Per SF(Rs.)</th>
									<th class="text-center">Total Cost</th>
									<th class="text-center">Caste</th>
									<th class="text-center">District</th>
									<th class="text-center">Active Status</th>
									<th class="text-center">Work Status</th>
								</thead>
								<tbody></tbody>
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
			$("#tblTechOfficer").dataTable().fnDestroy();
			$('#tblTechOfficer').dataTable({
				"bProcessing": true,
				"bServerSide": false,
				"ajax": {"url": RootUrl+"reports/Assignmentreport/TableView/data?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
				buttons: [
					'pageLength'
					@if($crud['excel']==1) ,{extend: 'excel',footer: true,title: 'Tech Officerreport',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif 
					@if($crud['copy']==1) ,{extend: 'copy',footer: true,title: 'Tech Officerreport',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['csv']==1) ,{extend: 'csv',footer: true,title: 'Tech Officerreport',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['print']==1) ,{extend: 'print',footer: true,title: 'Tech Officerreport',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['pdf']==1) ,{extend: 'pdf',footer: true,title: 'Tech Officerreport',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
				],
				columnDefs: [
					{"className": "dt-center", "targets":2}
				]
			});
			@endif
		}

		const ReportLoad=async()=>{
			
		}
		$('#lstStatus').multiselect({
			enableFiltering:true,
			maxHeight:250,
			buttonClass: 'btn btn-link',
			onChange: function(element, checked) {
				State='';
				var States = $('#lstStatus option:selected');
				$(States).each(function(index, brand){
					let comma="','";
					State +=$(this).val()+comma;
				});
				LoadTable();
			}                    
        });
		$('#lstStatus').on('change',function(){
			ReportLoad();
		});
		$('#lstSstatus').on('change',function(){
			ReportLoad();
		});
		$('#lstDistrict').on('change',function(){
			ReportLoad();
		});
		$('#lstTechasst').on('change',function(){
			ReportLoad();
		});
		$('#lstContractor').on('change',function(){
			ReportLoad();
		});
		
		LoadTable();
	})
</script>

@endsection