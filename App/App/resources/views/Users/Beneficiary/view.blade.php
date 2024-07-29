@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Master</li>
					<li class="breadcrumb-item">Beneficiary</li>
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
								<div class="col-md-4">	</div>
								<div class="col-md-4 my-2">
									<h5>Beneficiary </h5>
								</div>
								<div class="col-md-4 my-2 text-right text-md-right">
								<h5 class="d-none">User Count - ({{$UserCount}})</h5>
									
									@if($crud['add']==1)
										<a href="{{ url('/') }}/users-and-permissions/Beneficiary/create" class="btn  btn-outline-primary btn-air-success btn-sm" type="button" >Create</a> <!-- full-right -->
									@endif
									@if($crud['view']==1)
										<a href="{{ url('/') }}/users-and-permissions/Beneficiary/Import" class=" btn  btn-outline-success btn-air-success btn-sm" type="button" >Import</a> <!-- full-right -->
									@endif
									@if($crud['restore']==1)
										<a href="{{ url('/') }}/users-and-permissions/Beneficiary/trash-view" class="btn  btn-outline-light btn-air-success btn-sm m-r-10" type="button" > Trash view </a>
									@endif
								</div>
							</div>
						</div>
						<div class="card-body " >
                            <table class="table" id="tblBeneficiary">
                                <thead>
                                    <tr>
                                        <th class="text-center">BeneficiaryID</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">DOB</th>
                                        <th class="text-center">Gender</th>
										<th class="text-center">Address</th>
										<th class="text-center">CityName</th>
										<th class="text-center">Email</th>
										<th class="text-center">Phone</th>
										<!--<th class="text-center">Password</th>-->
										<th class="text-center">ActiveStatus</th>
										<th class="text-center">Action</th>
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
        const LoadTable=async()=>{
			@if($crud['view']==1)
			$('#tblBeneficiary').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": RootUrl+"users-and-permissions/Beneficiary/data?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
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
			window.location.replace("{{url('/')}}/users-and-permissions/Beneficiary/edit/"+ $(this).attr('data-id'));
		});
		$(document).on('click', '.btnPassword', function (e) {
			var id = $(this).attr('data-id');
			$.ajax({
				type: "post",
				url: "{{url('/')}}/users-and-permissions/Beneficiary/get/password",
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
                    url:"{{url('/')}}/users-and-permissions/Beneficiary/delete/"+ID,
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
</script>
@endsection