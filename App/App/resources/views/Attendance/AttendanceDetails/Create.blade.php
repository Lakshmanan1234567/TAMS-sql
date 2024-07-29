@extends('layouts.layout')
@section('content')

<style>
    table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}
</style>
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12 m-3 ml-3">
				<ol class="breadcrumb align-items-center justify-content-end ml-2">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" title="Dashboard"><i class="f-16 fa fa-home" title="Dashboard"></i></a></li>
					<li class="breadcrumb-item"><a href="{{ url('/') }}/Attendance/AttendanceDetails" title="Attendance">Attendance</a></li>
					<li class="breadcrumb-item"><a href="{{ url('/') }}/Attendance/AttendanceDetails" title="Attendance Details">Attendance Details</a></li>
                    <li class="breadcrumb-item ml-3">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row d-flex justify-content-center">
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header text-center"><h3> Attendance </h3></div>
				<div class="card-body " >
                    
                    <div class="row">

                    <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstDivision mb-1"><b> Division  </b><span class="required"> * </span></label>
                                <select id="lstDivision" class="form-control multiselect">
                                <option class="select2-selection__placeholder" value="">Select a Division</option>
                                        <?php 
												foreach ($Division as $key => $division) {
												?>
												<option value="<?php echo $division->DivisionName; ?>"><?php echo $division->DivisionName; ?></option>
												<?php
												}
												?>
										</select>
                                <div class="errors" id="lstDivision-err"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstDistrict mb-1"><b> District  </b><span class="required"> * </span></label>
                                <select id="lstDistrict" class="form-control multiselect">
                                <option class="select2-selection__placeholder" value="">Select a District</option>

										</select>
                                <div class="errors" id="lstDistrict-err"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstInstitude mb-1"><b> Institute   </b><span class="required"> * </span></label>
                                <select id="lstInstitude" class="form-control multiselect">
                                <option class="select2-selection__placeholder" value="">Select a Institute</option>

								</select>
                                <div class="errors" id="lstInstitude-err"></div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="lstCourse mb-1"> <b>Course  </b><span class="required"> * </span></label>
                            	<select id="lstCourse" class="form-control multiselect">
                                <option class="select2-selection__placeholder" value="">Select a Course</option>
				
								</select>
                                <div class="errors" id="lstCourse-err"></div>
                            </div>
                        </div>
                        <div class="container mb-2">
                    <label class="lstattendance mb-2"> <b> Attendance </b></label>
                    <table id="attendance-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be dynamically generated -->
            </tbody>
        </table>
    </div>
                    </div>
              

                    
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==1)
                            <!--href="{{url('/')}}/master/AssignedOfficers/"-->
                            <a class="btn btn-sm btn-outline-light" id="btnCancel">Back</a>
                            @endif
                            
                            @if((($crud['add']==1) && ($isEdit==0))||(($crud['edit']==1) && ($isEdit==1)))
                                <button class="btn btn-sm btn-outline-primary" id="btnSave">@if($isEdit==0) Add @else Save @endif</button>
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
let division="";
let district="";
let course="";
let Institude="";
let FromDate='';
let ToDate='';
$('.multiselect').select2();
let RootUrl=$('#txtRootUrl').val();


$('#lstDivision').change(function() {
// Initialize Vselected variable

// Clear previous selected values
division = '';

// Get the selected options from #lstDivision
var selectedOptions = $('#lstDivision option:selected');

// Iterate through each selected option
selectedOptions.each(function(index, option) {
    // Append the value of the selected option to Vselected
    division += (division === "") ? "" : ",";
    // Vselected += $(option).val();
    division += "'" + $(option).val() + "'";
    //  Vselected += $(this).val(); 
    // Log the value of the selected option to the console
    console.log($(option).val());
});

// Call LoadTable function
LoadDistrict();

});


const LoadDistrict=async()=>{

let editHPID=$('#lstDistrict').attr('data-HPID');
            
$('#lstDistrict').select2('destroy');
$('#lstDistrict option').remove();
$('#lstDistrict').append('<option value="">Select a District</option>');

var institutedivision=$('#lstDivision').val();

$.ajax({
type: "POST",
url: "{{url('/')}}/Attendance/AttendanceDetails/GetDistrict",
headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
data: { DivisionName: institutedivision },
error: function (e, x, settings, exception) {
ajax_errors(e, x, settings, exception);
},
success: function (response) {

for (let item of response) {
    let selected = "";
    if (item.DivId == editHPID) {
        selected = "selected";
    }
    $('#lstDistrict').append(
        `<option ${selected} value="${item.DID}">${item.DName}</option>`
    );
}

}

});
$('#lstDistrict').select2({
placeholder: "Select a District",
allowClear: true,
width: 'resolve'  // Adjust the width to fit the container
});
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
LoadInstitude();

});


const LoadInstitude=async()=>{

    let editHPID=$('#lstInstitude').attr('data-HPID');
                
    $('#lstInstitude').select2('destroy');
    $('#lstInstitude option').remove();
    $('#lstInstitude').append('<option value="">Select a Institute</option>');

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
$('#attendance-table').remove();

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
LoadCourse();
});

const LoadCourse=async()=>{

let editHPID=$('#lstCourse').attr('data-HPID');
    
$('#lstCourse').select2('destroy');
$('#lstCourse option').remove();
$('#lstCourse').append('<option value="">Select a Course</option>');

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
//LoadStudent();
});

const LoadStudent=async()=>{

let editHPID=$('#lstCourse').attr('data-HPID');
    
var course=$('#lstCourse').val();

$.ajax({
type: "POST",
url: "{{url('/')}}/Attendance/AttendanceDetails/GetStudents",
headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
data: { Course_Id: course },
error: function (e, x, settings, exception) {
ajax_errors(e, x, settings, exception);
},
success: function (response) {


const tableBody = document.querySelector('#attendance-table tbody');

const data = [
    { name: 'Steve', attendance: [0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
    { name: 'Thomas', attendance: [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0] },
    { name: 'Alisha', attendance: [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0] },
    { name: 'John', attendance: [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
    { name: 'Elena', attendance: [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
    { name: 'Jane', attendance: [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
    { name: 'Chris', attendance: [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
    { name: 'Martha', attendance: [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
    { name: 'Angela', attendance: [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
    { name: 'Dave', attendance: [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0] },
];

data.forEach(person => {
    const row = document.createElement('tr');
    const nameCell = document.createElement('td');
    nameCell.textContent = person.name;
    row.appendChild(nameCell);

    person.attendance.forEach(day => {
        const cell = document.createElement('td');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.checked = day === 1;
        cell.appendChild(checkbox);
        row.appendChild(cell);
    });

    tableBody.appendChild(row);
});




}
});


}


});



</script>
@endsection