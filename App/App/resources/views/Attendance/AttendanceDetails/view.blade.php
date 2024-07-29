@extends('layouts.layout')
@section('content')

<style>
    .custom-select-sm {
        width: 16vh; /* Adjust the width as needed */
    }
    #techofficer_filter {
    background-color: gainsboro;
}

  #work_abstract th {
            font-weight: bold;
            text-align: center;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background-color: #710404;
                border: 1px solid #c5c1c1;
                border-radius: 4px;
                display: inline-block;
                margin-left: 5px;
                margin-top: 5px;
                padding: 0;
                color: white;
                /* align-content: normal; */
            }
            /* Center align text inside select2 dropdown options */
.select2-results__option {
    text-align: left; /* Center align text */
}

/* Center align the select2 dropdown itself */
.select2-container--default .select2-dropdown {
    margin: 0 auto; /* Center the dropdown container */
}

/* Optional: Center align the search box inside the dropdown */
.select2-search--dropdown {
    display: flex;
    justify-content: left;
}

.select2-search__field {
    text-align: left; /* Center align text inside the search box */
}

</style>
&nbsp;
<div class="container-fluid">
	<div class="page-header" style="margin-left:-1.5vh">
	    <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 ">
                    <div class="col-sm-4 col-md-4 col-xl-4">
                        <h1 class="m-0">Attendance</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-3 col-md-3 col-xl-3"></div>
                    <div class="col-sm-5 col-md-5 col-xl-5 d-flex align-items-center justify-content-end">
                    <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Attendance</li>
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
								    <h3><b>Attendance</b></h3>
								</div>
								<div class="col-xl-4 col-sm-4 col-md-4 my-2 text-right text-md-right">
								    <div class="row">
								        <div class="col-md-5 col-xl-5 col-sm-5">
								            
								        </div>
								        <div class="col-md-7 col-xl-7 col-sm-7">
    										<a href="{{ url('/') }}/Attendance/AttendanceDetails/Create" class="btn btn-outline-primary btn-air-success btn-sm" type="button" >Attendance</a>

								        </div> 
								    </div>
								</div>
							</div>
						</div>
						<div class="card-body " style="margin-top:-0%">
						        <!-- <button class="btn  btn-outline-primary btn-air-success btn-sm m-2 p-2 mr-1" id="printTable">Print Table</button> -->
						  
                                <!-- <button class="btn  btn-outline-success btn-air-success btn-sm m-2 p-2 mr-1 hidden" id="Upstarct">Abstarct</button> -->
                           
							<div id="techofficer_filter" class="form-row d-flex justify-content-left m-1 mb-2">
								<div class="col-sm-3 justify-content-left m-1 ">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">District</label>
										<select id="lstDistrict" class="form-control multiselect"  multiple>
                                        <?php 
												foreach ($District as $key => $district) {
												?>
												<option value="<?php echo $district->DID; ?>"><?php echo $district->DName; ?></option>
												<?php
												}
												?>
										</select>
									</div>
								</div>
								<div class="col-sm-2 justify-content-left m-1 ">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Institute</label>
										<select id="lstInstitude" class="form-control multiselect"  multiple>
										
										</select>
									</div>
								</div>
								
								
								<div class="col-sm-2 justify-content-left m-1  ">
									<div class="form-group text-center mh-60">
										<label style="margin-bottom:0px;">Course</label>
										<select id="lstCourse" class="form-control multiselect"  multiple>
										  
										     
										
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
										<!--<select id="lstStatus" class="form-control multiselect"  multiple>-->
										<!--	<option value="1" selected>Active</option>-->
										<!--	<option value="0">Inactive</option>-->
										<!--</select>-->
									</div>
								</div>

							</div>
							
                            <table class="table" id="tblBeneficiary" class="table-responsive">
                                <thead>
                                    <tr>
                                      	<th class="text-center">District</th>
                                        <th class="text-center">Institute</th>
                                        <th class="text-center">Course</th>
                                        <th class="text-center">Present Count</th>
                                        <th class="text-center">Total Count</th>
                                        <th class="text-center">Action</th>
							
				                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <table class="table" id="tblBeneficiary_1" class="table-responsive">
                                <thead>
                                    <tr>
                                      	<th class="text-center">Institute Name</th>
                                        <th class="text-center">Course</th>
                                        <th class="text-center">Student Name</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Percentage</th>
							
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

        let district="";
        let course="";
        let Institude="";
        let FromDate='';
        let ToDate='';
        $('.multiselect').select2();
        let RootUrl=$('#txtRootUrl').val();


        const LoadTable=async()=>{

			  
			$('#tblBeneficiary').dataTable({
                
			    "bProcessing": true,
                "bServerSide": true,
                "ordering": false, // Disable sorting
                "sorting":false,
                "ajax": {
                    "url": RootUrl + "Attendance/AttendanceDetails/data",
                    "headers": {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    "type": "POST",
                    "data": function (d) {
                        
                        d.CityID = district;
                        d.Institute_Id = Institude;
                        d.Course_ID = course;
                        d.Created_On=FromDate;
                        d.Deleted_On=ToDate;
                    }
                },

                "deferRender": true,
                "responsive": true,
                "select": true, // Enable checkbox selectors
                "dom": 'Bfrtip',
				"iDisplayLength": "-1",
				// "lengthMenu": [[10, 15, 10,50,100, -1], [10, 15, 10,50,100, "All"]],
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
                "initComplete": function() {
                    var table = this.api();
                    // Loop through each column and add a select filter below each column header
                    table.columns().every(function(index) {
                        var column = this;
                        var headerCell = $(column.header());
                
                        // Remove any existing filter elements
                        headerCell.find('.filter-select').remove();
                
                        // Add filters only to the columns except the last two
                        if (index < table.columns().nodes().length - 2) {
                            // Store the original header text
                            var title = headerCell.text().trim(); // Trim whitespace from header text
                
                            // Create a select element and append it below the header
                            var select = $('<select class="custom-select-sm filter-select"><option value=""></option></select>')
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? val : '', true, false).draw();
                                });
                
                            // Populate the select element with unique values from the column
                            column.data().unique().sort().each(function(d, j) {
                                select.append('<option class="filter" value="' + d + '">' + d + '</option>');
                            });
                
                            // Append the select element directly after the original header text
                            headerCell.append(select);
                        }
                    });

                    if (table.page.len() <= 10) {
                        table.page.len(10).draw();
                        // Introduce a delay before triggering the click event
                        setTimeout(() => {
                            // Trigger click on element with data-dt-idx="2"
                            $('[data-dt-idx="2"]').click();
                            // Move to the previous page of the table
                            $('#tblBeneficiary_previous').click();
                        }, 100); // Adjust the delay time as needed
                    }
                },
		        buttons: [
                    'pageLength',
                    @if($crud['excel'] == 0)
                        {
                            extend: 'excel',
                            footer: true,
                            title: 'Daily Report',
                            action: DataTableExportOption,
                            exportOptions: {
                                columns: "thead th:not(.noExport):not(:nth-child(6))"  // Exclude the Image column (assuming it's the 6th column)
                            },
                            customize: function(xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
            
                                // Find and remove the first empty row (if exists)
                                var firstEmptyRow = $('row', sheet).filter(function() {
                                    return $(this).children().length === 0;
                                }).first();
            
                                if (firstEmptyRow.length > 0) {
                                    firstEmptyRow.remove();
                                }
            
                                // Set custom headers in the second row
                                var customHeaders = [
                                    { text: "Tech Assistant", style: "s1" },
                                    { text: "Beneficiary", style: "s1" },
                                    { text: "Builder", style: "s1" },
                                    { text: "Work Progress", style: "s1" },
                                    { text: "Remarks", style: "s1" }
                                ];
            
                                var headerRow = '<row r="2">';
                                customHeaders.forEach((header, index) => {
                                    var col = String.fromCharCode(65 + index); // Convert to A, B, C, etc.
                                    headerRow += `<c t="inlineStr" s="${header.style}" r="${col}2"><is><t>${header.text}</t></is></c>`;
                                });
                                headerRow += '</row>';
            
                                // Replace the existing second row with custom headers
                                $('row[r=2]', sheet).replaceWith(headerRow);
                                
                                // Calculate total count
                                var totalCount = $('row', sheet).length - 2; // Adjust based on how many rows you removed
                        
                                var filters = [];
                                if (Vselected) filters.push(Vselected);
                                if (district) filters.push(district);
                                if (housingstatus) filters.push(housingstatus);
                                if (FromDate) filters.push(FromDate);
                                if (ToDate) filters.push(ToDate);
                        
                                var filterText = filters.length > 0 ? filters.join('/') + ' = ' : 'Total ';
                        
                                // Add empty rows
                                var emptyRow1 = `
                                    <row r="${totalCount + 3}">
                                        <c t="inlineStr" s="s1" r="A${totalCount + 0}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="B${totalCount + 0}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="C${totalCount + 0}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="D${totalCount + 0}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="E${totalCount + 0}">
                                            <is><t></t></is>
                                        </c>
                                        <!-- Add more empty cells as needed -->
                                    </row>`;
                        
                                var emptyRow2 = `
                                    <row r="${totalCount + 4}">
                                        <c t="inlineStr" s="s1" r="A${totalCount + 4}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="B${totalCount + 4}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="C${totalCount + 4}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="D${totalCount + 4}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="E${totalCount + 4}">
                                            <is><t></t></is>
                                        </c>
                                        <!-- Add more empty cells as needed -->
                                    </row>`;
                        
                                // Add total count row at the end with dynamic text
                                var totalRow = `
                                    <row r="${totalCount + 5}">
                                        <c t="inlineStr" s="s1" r="A${totalCount + 5}">
                                            <is><t>${filterText}${totalCount}</t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="B${totalCount + 5}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="C${totalCount + 5}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="D${totalCount + 5}">
                                            <is><t></t></is>
                                        </c>
                                        <c t="inlineStr" s="s1" r="E${totalCount + 5}">
                                            <is><t></t></is>
                                        </c>
                                        <!-- Add more empty cells as needed -->
                                    </row>`;
                        
                                sheet.childNodes[0].childNodes[1].innerHTML += emptyRow1 + emptyRow2 + totalRow;
                            }
                        },
                    @endif
                    @if($crud['copy'] == 1)
                    {
                        extend: 'copy',
                        footer: true,
                        title: 'Work Progress',
                        action: DataTableExportOption,
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        },
                        customize: function(copy) {
                            // Modify the data being copied
                            var customHeaders = [
                                "code",
                                "WP Id",
                                "work",
                                "Status",
                                "Action"
                            ];
                
                            var data = copy.split('\n');
                            data[0] = customHeaders.join('\t'); // Replace header row with custom headers
                            return data.join('\n');
                        }
                    },
                    @endif
                    @if($crud['csv'] == 1)
                    {
                        extend: 'csv',
                        footer: true,
                        title: 'Work Progress',
                        action: DataTableExportOption,
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        },
                        customize: function(csv) {
                            var customHeaders = [
                                "code",
                                "WP Id",
                                "work",
                                "Status",
                                "Action"
                            ];
                
                            var data = csv.split('\n');
                            data[0] = customHeaders.join(','); // Replace header row with custom headers
                            return data.join('\n');
                        }
                    },
                    @endif
                    @if($crud['print'] == 1)
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Daily Report',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        },
                        customize: function(win) {
                            const customHeaders = [
                                "Tech Assistant",
                                "Beneficiary",
                                "Builder",
                                "Work Progress",
                                "Remarks",
                                "Image"
                            ];
                
                            $(win.document.body).find('h1').text('Daily Report');
                
                            const head = $(win.document.head).html();
                            let body = $(win.document.body).html();
                
                            let headerRow = '<thead><tr>';
                            customHeaders.forEach(header => {
                                headerRow += `<th>${header}</th>`;
                            });
                            headerRow += '</tr></thead>';
                
                            body = body.replace(/<thead>.*<\/thead>/s, headerRow);
                
                            $(win.document.head).html(head);
                            $(win.document.body).html(body);
                
                            // Include the table styles
                            $(win.document.head).append('<style>.thumbnail{max-width: 100px; max-height: 100px; margin: 5px;}</style>');
                        }
                    },
                    @endif
            @if($crud['pdf'] == 1)
            {
                text: 'Export PDF',
                action: function ( e, dt, node, config ) {
                    exportToPDF();
                }
            }
            @endif
                ],
                columnDefs: [
                    {"className": "dt-left", "targets": 0},
                    {"className": "dt-left", "targets": 1},
                    {"className": "dt-left", "targets": 2},
                    {"className": "dt-left", "targets": 3},
                    {"className": "dt-left", "targets": 4},
                    {"className": "dt-left", "targets": 5}

                
                ]
			});
        }


         LoadTable();
        


        $('#FromDate').change(function() {
            
        var currentFormattedDate = getCurrentFormattedDate();
        console.log("Formart :",currentFormattedDate);
        
        $('#ToDate').val(currentFormattedDate);
        
            FromDate = '';
            FromDate=$('#FromDate').val();
            ToDate=$('#ToDate').val();
            console.log("From :",FromDate);
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
        

        // $('#lstMilStatus').val('1');
        // $('#lstMilStatus').trigger('change');
        

        
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


        

$('#lstDistrict').change(function() {
        // Initialize Vselected variable
        
        // Clear previous selected values
        district = '';

        // Get the selected options from #lstDivision
        var selectedOptions = $('#lstDistrict option:selected');

        // Iterate through each selected option
        selectedOptions.each(function(index, option) {
            // Append the value of the selected option to Vselected
            district += (district === "") ? "" : ",";
            // Vselected += $(option).val();
            district += "'" + $(option).val() + "'";
            //  Vselected += $(this).val(); 
            // Log the value of the selected option to the console
            console.log($(option).val());
        });

        // Call LoadTable function
        getremove();
        LoadInstitude();
        LoadTable();

    });


    const LoadInstitude=async()=>{

            let editHPID=$('#lstInstitude').attr('data-HPID');
                        
			$('#lstInstitude').select2('destroy');
            $('#lstInstitude option').remove();
			var institutedistrict=$('#lstDistrict').val();
			
            $.ajax({
    type: "POST",
    url: "{{url('/')}}/Attendance/AttendanceDetails/GetInstitute",
    headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
    data: { CityID: institutedistrict },
    error: function (e, x, settings, exception) {
        ajax_errors(e, x, settings, exception);
    },
    success: function (response) {
        
        for (let item of response) {
                let selected = "";
                if (item.Institute_Id == editHPID) {
                    selected = "selected";
                }
                $('#lstInstitude').append(
                    `<option ${selected} value="${item.Institute_Id}">${item.Institute_Name} / ${item.MobileNumber} / ${item.CityID}</option>`
                );
            }


            // Handle disabling if only one option
                      // Initialize Select2 with search enabled
                      $('#lstInstitude').select2({
        placeholder: "Select a Institute",
        allowClear: true,
        width: 'resolve'  // Adjust the width to fit the container
    });
    
    }
});

        }

    const getremove = async () => {
        $('#tblBeneficiary').DataTable().destroy();

    }



    $('#lstInstitude').change(function() {
        // Initialize Vselected variable
        
        // Clear previous selected values
        Institude = '';

        // Get the selected options from #lstDivision
        var selectedOptions = $('#lstInstitude option:selected');

        // Iterate through each selected option
        selectedOptions.each(function(index, option) {
            // Append the value of the selected option to Vselected
            Institude += (Institude === "") ? "" : ",";
            // Vselected += $(option).val();
            Institude += "'" + $(option).val() + "'";
            //  Vselected += $(this).val(); 
            // Log the value of the selected option to the console
            console.log($(option).val());
        });

        // Call LoadTable function
        getremove();
        LoadTable();
        LoadCourse();
    });
   
    const LoadCourse=async()=>{

let editHPID=$('#lstCourse').attr('data-HPID');
            
$('#lstCourse').select2('destroy');
$('#lstCourse option').remove();

var institute=$('#lstInstitude').val();

$.ajax({
type: "POST",
url: "{{url('/')}}/Attendance/AttendanceDetails/GetCourse",
headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
data: { Institude_Id: institute },
error: function (e, x, settings, exception) {
ajax_errors(e, x, settings, exception);
},
success: function (response) {

    for (let item of response) {
    // Split the Course_ID and Institute_Course by comma
    let courseIds = item.Course_ID.split(',');
    let courseNames = item.Institute_Course.split(',');

    // Iterate through each course and add it to the select element
    for (let i = 0; i < courseIds.length; i++) {       

        $('#lstCourse').append(
            `<option  value="${courseIds[i]}">${courseNames[i]}</option>`
        );

    }
}

$('#lstCourse').select2({
placeholder: "Select a Course",
allowClear: true,
width: 'resolve'  // Adjust the width to fit the container
});

}
});


}



    $('#lstCourse').change(function() {
        // Initialize Vselected variable
        
        // Clear previous selected values
        course = '';

        // Get the selected options from #lstDivision
        var selectedOptions = $('#lstCourse option:selected');

        // Iterate through each selected option
        selectedOptions.each(function(index, option) {
            // Append the value of the selected option to Vselected
            course += (course === "") ? "" : ",";
            // Vselected += $(option).val();
            course += "'" + $(option).val() + "'";
            //  Vselected += $(this).val(); 
            // Log the value of the selected option to the console
            console.log($(option).val());
        });

        // Call LoadTable function
        getremove();
        LoadTable();
    });
   
      
    
    });
    
    


    
</script>
@endsection