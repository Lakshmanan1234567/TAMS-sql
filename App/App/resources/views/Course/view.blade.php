@extends('layouts.layout')
@section('content')
    <style>
        /* .nav-tabs .nav-link {
                                                        padding: 0.75rem 13.15rem;
                                                    } */
        .nav-tabs .nav-link.active {
            background-color: #b2c9e1;
            color: #09497a;
        }

        @media (max-width: 768px) {
            .nav-tabs {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-tabs .nav-link {
                margin: 0.5rem;
                padding: 0.75rem 1.5rem;
            }
        }
    </style>
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Course</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Course</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                <div class="col-md-12 my-2 text-right text-md-right">
									
									@if($crud['add']==1)
										<a href="{{ url('/') }}/Course/create" class="btn  btn-outline-primary btn-air-success btn-sm" type="button" >Create</a> <!-- full-right -->
									@endif
									@if($crud['view']==1)
										<a href="{{ url('/') }}/Course/Import" class=" btn  btn-outline-success btn-air-success btn-sm" type="button" >Import</a> <!-- full-right -->
									@endif
								</div>
                    <div class="card">
                
                    <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="institutes-tab" data-toggle="tab" href="#institutes" role="tab" aria-controls="institutes" aria-selected="true">Approved</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab" aria-controls="other" aria-selected="false">Waiting</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="institutes" role="tabpanel" aria-labelledby="institutes-tab">
                                    <table class="table" id="tblInstitutesApproved">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Course</th>
                                                <th class="text-center">Subject	</th>
                                                <th class="text-center">Slot</th>
                                                <th class="text-center">Duration <b>(HOURS)</b></th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                                    <table class="tables" id="tblInstitutesWaiting">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Course</th>
                                                <th class="text-center">Subject</th>
                                                <th class="text-center">Slot</th>
                                                <th class="text-center">Duration <b>(HOURS)</b></th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>

            <style>
                /* .nav-tabs .nav-link {
                                                                margin-bottom: -1px;
                                                                border: 1px solid transparent;
                                                                border-top-left-radius: .25rem;
                                                                border-top-right-radius: .25rem;
                                                            }
                                                            .nav-tabs .nav-link.active {
                                                                color: #495057;
                                                                background-color: #fff;
                                                                border-color: #dee2e6 #dee2e6 #fff;
                                                            }
                                                            .nav-tabs {
                                                                border-bottom: 1px solid #dee2e6;
                                                            } */
            </style>

            <!-- end row-->

        </div> <!-- container -->

    </div>

    <!-- Include Bootstrap's JavaScript dependencies -->

    <!-- Include jQuery -->
    
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        let RootUrl=$('#txtRootUrl').val();
        const LoadTable=async()=>{
			@if($crud['view']==1)
			$('#tblInstitutesApproved').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": RootUrl+"Course/Approved/data?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
				buttons: [
					'pageLength'
					@if($crud['excel']==1) ,{extend: 'excel',footer: true,title: 'Course',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['copy']==1) ,{extend: 'copy',footer: true,title: 'Course',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['csv']==1) ,{extend: 'csv',footer: true,title: 'Course',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['print']==1) ,{extend: 'print',footer: true,title: 'Course',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['pdf']==1) ,{extend: 'pdf',footer: true,title: 'Course',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
				],
				columnDefs: [
					{"className": "dt-center", "targets":2},
					{"className": "dt-center", "targets":3}
				]
			});
			@endif
        }
		// $(document).on('click', '.btnEdit', function(){
        //     let ID = $(this).attr('data-id');
        //     swal({
        //         title: "Are you sure?",
        //         text: "You want to edit this Course!",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonClass: "btn-outline-warning",
        //         confirmButtonText: "Yes, edit it!",
        //         closeOnConfirm: false
        //     },
        //     function(){
        //         swal.close();
        //         $.ajax({
        //     		type:"post",
        //         url:"{{url('/')}}/Course/edit/"+ID,
        //         });
        //     });
        // });


        $(document).on('click','.btnEdit',function(){
			window.location.replace("{{url('/')}}/Course/edit/"+ $(this).attr('data-id'));
		});

        $(document).on('click','.btnDelete',function(){
			let ID=$(this).attr('data-id');
			swal({
                title: "Are you sure?",
                text: "You want Delete this Course!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-danger",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: false
            },
            function(){swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{url('/')}}/Course/delete/"+ID,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                    		$('#tblInstitutesApproved').DataTable().ajax.reload();
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
    $(document).ready(function(){
    let RootUrl = $('#txtRootUrl').val();

    const LoadTable = async () => {
        @if($crud['view'] == 1)
        $('#tblInstitutesWaiting').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "ajax": {
                "url": RootUrl + "Course/Waiting/data?_token=" + $('meta[name=_token]').attr('content'),
                "headers": { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
                "type": "POST"
            },
            "deferRender": true,
            "responsive": true,
            "dom": 'Bfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [
                [10, 25, 50, 100, 250, 500, -1],
                [10, 25, 50, 100, 250, 500, "All"]
            ],
            "buttons": [
                'pageLength',
                @if($crud['excel'] == 1)
                { extend: 'excel', footer: true, title: 'Course', "action": DataTableExportOption, exportOptions: { columns: "thead th:not(.noExport)" } },
                @endif
                @if($crud['copy'] == 1)
                { extend: 'copy', footer: true, title: 'Course', "action": DataTableExportOption, exportOptions: { columns: "thead th:not(.noExport)" } },
                @endif
                @if($crud['csv'] == 1)
                { extend: 'csv', footer: true, title: 'Course', "action": DataTableExportOption, exportOptions: { columns: "thead th:not(.noExport)" } },
                @endif
                @if($crud['print'] == 1)
                { extend: 'print', footer: true, title: 'Course', "action": DataTableExportOption, exportOptions: { columns: "thead th:not(.noExport)" } },
                @endif
                @if($crud['pdf'] == 1)
                { extend: 'pdf', footer: true, title: 'Course', "action": DataTableExportOption, exportOptions: { columns: "thead th:not(.noExport)" } },
                @endif
            ],
            "columnDefs": [
                { "className": "dt-center", "targets": [2, 3] }
            ]
        });
        @endif
    }
		$(document).on('click','.btnEdit',function(){


            
			window.location.replace("{{url('/')}}/Course/edit/"+ $(this).attr('data-id'));
		});
		       

    $(document).on('click', '.btn-status', function() {
        let ID = $(this).attr('data-id');
        let status = $(this).attr('data-status');
        let action = status == 1 ? 'Approve' : 'Reject';
        swal({
            title: "Are you sure?",
            text: "You want to " + action + " this Course?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-outline-warning",
            confirmButtonText: "Yes, " + action + " it!",
            closeOnConfirm: false
        },
        function() {
            swal.close();
            $.ajax({
                type: "post",
                url: RootUrl + "Course/statusUpdate",
                headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
                data: { Course_Id: ID, status: status },
                dataType: "json",
                success: function(response) {
                    swal.close();
                    if (response.status == true) {
                        $('#tblInstitutesWaiting').DataTable().ajax.reload();
                        $('#tblInstitutesApproved').DataTable().ajax.reload();
                        toastr.success(response.message, "Success", {
                            positionClass: "toast-top-right",
                            containerId: "toast-top-right",
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            progressBar: !0
                        })
                    } else {
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
