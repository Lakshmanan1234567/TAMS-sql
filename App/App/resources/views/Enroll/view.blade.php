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
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Enroll</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Enroll</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                <div class="col-md-12 my-2 text-right text-md-right">

									@if($crud['add']==1)
										<a href="{{ url('/') }}/Enroll/create" class="btn  btn-outline-primary btn-air-success btn-sm" type="button" >New Enroll</a> <!-- full-right -->
									@endif
								</div>
                    <div class="card">

                    <div class="card-body">
                                <div class="tab-pane fade show active" id="institutes" role="tabpanel" aria-labelledby="institutes-tab">
                                    <table class="table" id="tblEnroll">
                                        <thead>
                                            <tr>
                                                <th class="">Enroll ID</th>
                                                <th class="">District</th>
                                                <th class="">Institute</th>
                                                <th class="">Timings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>

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
			$('#tblEnroll').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ordering": false,
                "ajax": {"url": RootUrl+"Enroll/data?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
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
				],
                initComplete: function() {
                        var table = this.api();

                        // Loop through each column
                        table.columns().every(function() {
                            var column = this;
                            var headerCell = $(column.header());

                            // Create a select element and append it to the header
                            var select = $(
                                    '<select><option value=""></option></select>')
                                .appendTo(headerCell)
                                .on('change', function() {
                                    // Get the selected value
                                    var val = $(this).val();
                                    console.log("Selected Value:",
                                    val); // Debugging: Log the selected value

                                    // Perform the search without regex
                                    column
                                        .search(val ? val : '', false, false)
                                        .draw();
                                });

                            // Populate the select element with unique values from the column
                            column.data().unique().sort().each(function(d, j) {
                                console.log("Column Value:",
                                d); // Debugging: Log each value in the column
                                select.append('<option value="' + d + '">' + d +
                                    '</option>');
                            });
                        });
                    },
			});
			@endif
        }
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
		$(document).on('click', '.btnPassword', function (e) {
			var id = $(this).attr('data-id');
			$.ajax({
				type: "post",
				url: "{{url('/')}}/Course/get/password",
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
        LoadTable();
    });
</script>
@endsection
