@extends('layouts.layout')
@section('content')

<style>
    .custom-select-sm {
        width: 16vh; /* Adjust the width as needed */
    }
 .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            position: relative;
            max-width: 80%;
            max-height: 80%;
            background: #fff; /* Optional: Add a white background for modal content */
            overflow: hidden; /* Optional: Hide overflowing content */
            border-radius: 8px; /* Optional: Add border radius for modal */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Optional: Add shadow for modal */
        }
        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8); /* Optional: Semi-transparent background for close button */
            border-radius: 50%; /* Optional: Make the button round */
            z-index: 999;
        }
        .modal-close:hover {
            background-color: rgba(255, 255, 255, 0.9); /* Optional: Hover effect for close button */
        }
        .enlarged-img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: auto;
        }
         .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }
 @media print {
        table {
          page-break-inside: avoid;
        }
        tr {
          break-inside: avoid;
        }
        tbody {
          display: block;
        }
        tbody tr:nth-child(5n + 1) {
          page-break-before: always;
        }
      }
  #work_abstract th {
            font-weight: bold;
            text-align: center;
        }
</style>
&nbsp;
<div class="container-fluid">
	<div class="page-header" style="margin-left:-1.5vh">
	    <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 ">
                    <div class="col-sm-4 col-md-4 col-xl-4">
                        <h1 class="m-0">Reports</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-3 col-md-3 col-xl-3"></div>
                    <div class="col-sm-5 col-md-5 col-xl-5 d-flex align-items-center justify-content-end">
                        <ol class="breadcrumb ">
        					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title="Dashboard"><i class="f-16 fa fa-home"></i></a></li>
        					<li class="breadcrumb-item"><a href="{{ url('/') }}/reports/beneficiaryreport" data-original-title="" title="Reports">Reports</a></li>
        					<li class="breadcrumb-item"><a href="{{ url('/') }}/reports/DailyReport" title="Daily Reports">Daily Reports</a></li>
        					<li class="breadcrumb-item"><a href="{{ url('/') }}/reports/DailyReport/Upstarct" title="Map Tracker">Abstarct</a></li>
        				</ol>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->
	</div>
</div>
&nbsp;
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header text-center">
							<div class="form-row  d-flex justify-content-center">
								<div class="col-md-4 col-xl-4 col-sm-4"></div>
								<div class="col-xl-4 col-sm-4 col-md-4 my-2">
								    <h4><b>Abstarct</b></h4>
								</div>
								<div class="col-xl-4 col-sm-4 col-md-4 my-2 text-right text-md-right">
								    <div class="row">
								        <div class="col-md-5 col-xl-5 col-sm-5">
								            
								        </div>
								        <div class="col-md-7 col-xl-7 col-sm-7">
    										<a class="btn btn-sm  btn-outline-light btn-sm" id="btnCancel">Back</a>
								        </div>
								    </div>
								</div>
							</div>
						</div>
						
						<div class="card-body " style="margin-top:-0%">
							<div id="techofficer_filter" class="form-row d-flex justify-content-left m-1 mb-2">
								<div class="col-sm-3 justify-content-left m-1 ">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Division</label>
										<select id="lstDivision" class="form-control multiselect"  multiple>
											<?php 
												foreach ($Division as $key => $Division) {
												?>
												<option value="<?php echo $Division->DivisionName; ?>"><?php echo $Division->DivisionName; ?></option>
												<?php
												}
												?>
										</select>
									</div>
								</div>
								<div class="col-sm-2 justify-content-left m-1  ">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">From Date</label>
										<input type="date" id="FromDate" class="form-control date" placeholder="From Date" value="">
									</div>
								</div>
								<div class="col-sm-2 justify-content-left m-1 ">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">To Date</label>
										<input type="date" id="ToDate" class="form-control date" placeholder="To Date" value="">
										<input type="text" id="oldTAID" class="d-none" value="">
									</div>
								</div>
								<div class="col-sm-3 justify-content-end m-1 "></div>
								<div class="col-sm-2 justify-content-end m-1 ">
									<div class="form-group text-center mh-60">
                                        <button class="btn  btn-outline-success btn-air-success btn-sm m-2 p-2 mr-1" id="btnTrack">Go</button>
                                    </div>
                                </div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-12">
							    <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <div class="card">
                                            <div class="row">
                                                     <div class="col-sm-2 col-md-2 col-xl-2"><button class="btn  btn-outline-primary btn-air-success btn-sm m-1 p-2 mr-1" id="printTable">Print Table</button></div>
                                            <div class="col-sm-2 col-md-2 col-xl-2"><button class="btn  btn-outline-success btn-air-success btn-sm m-1 p-2 mr-1" id="exportBtn">Export Excel</button></div>
                                                
                                            </div>
                                       
                                            <div class="col-sm-10 col-md-10 col-xl-10"></div>
                                            <div class="card-body" id="divFilter">
                                                <div id="Division_Design" class="table-responsive">
                                                    <?php 
                                                        echo $Design;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
                            <div class="modal-overlay" id="modalOverlay">
                                <div class="modal-content">
                                    <span class="modal-close" id="modalClose">&times;</span>
                                    <img id="enlargedImage" class="enlarged-img" src="" alt="Enlarged Image">
                                </div>
                            </div>    
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var locations = [];
    $(document).ready(function(){
        let OldTAID=$('#oldTAID').val();
        let Vselected = ""; // Removed 'let' keyword to ensure global scope
        let district="";
        let community="";
        let housingstatus="";
        let MilStatus='';
        let FromDate='';
        let ToDate='';
        
        $('.multiselect').select2();
        
        let RootUrl=$('#txtRootUrl').val();
        
        $('#lstDivision').change(function() {
            // Initialize Vselected variable
            $('#lstDistrict').empty(); 
            // Clear previous selected values
            Vselected = '';
    
            // Get the selected options from #lstDivision
            var selectedOptions = $('#lstDivision option:selected');
    
            // Iterate through each selected option
            selectedOptions.each(function(index, option) {
                // Append the value of the selected option to Vselected
                Vselected += (Vselected === "") ? "" : ",";
                // Vselected += $(option).val();
                Vselected += "'" + $(option).val() + "'";
                //  Vselected += $(this).val(); 
                // Log the value of the selected option to the console
                console.log($(option).val());
            });
    
            // Call LoadTable function
        });
        
        function getCurrentFormattedDate() {
            var currentDate = new Date();
            function formatDate(date) {
                var d = new Date(date);
                var month = '' + (d.getMonth() + 1);
                var day = '' + d.getDate();
                var year = d.getFullYear();
        
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
        
                return [year, month, day].join('-');
            }
        
            return formatDate(currentDate);
        }
        
        $('#FromDate').change(function() {
            // Initialize Vselected variable
            
            // Clear previous selected values
            FromDate = '';
            FromDate=$('#FromDate').val();
            console.log("From :",FromDate);
            var currentFormattedDate = getCurrentFormattedDate();
            if(ToDate == ''){
                $('#ToDate').val(currentFormattedDate);
                ToDate=currentFormattedDate;
            }
            
            // Call LoadTable function
            if (FromDate != '' && ToDate != '') 
            {
            getremove();
            LoadTable();
            }
        });
        
        $('#ToDate').change(function() {
            // Initialize Vselected variable
            
            // Clear previous selected values
            ToDate = '';
            ToDate=$('#ToDate').val();
                        console.log("To :",ToDate);

            // Call LoadTable function
            if (FromDate != '' && ToDate != '') {
                getremove();
                LoadTable();
            }
        });
        
        $('#btnTrack').click(function(){
                $.ajax({
                    type: "post",
                    url: "{{url('/')}}/reports/DailyReport/Upstarct/FilterbyDivision",
                    data: { 'TAID': Vselected ,'Created_On':FromDate,'Deleted_On':ToDate},
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    error: function(e, x, settings, exception) {
                        ajax_errors(e, x, settings, exception);
                        toastr.error(e.responseJSON.error, "Failed", {
                            positionClass: "toast-top-right",
                            containerId: "toast-top-right",
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            progressBar: true
                        });
                    },
                    complete: function(e, x, settings, exception) {
                        btnReset($('#btnSave'));
                        ajaxindicatorstop();
                    },
                    success: function(response) {
                        
                        
if(response.Design !='')
{
                    $('#Division_Design').empty(); // Clear the div
                    $('#Division_Design').html(response.Design); // Populate the div with the table HTML from the response
                   //console.log(response.Design);
} 
else
{
window.location.reload()
}
                    }
                });
            
        });
        
        if(OldTAID!=''){
           Vselected=$('#oldTAID').val();
           $('#btnTrack').click();
        }
        
        $('#btnCancel').click(function(){
            window.location.replace(document.referrer);
        });
        
$('#printTable').click(function() {
    var tableContent = $('.table-responsive').html();
    var printWindow = window.open('', '_blank', 'width=1200,height=1000');

    // Write content to the print window
    printWindow.document.write('<html><head><title>Abstract</title>');
    // Add Bootstrap CSS for styling
    printWindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
    // Add custom CSS for table styling and printing
    printWindow.document.write('<style>');
    printWindow.document.write('@media print {');
    printWindow.document.write('  body { -webkit-print-color-adjust: exact; }');
    printWindow.document.write('  .table-responsive { overflow: visible !important; }');
    printWindow.document.write('  table { border-collapse: collapse; width: 100%; }');
    printWindow.document.write('  th, td { border: 1px solid black; padding: 1px; text-align: center; white-space: nowrap; }');
    printWindow.document.write('  th { background-color: #f2f2f2; }');
    printWindow.document.write('  @page { size: A4 landscape; margin: 1mm; }');
    printWindow.document.write('  .table-container { transform: scale(0.4); transform-origin: top left; }'); // Adjust scaling as needed
    printWindow.document.write('}');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<div class="table-container">' + tableContent + '</div>'); // Wrap table content for scaling
    printWindow.document.write('</body></html>');

    // Function to check if the print dialog is closed
    printWindow.onafterprint = function() {
        printWindow.close();
        window.location.href = "https://thms.tahdco.com/reports/DailyReport/Upstarct";
    };

    // Add event listener for before unload (cancel print dialog)
    printWindow.onbeforeunload = function() {
        window.location.href = "https://thms.tahdco.com/reports/DailyReport/Upstarct";
    };

    printWindow.print();
});





    // $('#work_abstract thead').css({
    //     'font-weight': 'bold',
    //     'text-align': 'center'
    // });

$('#work_abstract th').css({
    'font-weight': 'bold',
    'text-align': 'center'
});


  $('#exportBtn').click(function() {
        // Function to convert HTML table to CSV
        function tableToCSV() {
            var csv = [];
            $('#work_abstract tr').each(function() {
                var row = [];
                $(this).find('th, td').each(function() {
                    var text = $(this).text().trim().replace(/"/g, '""'); // Escape double quotes
                    row.push('"' + text + '"'); // Wrap text in double quotes
                });
                csv.push(row.join(',')); // Join row elements with commas
            });
            return csv.join('\n'); // Join rows with new line character
        }

        var csv = tableToCSV();
        var blob = new Blob([csv], { type: 'text/csv' });
        var url = URL.createObjectURL(blob);

        // Create a temporary element (a.href) for downloading
        var a = document.createElement('a');
        a.href = url;
        a.download = 'Abstract.csv'; // File name
        document.body.appendChild(a);
        a.click();

        // Cleanup
        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 0);
    });
        
        
    });

</script>

<!-- Load the Google Maps JavaScript API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCU7IM8zVtGzCvLAhx6JUYin-Rdk42_m-s&callback=initMap1&libraries=geometry" async defer></script>

@endsection