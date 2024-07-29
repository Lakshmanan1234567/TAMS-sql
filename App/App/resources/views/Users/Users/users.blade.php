@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-title">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Users & Permissions</li>
					<li class="breadcrumb-item">Users</li>
					<li class="breadcrumb-item">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
		<div class="row justify-content-center">
				<div class="col-sm-8">
					<div class="card">
						<div class="card-header text-center">
                            <h5>Users</h5>
						</div>
						<div class="card-body p-20">
								<div class="row">
									<div class="col-md-4"></div>
									<div class="col-md-4 text-center userImage">
										<input type="file" id="txtCImage" class="dropify" data-default-file="<?php if($isEdit==true){if($EditData[0]->ProfileImage !=""){ echo url('/')."/".$EditData[0]->ProfileImage;}}?>"  data-allowed-file-extensions="jpeg jpg png gif" />
										<span class="errors" id="txtCImage-err"></span>
									</div>
									<div class="col-md-4"></div>
								</div>
							<div class="row mt-20">
								<div class="col-md-6">
									<div class="form-group">
										<label for="FirstName">First Name <span class="required">*</span></label>
									
								<input type="text" id="FirstName" class="form-control" placeholder="First Name" 	value="<?php if($isEdit==true){ echo $EditData[0]->FirstName;} ?>">
										<span class="errors" id="FirstName-err"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="LastName">Last Name <span class="required">*</span></label>
									
								<input type="text" id="LastName" class="form-control " placeholder="Last Name" value="<?php if($isEdit==true){ echo $EditData[0]->LastName;} ?>">
										<span class="errors" id="LastName-err"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="DOB">Date Of Birth <span class="required">*</span></label>
										
										<input type="date" id="DOB" class="form-control date" placeholder="Date of Birth"  value="<?php if($isEdit==true){ echo $EditData[0]->DOB;} ?>" >
										<span class="errors" id="DOB-err"></span>
									</div>
								</div><div class="col-md-6 d-none">
									<div class="form-group">
										<label for="DOJ">Date Of Join <span class="required">*</span></label>
										
										<input type="date" id="DOJ" class="form-control date" placeholder="Date of Join"  value="<?php if($isEdit==true){ echo $EditData[0]->DOJ;} ?>">
										<span class="errors" id="DOJ-err"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="Gender">Gender <span class="required">*</span></label>
										<select class="form-control select2" id="Gender">
											<option value="">Select a Gender</option>
										</select>
										<span class="errors" id="Gender-err"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="City">City <span class="required">*</span></label>
										<select class="form-control select2" id="City">
											<option value="">Select a City</option>
										</select>
										<span class="errors" id="City-err"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="PinCode">Postal Code <span class="required">*</span></label>
										<select class="form-control select2Tag" id="PinCode">
											<option value="">Select a Postal Code</option>
										</select>
										<span class="errors" id="PinCode-err"></span>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="Address">Address <span class="required">*</span></label>
										<textarea class="form-control" placeholder="Address" id="Address" name="Address" rows="2" ><?php if($isEdit==true){ echo $EditData[0]->Address;} ?></textarea>
										<span class="errors" id="Address-err"></span>
									</div>
								</div>
									<div class="col-md-6">
									
										<div class="form-group">
											<label for="Email">Email <span class="required">*</span></label>
											<input type="email" id="Email" class="form-control" placeholder="E-Mail"  value="<?php if($isEdit==true){ echo $EditData[0]->EMail;} ?>">
											<span class="errors" id="Email-err"></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="MobileNumber"> MobileNumber <span id="CallCode"></span> <span class="required">*</span></label>
											<input type="number" id="MobileNumber" class="form-control" data-length="0" placeholder="Mobile Number enter without country code"  value="<?php if($isEdit==true){ echo $EditData[0]->MobileNumber;} ?>">
											
											<span class="errors" id="MobileNumber-err"></span>
										</div>
									</div>
									<div class="col-md-6 EditPassword">
									<div class="form-group">
										<label for="FirstPass">Password <span class="required">*</span></label>
										<input type="password" id="FirstPass" class="form-control " placeholder="Password"  value="<?php if($isEdit==true){ echo $EditData[0]->Password1;} ?>">
										
										<span class="errors" id="FirstPass-err"></span>
									</div>
								</div>
								<div class="col-md-6 EditCPassword">
									<div class="form-group">
										<label for="ConfirmPass">Confirm Password <span class="required">*</span></label>
										<input type="Password" id="ConfirmPass" class="form-control " name='Confirm Password' placeholder="Confirm Password"  value="<?php if($isEdit==true){ echo $EditData[0]->Password1;} ?>">
										
										<span class="errors" id="ConfirmPass-err"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="UserRole">Role <span class="required">*</span></label>
										<select class="form-control select2Tag" id="UserRole">
											<option value="">Select a Role</option>
										</select>
										<span class="errors" id="UserRole-err"></span>
									</div>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divDivision">
									<label for="Division">Division <span class="required">*</span></label>
									<select class="form-control select2Tag" id="lstDivision">
										<option value="">Select a Division</option>
									</select>
									<span class="errors" id="lstDivision-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="institute Name">Institute Name <span class="required">*</span></label>
									<input type="text" id="txtInstituteName" class="form-control" name="txtInstituteName" placeholder="Institute Name" value="<?php if($isEdit==true){ echo $EditData[0]->InstituteName;} ?>">
									<span class="errors" id="txtInstituteName-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="Latitude">Latitude<span class="required">*</span></label>
									<input type="text" id="txtlat" class="form-control" name="txtlat" placeholder="Latitude" value="<?php if($isEdit==true){ echo $EditData[0]->latitude;} ?>">
									<span class="errors" id="txtlat-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="Longitude">Longitude<span class="required">*</span></label>
									<input type="text" id="txtlan" class="form-control" name="txtlan" placeholder="Longitude" value="<?php if($isEdit==true){ echo $EditData[0]->longitude;} ?>">
									<span class="errors" id="txtlan-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="insAddress">Address<span class="required">*</span></label>
									<textarea id="insAddress" class="form-control" name="insAddress" placeholder="Address"  rows="2" ><?php if($isEdit==true){ echo $EditData[0]->InstituteAddress;} ?></textarea>
									<span class="errors" id="insAddress-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="Postal Code">Postal Code<span class="required">*</span></label>
									<input type="text" id="txtPostalCode" class="form-control" name="txtPostalCode" placeholder="Postal Code" value="<?php if($isEdit==true){ echo $EditData[0]->PostalCode;} ?>">
									<span class="errors" id="txtPostalCode-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="Cource">Cource<span class="required">*</span></label>
									<select class="form-control multiselect" id="lstCource" multiple>
										
									</select>
									<span class="errors" id="lstCource-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="GST No">GST NO<span class="required">*</span></label>
									<input type="text" id="txtGst" class="form-control" name="txtGst" placeholder="GST Number" value="<?php if($isEdit==true){ echo $EditData[0]->GSTNumber;} ?>">
									<span class="errors" id="txtGst-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divInstitute">
									<label for="Student Slot">Student Slot<span class="required">*</span></label>
									<input type="text" id="txtStudentSlot" class="form-control" name="txtStudentSlot" placeholder="Student Slot"  value="<?php if($isEdit==true){ echo $EditData[0]->InstituteSlot;} ?>">
									<span class="errors" id="txtStudentSlot-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divStudent">
									<label for="Cource">Cource<span class="required">*</span></label>
									<select class="form-control multiselect" id="lststdCource" multiple>
										
									</select>
									<span class="errors" id="lststdCource-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divStudent">
									<label for="Permanent Address">Permanent Address<span class="required">*</span></label>
									<textarea id="stdsAddress" class="form-control" name="stdsAddress" placeholder="Permanent Address"  rows="2" ><?php if($isEdit==true){ echo $EditData[0]->stdAddress;} ?></textarea>
									<span class="errors" id="stdsAddress-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divStudent">
									<label for="Degree">Degree<span class="required">*</span></label>
									<input type="text" id="txtDegree" class="form-control" name="txtDegree" placeholder="Degree" value="<?php if($isEdit==true){ echo $EditData[0]->StdDegree;} ?>">
									<span class="errors" id="txtDegree-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divStudent">
									<label for="education_status">Education Status<span class="required">*</span></label><br>
									<input type="radio" id="status_pursuing" name="education_status" value="Pursuing" <?php if($isEdit == true && $EditData[0]->StdEduStatus == "Pursuing") { echo "checked"; } ?><?php if(!$isEdit) { echo "checked"; } ?>>
									<label for="status_pursuing">Pursuing</label><br>
									<input type="radio" id="status_passedout" name="education_status" value="Passedout" <?php if($isEdit==true && $EditData[0]->StdEduStatus=="Passedout"){ ?>checked<?php }?>>
									<label for="status_passedout">Passedout</label><br>
									
									<div id="year_field" style="display: none;">
										<label for="passedout_year">Year<span class="required">*</span></label>
										<input type="text" id="passedout_year" name="passedout_year" placeholder="Enter year (YYYY)" maxlength="4" value="<?php if($isEdit==true && $EditData[0]->StdEduStatus=="Passedout"){echo $EditData[0]->StdYearofPassOut;}?>" >
									</div>
									<span class="errors" id="year_err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6 divReport">
									<label for="Reported By">Reported By<span class="required">*</span></label>
									<select class="form-control select2Tag" id="lstReportedto">
										<option value="">Select a Officer</option>
									</select>
									<span class="errors" id="lstReportedto-err"></span>
								</div>
								<div class="col-sm-6 col-md-6 col-xl-6">
									<label for="">Active Status</label>
									<select class="form-control" id="lstActiveStatus">
										<option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
										<option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
									</select>
								</div>							                                    
							</div>
							<input type="hidden" id="IsEditval" class="form-control"   value="{{$isEdit}}">
							
							<div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a  class="btn btn-sm btn-outline-dark" id="btnCancel">Back</a>
                            @endif
                            
                            @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                <button class="btn btn-sm btn-outline-success" id="btnSubmit">@if($isEdit==true) Update @else Save @endif</button>
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
		var insMltCource="";
		var stdMltCource="";
		var isEdit = $('#IsEditval').val();
		$('.multiselect').select2();
		if(isEdit == true){
			$('.EditPassword').prop("hidden", true);
			$('.EditCPassword').prop("hidden", true);
		}
		let formModified = false;
        // Function to detect form changes
        const detectFormChanges = () => {
            $('input, select').each(function() {
                $(this).data('initial-value', $(this).val());
            });

            $('input, select').on('change keyup', function() {
                formModified = true;
            });
        };

        // Call the function to start detecting changes
        detectFormChanges();
        $('#btnCancel').click(function(){
            if(formModified) {
                swal({
                    title: "Unsaved Changes",
                    text: "You have unsaved changes. Do you really want to leave?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn btn-danger",
                    confirmButtonText: "Yes, Go Back!",
                    cancelButtonText: "No, Stay Here",
                    closeOnConfirm: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        window.location.replace(document.referrer);
                    }
                });
            } else {
                window.location.replace(document.referrer);
            }
        });
        const formValidation=()=>{
			$('.errors').html('');
            let status=true;
			let FirstName = $('#FirstName').val();
			let LastName = $('#LastName').val();
			let FirstPass = $('#FirstPass').val();
			let ConfirmPass = $('#ConfirmPass').val();
			let DOB = $('#DOB').val();
			let DOJ = $('#DOJ').val();
			let Gender = $('#Gender').val();
			let Country = $('#Country').val();
			let State = $('#State').val();
			let City = $('#City').val();
			let Address = $('#Address').val();
			let PinCode = $('#PinCode').val();
			let Email = $('#Email').val();
			let MobileNumber = $('#MobileNumber').val();
			let txtCImage = $('#txtCImage').val();
			let imagePath = $('#txtCImage').attr('data-default-file');
			let lstActiveStatus = $('#lstActiveStatus').val();
			let lstReportTo = $('#lstReportTo').val();
			let UserRole = $('#UserRole').val();

			let Division=$('#lstDivision').val();

			let InstituteName=$('#txtInstituteName').val();
			let Latitude=$('#txtlat').val();
			let Longitude=$('#txtlan').val();
			let insAddress=$('#insAddress').val();
			let insPostalCode=$('#txtPostalCode').val();
			let insCource=$('#lstCource').val();
			let insGST=$('#txtGst').val();
			let StudentSlot=$('#txtStudentSlot').val();
			let insReported=$('#lstReportedto').val();

			let stdCource=$('#lststdCource').val();
			let stdAddress=$('#stdsAddress').val();
			let stdDegree=$('#txtDegree').val();
			var selectedValue = $('input[name="education_status"]:checked').val();
			let stdYear=$('#passedout_year').val();
			let stdReported=$('#lststdReportedto').val();

			if (FirstName == "") {
				$('#FirstName-err').html('First Name is required');status = false;
			} else if (FirstName.length > 50) {
				$('#FirstName-err').html('First Name may not be greater than 50 characters');status = false;
			}else if (FirstName.length <3) {
				$('#FirstName-err').html('First Name may not be leesthen than 3 characters');status = false;
			}
			if (LastName == "") {
				$('#LastName-err').html('Last Name is required');status = false;
			} else if (LastName.length > 50) {
				$('#LastName-err').html('Last Name may not be greater than 50 characters');status = false;
			}else if (LastName.length < 3) {
				$('#LastName-err').html('Last Name may not be leesthen than 3 characters');status = false;
			}
			if (FirstPass == "") {
				$('#FirstPass-err').html('Password is required');status = false;
			} else if (FirstPass.length < 5) {
				$('#FirstPass-err').html('Password may not be less than 5 characters');status = false;
			}
			if (ConfirmPass == "") {
				$('#ConfirmPass-err').html('Password is required');status = false;
			} else if (ConfirmPass.length < 5) {
				$('#ConfirmPass-err').html('Password may not be less than 5 characters');status = false;
			}
			if(isEdit == false){
				
				if (FirstPass != ConfirmPass) {
					$('#ConfirmPass-err').html('Passwords did not match');status = false;
				}
			}
			if (DOB == "") {
				$('#DOB-err').html('Date of Birth  is required');status = false;
			}
			// if (DOJ == "") {
			// 	$('#DOJ-err').html('Date of Join  is required');status = false;
			// }
			if (Gender == "") {
				$('#Gender-err').html('plese select Gender');status = false;
			}
			if (City == "") {
				$('#City-err').html('please select City');status = false;
			}
			if (Address == "") {
				$('#Address-err').html('Address  is required');status = false;
			}
			if (Address.length < 10) {
				$('#Address-err').html('Address  may not be greater than 10 characters');status = false;
			}

			if (PinCode == "") {
				$('#PinCode-err').html('plese select Postalcode');status = false;
			}

			if (Email == "") {
				$('#Email-err').html('E-Mail  is required');status = false;
			}
			var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
				if(!Email.match(mailformat))
				{
					$('#Email-err').html('Valid E-Mail  is required');status = false;
				}
			if (MobileNumber == "") {
				$('#MobileNumber-err').html('Mobile Number  is required');status = false;
			}
			
			if(typeof(txtCImage) != "undefined" && txtCImage !== null && txtCImage !== ''){
				let validation = fileValidation();
				if(validation !=''){
					$('#txtCImage-err').html(validation);status = false;
				}
			}
			if (UserRole == "") {
				$('#UserRole-err').html('Please Select Role');status = false;
			}
			if($("#lstDivision").is(":visible")){
				if($('#lstDivision').val() ==""){
					$('#lstDivision-err').html("Please Select Division");status = false;
				}
			}
			if($('.divInstitute').is(":visible")){
				// Function to validate latitude
				function validateLatitude(lat) {
					var latPattern = /^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/;
					return latPattern.test(lat);
				}
				// Function to validate longitude
				function validateLongitude(lng) {
					var lngPattern = /^[-+]?(180(\.0+)?|((1[0-7]\d)|(\d{1,2}))(\.\d+)?)$/;
					return lngPattern.test(lng);
				}
				var postalCodeRegex = /^[0-9]{6}$/; // Example: Accepts exactly 6 digits
				var gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[A-Z]{1}[0-9A-Z]{1}$/;  // Candian GST Formate example
				var numericRegex = /^[0-9]+$/; // Canadian Number Formate example
				// InstituteName 
				if (InstituteName.trim() === "") {
					$('#txtInstituteName-err').html("Institute Name Required");
					status = false;
				}
				if(InstituteName.length < 3){
					$('#txtInstituteName-err').html("Institute Name Must Minimum 3 Letter");
					status = false;
				}
				if(InstituteName.length > 50){
					$('#txtInstituteName-err').html("Institute Name Must Maximum 50 Letter");
					status = false;
				}
				// Latitude 
				if (Latitude.trim() === "") {
					$('#txtlat-err').html("Latitude Required");
					status = false;
				}
				if (!validateLatitude(Latitude)) {
					$('#txtlat-err').html("Invalid latitude format");
					status = false;
				} 
				// Longitude
				if (Longitude.trim() === "") {
					$('#txtlan-err').html("Longitude Required");
					status = false;
				}
				if (!validateLongitude(Longitude)) {
					$('#txtlan-err').html("Invalid longitude format");
					status = false;
				}
				// Institute Address 
				if (insAddress == "") {
					$('#insAddress-err').html('Address  is Required');
					status = false;
				}
				if (insAddress.length < 10) {
					$('#insAddress-err').html('Address  may not be greater than 10 characters');
					status = false;
				}
				// Institute Pincode 
				if(insPostalCode.trim() === ""){
					$('#txtPostalCode-err').html('Postal Code Required');
					status = false;
				}
				if (!postalCodeRegex.test(insPostalCode)) {
					$('#txtPostalCode-err').html('Invalid Postal Code Format');
					status = false;
				}
				// Institute Cource 
				if(insMltCource ===""){
					$('#lstCource-err').html('Cource Required');
					status = false;
				}
				// GST Number 
				if(insGST.trim() === ""){
					$('#txtGst-err').html('GST Number Required');
					status = false;
				}
				if (!gstRegex.test(insGST)) {
					$('#txtGst-err').html('Invalid GST Number Format');
					status = false;
				}
				// Student Slot 
				if(StudentSlot.trim()=== ""){
					$('#txtStudentSlot-err').html('Student Slot Required');
					status = false;
				}
				if (!numericRegex.test(StudentSlot)) {
					$('#txtStudentSlot-err').html("Student Slot must contain only numbers");
					status = false;
				}
			}
			if($('.divStudent').is(":visible")){
				// Education status 
				var EducationRgx=/^\d{4}$/;

				// Student Cource 
				if(stdMltCource === ""){
					$('#lststdCource-err').html('Cource Required')
				}
				// Student Address 
				if (stdAddress.trim() === "") {
					$('#stdsAddress-err').html('Address  is Required');
					status = false;
				}
				if (stdAddress.length < 10) {
					$('#stdsAddress-err').html('Address  may not be greater than 10 characters');
					status = false;
				}
				// Degree 
				if(stdDegree.trim() === ""){
					$('#txtDegree-err').html('Degree Required')
				}
				if(stdDegree.length < 3){
					$('#txtInstituteName-err').html("Institute Name Must Minimum 3 Letter");
					status = false;
				}
				if(stdDegree.length > 50){
					$('#txtInstituteName-err').html("Institute Name Must Maximum 50 Letter");
					status = false;
				}
				// Education 
				if($('#passedout_year').is(":visible")){
					var sanitized = stdYear.replace(/\D/g, ''); // Remove any non-numeric characters
					var year = sanitized;
					var currentYear = new Date().getFullYear();
					var minYear = currentYear - 10; // Allow years up to 10 years ago
					var isValidYear = /^\d{4}$/.test(year) && parseInt(year) >= 1900 && parseInt(year) <= currentYear;

					if(!EducationRgx.test(stdYear)){
						$('#year_err').html("Please enter a valid year (YYYY)");
						status = false;
					}
					if (!isValidYear) {
						$('#year_err').text('Please enter a valid year (YYYY) and within the last 10 years');
						status = false;
					}
				}
			}
			if($('#lstReportedto').is(":visible")){
				if($('#lstReportedto').val()==""){
					$('#lstReportedto-err').html('Officer Required');
					status = false;
				}
			}
			return status;
        }
		function fileValidation() {
			var errorMsg = '';
            var fileInput = 
                document.getElementById('txtCImage');
              
            var filePath = fileInput.value;
          
            // Allowing file type
            var allowedExtensions = 
                    /(\.jpg|\.jpeg|\.png|\.gif)$/i;
              
            if (!allowedExtensions.exec(filePath)) {
                errorMsg='Invalid file type';
                fileInput.value = '';
                
            } 
			return errorMsg;
            
        }
		const appInit=async()=>{
            GetCityName();
			getGender();
			getRole();
			$('.divDivision').hide();
			$('.divInstitute').hide();
			$('.divStudent').hide();
			$('.divReport').hide();
		}
		const getRole=async()=>{
			$('#UserRole').select2('destroy');
			$('#UserRole option').remove();
			$('#UserRole').append('<option value="">Select a Role</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Role",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$RoleID="";
							if($isEdit==true){
								$RoleID=$EditData[0]->RoleID;
							}
						@endphp
						if(item.RoleID=="{{$RoleID}}"){selected="selected";}
						$('#UserRole').append('<option '+selected+'  value="'+item.RoleID+'">'+item.RoleName+'</option>');
					}
				}
			});
			$('#UserRole').select2();
			$('#Gender').select2();
		}
		const getGender=async()=>{
			$('#Gender').select2('destroy');
			$('#Gender option').remove();
			$('#Gender').append('<option value="">Select a Gender</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Gender",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$GenderID="";
							if($isEdit==true){
								$GenderID=$EditData[0]->GenderID;
							}
						@endphp
						if(item.GID=="{{$GenderID}}"){selected="selected";}
						$('#Gender').append('<option '+selected+'  value="'+item.GID+'">'+item.Gender+'</option>');
					}
				}
			});
			$('#Gender').select2();
		}

		const getCountry=async()=>{
			
			$('#Country').select2('destroy');
			$('#Country option').remove();
			$('#Country').append('<option value="">Select a Country</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Country",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$CountryID="";
							$CountryName="";
							if($isEdit==true){
								$CountryID=$EditData[0]->CountryID;
							}
						@endphp
						if(item.CountryID=="{{$CountryID}}"){selected="selected";}
						else if(item.CountryName=="{{$CountryName}}"){selected="selected";}
						$('#Country').append('<option '+selected+' data-phone-code="'+item.PhoneCode+'" data-phone-lenth="'+item.PhoneLength+'" value="'+item.CountryID+'">'+item.CountryName+'</option>');
					}
					let PhoneLength=0;
					if($('#Country').val()!=""){
						GetStateName();
						let CallingCode=$('#Country option:selected').attr('data-phone-code');
							PhoneLength=$('#Country option:selected').attr('data-phone-lenth');
						$('#CallCode').html(' (+'+CallingCode+')')
					}else{
						$('#CallCode').html('');
					}
					if((PhoneLength=="")||(PhoneLength==undefined)){PhoneLength=0;}
					$('#MobileNumber').attr('data-length',PhoneLength)
				}
			});
			$('#Country').select2();
		}

		const GetStateName=async()=>{
			let CountryID=$('#Country').val();
			$('#State').select2('destroy');
			$('#State option').remove();
			$('#State').append('<option value="">Select a State</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/States",
				data:{CountryID:CountryID},
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$StateID="";
							$StateName="";
							if($isEdit==true){
								if(isset($EditData[0]->StateID)){
									$StateID=$EditData[0]->StateID;
								}
							}
						@endphp

						if(item.StateID=="{{$StateID}}"){selected="selected";}
						else if(item.StateName=="{{$StateName}}"){selected="selected";}
						$('#State').append('<option  '+selected+' value="'+item.StateID+'">'+item.StateName+'</option>');
					}
					if($('#State').val()!=""){
						GetCityName();
					
					}
				}
			});
			$('#State').select2();
		}
		const GetCityName=async()=>{
			let CountryID=$('#Country').val();
			let StateID=$('#State').val();
			$('#City').select2('destroy');
			$('#City option').remove();
			$('#City').append('<option value="">Select a City</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/City",
				data:{CountryID:CountryID,StateID:StateID},
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$CityID="";
							$City="";
							if($isEdit==true){
								if(isset($EditData[0]->CityID)){
									$CityID=$EditData[0]->CityID;
								}
							}
						@endphp
						if(item.CityID=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#City').append('<option '+selected+'  value="'+item.CityID+'">'+item.CityName+'</option>');
					}
				}
			});
			
			$('#City').select2();
		}
		const GetPinCode=async()=>{
		    let CityID=$('#City').val();
			$('#PinCode').select2('destroy');
			$('#PinCode option').remove();
			$('#PinCode').append('<option value="">Select a Postal Code</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/pincode",
				data:{CityID:CityID},
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						// console.log(item.PID)
						let selected="";
						@php
							$PostalCode="";
							// $PID="";
							if($isEdit==true){
								if(isset($EditData[0]->PostalCode)){
									$PostalCode=$EditData[0]->PostalCode;
								}
							}
						@endphp
						if(item.CityName=="{{$PostalCode}}"){selected="selected";}
						else if(item.PostalCode=="{{$PostalCode}}"){selected="selected";}
						$('#PinCode').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
				}
			});
			$('#PinCode').select2({tags:true});
		}
		$('#City').change(function(){
			GetPinCode();
		})
		$('#Country').change(function(){
			GetStateName();
			let PhoneLength=0;
			if($('#Country').val()!=""){
				let CallingCode=$('#Country option:selected').attr('data-phone-code');
				PhoneLength=$('#Country option:selected').attr('data-phone-lenth');
				$('#CallCode').html(' (+'+CallingCode+')')
			}else{
				$('#CallCode').html('');
			}
			if((PhoneLength=="")||(PhoneLength==undefined)){PhoneLength=0;}
			$('#MobileNumber').attr('data-length',PhoneLength)
		})
		$('#State').change(function(){
			GetCityName();
		})
		function ShowDivision(){
            $('#lstDivision').select2('destroy');
			$('#lstDivision option').remove();
			$('#lstDivision').append('<option value="">Select a Division</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/division",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					for(item of response){
						let selected="";
						@php
							$Division="";
							if($isEdit==true){
								if(isset($EditData[0]->Division)){
								    $Division=$EditData[0]->Division;
								}
							}
						@endphp
						if(item.DivisionName=="{{$Division}}"){selected="selected";}
						$('#lstDivision').append('<option '+selected+' value="'+item.DivisionName+'">'+item.DivisionName+'</option>');
					}
			    }
			});
			$('#lstDivision').select2();
        }
		@php
			if($isEdit==true){
        @endphp
        $(document).ready(function() {
            getRole();
            setTimeout(function() {
				$('#City').trigger('change');
				$('input[name="education_status"]').trigger('change');
                $('#UserRole').trigger('change');
                ShowDivision();
				// LoadinsCource();
				// LoadstdCource();
            },3000)
        });
        @php
			}
		@endphp
		$("#UserRole").change(function(){
            if($(this).val()=="UR2024-0000003"){
                $(".divDivision").toggle();
                ShowDivision();
            }else{
                $(".divDivision").hide();
            }
			if($(this).val()=="UR2024-0000004"){
				$(".divInstitute").toggle();
				LoadinsCource();
			}else{
				$('.divInstitute').hide();
			}

			if($(this).val()=="UR2024-0000005"){
				$(".divStudent").toggle();
				LoadstdCource();
			}else{
				$('.divStudent').hide();
			}

			if($(this).val()!="UR2024-0000001"){
				$('.divReport').show();
				LoadReportedto();
			}else{
				$('.divReport').hide();
			}
        });
		$('input[name="education_status"]').change(function() {
            var selectedValue = $('input[name="education_status"]:checked').val();
            
            if (selectedValue === 'Passedout') {
                $('#year_field').show(); // Show the year input field
            } else {
                $('#year_field').hide(); // Hide the year input field
                $('#passedout_year').val(''); // Clear the year input value
                $('#year_err').text(''); // Clear any previous error message
            }
        });
		// Function to validate latitude
		function validateLatitude(lat) {
			var latPattern = /^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/;
			return latPattern.test(lat);
		} 
		// Function to validate longitude
		function validateLongitude(lng) {
			var lngPattern = /^[-+]?(180(\.0+)?|((1[0-7]\d)|(\d{1,2}))(\.\d+)?)$/;
			return lngPattern.test(lng);
		}
		// Validate latitude on blur
		$('#txtlat').on('blur', function() {
			var latitude = $(this).val().trim();
			var errorSpan = $('#txtlat-err');
			if (!validateLatitude(latitude)) {
				errorSpan.text('Invalid latitude format');
			} else {
				errorSpan.text('');
			}
		});
		// Validate longitude on blur
		$('#txtlan').on('blur', function() {
			var longitude = $(this).val().trim();
			var errorSpan = $('#txtlan-err');
			if (!validateLongitude(longitude)) {
				errorSpan.text('Invalid longitude format');
			} else {
				errorSpan.text('');
			}
		});
        // Function to validate year input
		$('#passedout_year').on('input', function() {
			var input = $(this).val(); // Get the current value of the input field
			var sanitized = input.replace(/\D/g, ''); // Remove any non-numeric characters

			// Update the value of the input field with the sanitized value
			$(this).val(sanitized);

			// Validate the year format and range
			var year = sanitized;
			var currentYear = new Date().getFullYear();
			var minYear = currentYear - 10; // Allow years up to 10 years ago
			var isValidYear = /^\d{4}$/.test(year) && parseInt(year) >= 1900 && parseInt(year) <= currentYear;

			if (!isValidYear) {
				$('#year_err').text('Please enter a valid year (YYYY) and within the last 10 years');
			} else {
				$('#year_err').text('');
			}
		});
		// Validate Postal code 
		$('#txtPostalCode').on('input', function() {
			var postalCode = $(this).val().trim();
			var postalCodeRegex = /^[0-9]{6}$/; // Example: Accepts exactly 6 digits

			if (postalCode === "") {
				$('#txtPostalCode-err').html("Postal Code Required");
			} else if (!postalCode.match(/^\d+$/)) {
				$('#txtPostalCode-err').html("Postal Code must contain only numbers");
			} else if (!postalCodeRegex.test(postalCode)) {
				$('#txtPostalCode-err').html("Postal Code must be exactly 5 digits");
			} else {
				$('#txtPostalCode-err').html(""); // Clear error message if valid
			}
		});
		// Validate GST 
		$('#txtGst').on('input', function() {
			var gstNumber = $(this).val().trim();
			var gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[A-Z]{1}[0-9A-Z]{1}$/;

			if (gstNumber === "") {
				$('#txtGst-err').html("GST Number Required");
			} else if (!gstRegex.test(gstNumber)) {
				$('#txtGst-err').html("Invalid GST Number Format");
			} else {
				$('#txtGst-err').html(""); // Clear error message if valid
			}
		});
		// Validate Slot
		$('#txtStudentSlot').on('input', function() {
			// Get the current value of the input field
			var input = $(this).val();

			// Remove any non-numeric characters using a regular expression
			var sanitized = input.replace(/[^0-9]/g, '');

			// Update the value of the input field with the sanitized value
			$(this).val(sanitized);
		});
		function LoadinsCource(){
            $('#lstCource').select2('destroy');
			$('#lstCource option').remove();
			$('#lstCource').append('<option value="">Select a Cource</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Cource",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					console.log(response);
					@php
						$courseIds = [];
						if ($isEdit && isset($EditData[0]->insCourseID)) {
							$courseIds = explode(",", $EditData[0]->insCourseID);
						}
					@endphp
					// Pass the PHP array to a JavaScript variable
					var courseIds = @json($courseIds);

					// Function to verify if a given course ID is in the courseIds array
					function courseVerify(courseIdToCheck) {
						return courseIds.some(courseId => courseId.trim().replace(/'/g, '') === courseIdToCheck);
					}

					// Iterate over the response
					response.forEach(item => {
						let selected = courseVerify(item.Course_Id) ? "selected" : "";
						$('#lstCource').append(`<option value="${item.Course_Id}" ${selected}>${item.C_Name}</option>`);
					});					
			    }
			});
			$('#lstCource').select2();
        }
		function LoadstdCource(){
            $('#lststdCource').select2('destroy');
			$('#lststdCource option').remove();
			$('#lststdCource').append('<option value="">Select a Cource</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Cource",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					console.log(response);
					@php
						$courseIds = [];
						if ($isEdit && isset($EditData[0]->stdCourseID)) {
							$courseIds = explode(",", $EditData[0]->stdCourseID);
						}
					@endphp
					// Pass the PHP array to a JavaScript variable
					var courseIds = @json($courseIds);

					// Function to verify if a given course ID is in the courseIds array
					function courseVerify(courseIdToCheck) {
						return courseIds.some(courseId => courseId.trim().replace(/'/g, '') === courseIdToCheck);
					}

					// Iterate over the response
					response.forEach(item => {
						let selected = courseVerify(item.Course_Id) ? "selected" : "";
						$('#lststdCource').append(`<option value="${item.Course_Id}" ${selected}>${item.C_Name}</option>`);
					});
			    }
			});
			$('#lststdCource').select2();
        }
		function LoadReportedto(){
			let Roleid=$('#UserRole').val();
            $('#lstReportedto').select2('destroy');
			$('#lstReportedto option').remove();
			$('#lstReportedto').append('<option value="">Select a Officer</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Reportedto",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				data: {
                    RoleId: Roleid
                },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){btnReset($('#btnSubmit'));},
				success:function(response){
					console.log(response);
					for(item of response){
						let selected="";
						@php
							$Division="";
							if($isEdit==true){
								if(isset($EditData[0]->Division)){
								    $Division=$EditData[0]->ReportedBy;
								}
							}
						@endphp
						if(item.UserID =="{{$Division}}"){selected="selected";}
						$('#lstReportedto').append('<option '+selected+' value="'+item.UserID +'">'+item.name+'</option>');
					}
			    }
			});
			$('#lstReportedto').select2();
        }
		$('.userImage .dropify-clear').click(function(){
			$('#txtCImage').attr('data-default-file', '');
		}); 
		function getDivisionValue(){
		    if($("#lstDivision").is(":visible")){
        	    return $("#lstDivision").val();
        	}else{
        	    return null;
        	}
		}
		function getReportedtoValue() {
			if ($('#lstReportedto').is(':visible')) {
				return $("#lstReportedto").val();
			} else {
				return null;
			}
		}
		function getInstituteNameValue(){
			if ($('#txtInstituteName').is(':visible')) {
				return $("#txtInstituteName").val();
			} else {
				return null;
			}
		}
		function getLatValue(){
			if ($('#txtlat').is(':visible')) {
				return $("#txtlat").val();
			} else {
				return null;
			}
		}
		function getLonValue(){
			if ($('#txtlan').is(':visible')) {
				return $("#txtlan").val();
			} else {
				return null;
			}
		}
		function getAddressValue(){
			if ($('#insAddress').is(':visible')) {
				return $("#insAddress").val();
			} else {
				return null;
			}
		}
		function getPostalCodeValue(){
			if ($('#txtPostalCode').is(':visible')) {
				return $("#txtPostalCode").val();
			} else {
				return null;
			}
		}
		function getgstNumberValue(){
			if ($('#txtGst').is(':visible')) {
				return $("#txtGst").val();
			} else {
				return null;
			}
		}
		function getSlotValue(){
			if ($('#txtStudentSlot').is(':visible')) {
				return $("#txtStudentSlot").val();
			} else {
				return null;
			}
		}
		function getstdAddressValue(){
			if ($('#stdsAddress').is(':visible')) {
				return $("#stdsAddress").val();
			} else {
				return null;
			}
		}
		function getstdDegreeValue(){
			if ($('#txtDegree').is(':visible')) {
				return $("#txtDegree").val();
			} else {
				return null;
			}
		}
		function getstdPassingyearValue(){
			if ($('#passedout_year').is(':visible')) {
				return $("#passedout_year").val();
			} else {
				return null;
			}
		}
		appInit();
        $('#btnSubmit').click(function(){
            let status=formValidation();
            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this TAMS Member!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-primary",
                    confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
                    closeOnConfirm: false
                },function(){
                    swal.close();
                    btnLoading($('#btnSubmit'));
        
                    let formData=new FormData();
					console.log(formData);
                    formData.append('FirstName', $('#FirstName').val());
					formData.append('LastName', $('#LastName').val());
					formData.append('Password', $('#FirstPass').val());
					formData.append('CPassword', $('#ConfirmPass').val());
					formData.append('Email', $('#Email').val());
					formData.append('DOB', $('#DOB').val());
					formData.append('DOJ', $('#DOJ').val());
					formData.append('Gender', $('#Gender').val());
					formData.append('Country', "C2020-00000101");
					formData.append('State', $('#State').val());
					formData.append('City', $('#City').val());
					formData.append('Address', $('#Address').val());
					formData.append('PostalCodeID', $('#PinCode').val());
					formData.append('PostalCode', $('#PinCode option:selected').text());
					formData.append('MobileNumber', $('#MobileNumber').val());
					formData.append('ActiveStatus', $('#lstActiveStatus').val());
					formData.append('Division', getDivisionValue());
					formData.append('RoleID', $('#UserRole').val());
					formData.append('ReportedTo', getReportedtoValue());
					formData.append('InstituteName', getInstituteNameValue());
					formData.append('Lat', getLatValue());
					formData.append('Lon', getLonValue());
					formData.append('insAddress', getAddressValue());
					formData.append('insPostalCode', getPostalCodeValue());
					formData.append('insGst', getgstNumberValue());
					formData.append('insSlot', getSlotValue());
					formData.append('stdAddress', getstdAddressValue());
					formData.append('stdDegree', getstdDegreeValue());
					formData.append('stdPassingYear', getstdPassingyearValue());
					formData.append('insCource', insMltCource);
					formData.append('stdCource', stdMltCource);

                    if($('#txtCImage').val()!=""){
                        formData.append('ProfileImage', $('#txtCImage')[0].files[0]);
                    }

					@if($isEdit == true)
					formData.append('UserID',"{{$EditData[0]->UserID}}");
					var  submiturl = "{{ url('/') }}/users-and-permissions/users/edit/{{$EditData[0]->UserID}}";
           			 @else
						var  submiturl = "{{ url('/') }}/users-and-permissions/users/create";
           			 @endif
                    $.ajax({
                        type:"post",
                        url:submiturl,
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
                                        window.location.replace("{{url('/')}}/users-and-permissions/users/");
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
										$.each(response['errors'], function(KeyName, KeyValue) {
                                        var key = KeyName;
                                        if (key == "Email") {
                                            $('#Email-err').html(KeyValue);
                                        }else
										if (key == "MobileNumber") {
                                            $('#MobileNumber-err').html(KeyValue);
                                        }else if (key == "FirstName") {
                                            $('#FirstName-err').html(KeyValue);
                                        }else if (key == "LastName") {
                                            $('#LastName-err').html(KeyValue);
                                        }else if (key == "State") {
                                            $('#State-err').html(KeyValue);
                                        }else if (key == "Gender") {
                                            $('#Gender-err').html(KeyValue);
                                        }else if (key == "City") {
                                            $('#City-err').html(KeyValue);
                                        }else if (key == "Country") {
                                            $('#Country-err').html(KeyValue);
                                        }else if (key == "PostalCode") {
                                            $('#PinCode-err').html(KeyValue);
                                        }else if (key == "ReportTo") {
                                            $('#lstReportTo-err').html(KeyValue);
                                        }else if (key == "Password") {
                                            $('#Password-err').html(KeyValue);
                                        }
                                        if(key=="CImage"){$('#txtCImage-err').html(KeyValue);}
                                    });
                                }
                            }
                        }
                    });
                });
            }
        });
		// Institute Cource multiselect 
		$('#lstCource').change(function() {
            // Initialize Vselected variable
            insMltCource = '';
    
            // Get the selected options from #lstCource
            var selectedOptions = $('#lstCource option:selected');
    
            // Iterate through each selected option
            selectedOptions.each(function(index, option) {
                // Append the value of the selected option to insMltCource
                insMltCource += (insMltCource === "") ? "" : ",";
                // insMltCource += $(option).val();
                insMltCource += "'" + $(option).val() + "'";
                //  insMltCource += $(this).val(); 
                // Log the value of the selected option to the console
                console.log($(option).val());
            });
        });
		// Student Multiselect 
		$('#lststdCource').change(function() {
            // Initialize Vselected variable
            stdMltCource = '';
    
            // Get the selected options from #lststdCource
            var selectedOptions = $('#lststdCource option:selected');
    
            // Iterate through each selected option
            selectedOptions.each(function(index, option) {
                // Append the value of the selected option to stdMltCource
                stdMltCource += (stdMltCource === "") ? "" : ",";
                // stdMltCource += $(option).val();
                stdMltCource += "'" + $(option).val() + "'";
                //  stdMltCource += $(this).val(); 
                // Log the value of the selected option to the console
                console.log($(option).val());
            });
        });
    });
</script>


@endsection