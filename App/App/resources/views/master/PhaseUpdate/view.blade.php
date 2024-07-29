@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Operations</li>
					<li class="breadcrumb-item">Phase Update</li>
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
							<div class="form-row  d-flex justify-content-center">
								<div class="col-md-4">	</div>
								<div class="col-md-4 my-2">
									<h5>Phase Update</h5>
								</div>
								<div class="col-md-4 my-2 text-right text-md-right">
									@if($crud['restore']==1)
										<!--<a href="{{ url('/') }}/master/AssignedOfficers/trash-view" class="btn  btn-outline-light btn-sm m-r-10" type="button" > Trash view </a>-->
									@endif
									@if($crud['add']==1)
										<!--<a href="{{ url('/') }}/master/AssignedOfficers/Import" class="btn  btn-outline-primary btn-air-success btn-sm" type="button" >Import</a> <!-- full-right -->
									@endif
									@if($crud['add']==1)
										<!--<a href="{{ url('/') }}/master/AssignedOfficers/create" class="btn  btn-outline-primary btn-air-success btn-sm" type="button" >Create</a> <!-- full-right -->
									@endif
									
								</div>
							</div>
						</div>
						<div class="card-body " >
                            <table class="table" id="tblAssignedOfficers">
                                <thead>
                                    <tr>
                                        
                                        <th class="text-center">Technical Assistant</th>
                                        <th class="text-center">Beneficiary </th>
                                        <th class="text-center">Contractor</th>
                                        <th class="text-center">HouseType </th>
                                        <th class="text-center">HousePhase</th>
										<th class="text-center">ActiveStatus</th>
                                        <th class="text-center">action</th>
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
        let RootUrl=$('#txtRootUrl').val();
        
        const LoadTable = async () => {
            $('#tblAssignedOfficers').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "ajax": {
                    "url": RootUrl + "master/PhaseUpdate/data?_token=" + $('meta[name=_token]').attr('content'),
                    "headers": { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
                    "type": "POST",
                    "dataSrc": function (json) {
                        var uniqueData = [];
                        var uniqueRows = {}; 
                        
                        $.each(json.data, function(index, row) {
                          var rowData = row.join();
                          
                          if(!uniqueRows[rowData]) {
                            uniqueRows[rowData] = true;
                            uniqueData.push(row);
                          }
                        });
                        
                        return uniqueData;
                    }
                },
                deferRender: true,
                responsive: true,
                select: true, // Enable checkbox selectors
                dom: 'Bfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [[10, 25, 50, 100, 250, 500, -1], [10, 25, 50, 100, 250, 500, "All"]],
                buttons: [
        					'pageLength' 
        					@if($crud['excel']==1) ,{extend: 'excel',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif 
        					@if($crud['copy']==1) ,{extend: 'copy',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
        					@if($crud['csv']==1) ,{extend: 'csv',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
        					@if($crud['print']==1) ,{extend: 'print',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
        					@if($crud['pdf']==1) ,{extend: 'pdf',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
        				],
                columnDefs: [
                    { "className": "dt-center", "targets": 2 },
                    { "className": "dt-center", "targets": 3 }
                ]
            });
        }



		$(document).on('click','.btnEdit',function(){
			window.location.replace("{{url('/')}}/master/PhaseUpdate/edit/"+ $(this).attr('data-id'));
		});
		
	
        LoadTable();
    });
</script>
@endsection

<!-- https://www.aspsnippets.com/questions/142715/Make-HTML-Table-cell-editable-using-jQuery-in-ASPNet-MVC/ -->