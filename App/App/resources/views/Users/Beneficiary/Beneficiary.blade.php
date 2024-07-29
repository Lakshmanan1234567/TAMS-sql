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
                            <h5>Beneficiary</h5>
						</div>
						<div class="accordion" id="accordionExample">
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingOne">
									<button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									Basic Information
									</button>
								</h2>
								<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
									<div class="accordion-body">
									<div class="row">
										<div class="col-md-4"></div>
										<div class="col-md-4 text-center userImage">
											<input type="file" id="txtCImage" class="dropify" data-default-file="<?php if($isEdit==true){if($EditData[0]->txtCImage !=""){ echo url('/')."/".$EditData[0]->txtCImage;}}?>"  data-allowed-file-extensions="jpeg jpg png gif" />
											<span class="errors" id="txtCImage-err"></span>
										</div>
										<div class="col-md-4"></div>
								</div>
								<div class="row mt-10">
									<div class="col-md-6">
										<div class="form-group">
											<label for="FirstName">Beneficiary Name <span class="required">*</span></label>
										
											<input type="text" id="FirstName" class="form-control" placeholder="Beneficiary Name" 	value="<?php if($isEdit==true){ echo $EditData[0]->FirstName;} ?>">
											<span class="errors" id="FirstName-err"></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="LastName">Father Name <span class="required">*</span></label>
										
											<input type="text" id="LastName" class="form-control " placeholder="Father Name" value="<?php if($isEdit==true){ echo $EditData[0]->LastName;} ?>">
											<span class="errors" id="LastName-err"></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="MotherName">Mother Name <span class="required">*</span></label>
										
											<input type="text" id="MotherName" class="form-control " placeholder="Mother Name" value="<?php if($isEdit==true){ echo $EditData[0]->MotherName;} ?>">
											<span class="errors" id="MotherName-err"></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="DOB">Date Of Birth <span class="required">*</span></label>
											
											<input type="date" id="DOB" class="form-control date" placeholder="Date of Birth"  value="<?php if($isEdit==true){ echo $EditData[0]->DOB;} ?>" >
											<span class="errors" id="DOB-err"></span>
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
											<label for="religion">Religion <span class="required">*</span></label>
											<input type="text" id="religion" class="form-control" placeholder="Religion"  value="<?php if($isEdit==true){ echo $EditData[0]->religion;} ?>">
											<span class="errors" id="religion-err"></span>
										</div>
									</div>
										<div class="col-md-6">
												<label for="Category">Category</label>
											<select class="form-control" id="lstCategory">
												<option value="">Select the Category</option>
												<option value="SC" @if($isEdit==true) @if($EditData[0]->lstCategory=="SC") selected @endif @endif >SC</option>
												<option value="ST" @if($isEdit==true) @if($EditData[0]->lstCategory=="ST") selected @endif @endif>ST</option>
											</select>
											<span class="errors" id="lstCategory-err"></span>
										</div>
										<div class="col-md-6">
												<label for="MaritalStatus">Marital Status</label>
											<select class="form-control" id="lstMaritalStatus">
												<option value="">Select the Category</option>
												<option value="Married" @if($isEdit==true) @if($EditData[0]->lstMaritalStatus=="Married") selected @endif @endif >Married</option>
												<option value="Widow" @if($isEdit==true) @if($EditData[0]->lstMaritalStatus=="Widow") selected @endif @endif>Widow</option>
												<option value="Widower" @if($isEdit==true) @if($EditData[0]->lstMaritalStatus=="Widower") selected @endif @endif >Widower</option>
												<option value="Divorced" @if($isEdit==true) @if($EditData[0]->lstMaritalStatus=="Divorced") selected @endif @endif>Divorced</option>

											</select>
											<span class="errors" id="lstMaritalStatus-err"></span>

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
										<div class="col-md-6">
											<div class="form-group">
												<label>Person with Disability:</label><br>
												<input type="radio" id="disabilityYes" name="disability" value="yes">
												<label for="disabilityYes">Yes</label><br>

												<input type="radio" id="disabilityNo" name="disability" value="no">
												<label for="disabilityNo">No</label><br><br>

												<span class="errors" id="disability-err"></span>


												<div id="disabilityType" style="display: none;">
													<label for="disabilityType">Type of Disability:</label><br>
													<select id="disabilityTypeSelect" class="form-select" name="disabilityType">
														<option value="">select Disability</option>	
														<option value="physically">Physically Challenged</option>
														<option value="mentally">Mentally Challenged</option>
													</select>
													<span class="errors" id="disabilityType-err"></span>
												</div>

											</div>
										</div>
										<div class="col-md-6">
											<div id="disabilitydiseases" class="form-group" name="disabilitydiseases" onsubmit="return validateForm()">
												<label>Is any member suffering from:</label><br>
												<input type="checkbox" id="leprosy" name="leprosy" value="leprosy">
												<label for="leprosy">Leprosy</label><br>

												<input type="checkbox" id="cancer" name="cancer" value="cancer">
												<label for="cancer">Cancer</label><br>

												<input type="checkbox" id="hivAids" name="hivAids" value="hivAids">
												<label for="hivAids">HIV/AIDS</label><br>

												<input type="checkbox" id="None" name="None" value="None">
												<label for="None">None</label><br>

												<span class="errors" id="disabilitydiseases-err"></span>

											</div>

										</div>
										
										<div class="col-md-6">
												<label for="">Active Status</label>
											<select class="form-control" id="lstActiveStatus">
												<option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
												<option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
											</select>
										</div>	
																			
								</div>
								
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingTwo">
									<button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Address Details
									</button>
								</h2>
								<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="Address">Address1 <span class="required">*</span></label>
													<textarea class="form-control" placeholder="Address" id="Address1" name="Address" rows="2" ><?php if($isEdit==true){ echo $EditData[0]->Address1;} ?></textarea>
													<span class="errors" id="Address1-err"></span>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label for="Address">Address2 <span class="required">*</span></label>
													<textarea class="form-control" placeholder="Address" id="Address2" name="Address" rows="2" ><?php if($isEdit==true){ echo $EditData[0]->Address2;} ?></textarea>
													<span class="errors" id="Address2-err"></span>
												</div>
											</div>
											<!--<div class="col-md-6">-->
											<!--	<div class="form-group">-->
											<!--		<label for="Country">Country <span class="required">*</span></label>-->
											<!--		<select class="form-control select2" id="Country">-->
											<!--			<option value="">Select a Country</option>-->
											<!--		</select>-->
											<!--		<span class="errors" id="Country-err"></span>-->
											<!--	</div>-->
											<!--</div>-->
											<!--<div class="col-md-6">-->
											<!--	<div class="form-group">-->
											<!--		<label for="State">State <span class="required">*</span></label>-->
											<!--		<select class="form-control select2" id="State">-->
											<!--			<option value="">Select a State</option>-->
											<!--		</select>-->
											<!--		<span class="errors" id="State-err"></span>-->
											<!--	</div>-->
											<!--</div>	-->
											<div class="col-md-6">
												<div class="form-group1">
													<label for="City">City <span class="required">*</span></label><br>
													<select class="form-control select2" id="City">
														<option value="">Select a City</option>
													</select>
													<span class="errors" id="City-err"></span>
												</div>
											</div>
											
											
											<div class="col-md-6">
												<div class="form-group1">
													<label for="Taluka">Taluka <span class="required">*</span></label><br>
													<select class="form-control select2" id="Taluka">
														<option value="">Select a Taluka </option>
													</select>
													<span class="errors" id="Taluka-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="Block">Block <span class="required">*</span></label><br>
													<select class="form-control select2" id="Block">
														<option value="">Select a Block </option>
													</select>
													<span class="errors" id="Block-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="Village">Village <span class="required">*</span></label><br>
													<select class="form-control select2" id="Village">
														<option value="">Select a Village </option>
													</select>
													<span class="errors" id="Village-err"></span>
												</div>
											</div>	
											<div class="col-md-6">
												<div class="form-group">
													<label for="Panchayat ">Panchayat  <span class="required">*</span></label><br>
													<select class="form-control select2" id="Panchayat">
														<option value="">Select a Panchayat </option>
													</select>
													<span class="errors" id="Panchayat-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="PinCode">Postal Code <span class="required">*</span></label><br>
													<select class="form-control select2Tag" id="PinCode">
														<option value="">Select a Postal Code</option>
													</select>
													<span class="errors" id="PinCode-err"></span>
												</div>
											</div>
											
											
											<div class="col-md-6">
												<div class="form-group">
													<label for="MLA">MLA <span class="required">*</span></label><br>
													<select class="form-control select2" id="MLA">
														<option value="">Select a MLA </option>
													</select>
													<span class="errors" id="MLA-err"></span>
												</div>
											</div>	
											<div class="col-md-6">
												<div class="form-group">
													<label for="MP">MP <span class="required">*</span></label><br>
													<select class="form-control select2" id="MP">
														<option value="">Select a MP </option>
													</select>
													<span class="errors" id="MP-err"></span>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>

		<div class="accordion-item d-none">
								<h2 class="accordion-header" id="headingThree">
									<button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									Family Details
									</button>
								</h2>
								<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<div class="row clonefamilymeber">
											<div class="col-md-6">
											<div class="form-group">
												<label for="familyname"> Name <span class="required">*</span></label>
												<input type="text" id="familyname" class="form-control" placeholder="Your Name" 	value="<?php if($isEdit==true){ echo $EditData[0]->Address1;} ?>">
												<span class="errors" id="familyname-err"></span>
											</div>
											</div>
											<div class="col-md-6">
											<div class="form-group">
												<label for="DOB">Date Of Birth <span class="required">*</span></label>
												<input type="date" id="dob" class="form-control date" placeholder="Date of Birth"  value="<?php if($isEdit==true){ echo $EditData[0]->Address1;} ?>" >
												<span class="errors" id="dob-err"></span>
											</div>
											</div>
											<div class="col-md-6">
											<div class="form-group">
												<label for="gender">Gender <span class="required">*</span></label>
												<select id="gender1" class="form-control">
													<option value="">Select a Gender</option>
													<option value="male">Male</option>
													<option value="female">Female</option>
													<option value="other">Other</option>
												</select>
												<span class="errors" id="gender1-err"></span>

											</div>
											</div>
											<div class="col-md-6">
											<div class="form-group">
												<label for="age"> Age <span class="required">*</span></label>
												<input type="text" id="age1" class="form-control" placeholder="Your Age" 	value="<?php if($isEdit==true){ echo $EditData[0]->Address1;} ?>">
												<span class="errors" id="age1-err"></span>
											</div>
											</div>
											<div class="col-md-6">
											<div class="form-group">
												<label for="relation"> Relationship <span class="required">*</span></label>
												<input type="text" id="relation" class="form-control" placeholder="Your Relation" 	value="<?php if($isEdit==true){ echo $EditData[0]->Address1;} ?>">
												<span class="errors" id="relation-err"></span>
											</div>
											</div>
											<div class="col-md-6">
											<div class="form-group">
												<label for="education"> Education <span class="required">*</span></label>
												<input type="text" id="education" class="form-control" placeholder="Education" 	value="<?php if($isEdit==true){ echo $EditData[0]->Address1;} ?>">
												<span class="errors" id="education-err"></span>
											</div>
											</div>
											<div class="col-md-6">
											<div class="form-group">
												<label for="occupation"> Occupation <span class="required">*</span></label>
												<input type="text" id="loccupation" class="form-control" placeholder="Your Occupation" 	value="<?php if($isEdit==true){ echo $EditData[0]->Address1;} ?>">
												<span class="errors" id="loccupation-err"></span>
											</div>
											</div>
											<div class="col-md-2">
											<div class="form-group">
												<button type="button" id="remove-btn" class="btn btn-sm btn-danger">Remove</button>
											</div>
											</div>
											<div class="col-md-2">
											<div class="form-group">
												<button type="button" id="addBtn" class="btn btn-fill btn-success  btn-sm">Add More</button>
											</div>
											</div>
										</div>
										<div class="addclonefamilymeber"></div>
									</div>
								</div>
								</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingFour">
									<button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
										Identity Details
									</button>
								</h2>
								<div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
									<div class="accordion-body">
									<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="AadhaarNumber">Aadhaar Number <span class="required">*</span></label>
												
													<input type="text" id="AadhaarNumber" class="form-control" placeholder="Aadhaar Number" 	value="<?php if($isEdit==true){ echo $EditData[0]->AadhaarNumber;} ?>">
													<span class="errors" id="AadhaarNumber-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="familycardnumber">Family Card Number <span class="required">*</span></label>
												
													<input type="text" id="familycardnumber" class="form-control " placeholder="Family Card Numbe" value="<?php if($isEdit==true){ echo $EditData[0]->familycardnumber;} ?>">
													<span class="errors" id="familycardnumber-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="mgnregsnumber">MGNREGS Job card Number <span class="required">*</span></label>
												
													<input type="text" id="mgnregsnumber" class="form-control " placeholder="MGNREGS Job card Number" value="<?php if($isEdit==true){ echo $EditData[0]->mgnregsnumber;} ?>">
													<span class="errors" id="mgnregsnumber-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="Occupation">Occupation <span class="required">*</span></label>
												
													<input type="text" id="Occupation" class="form-control " placeholder="Occupation" value="<?php if($isEdit==true){ echo $EditData[0]->Occupation;} ?>">
													<span class="errors" id="Occupation-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="AnnualIncome">Annual Income  <span class="required">*</span></label>
												
													<input type="text" id="AnnualIncome" class="form-control " placeholder="AnnualIncome" value="<?php if($isEdit==true){ echo $EditData[0]->AnnualIncome;} ?>">
													<span class="errors" id="AnnualIncome-err"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingFive">
									<button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
										Land Details
									</button>
								</h2>
								<div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<div class="row">
											<div class="col-md-6">
													<div class="form-group">
														<label for="ownHouseSite">Do you own a house site?</label><br>
														<input type="radio" id="ownHouseSiteYes" name="ownHouseSite" value="yes">
														<label for="ownHouseSiteYes">Yes</label><br>
														<input type="radio" id="ownHouseSiteNo" name="ownHouseSite" value="no">
														<label for="ownHouseSiteNo">No</label><br><br>
														<span class="errors" id="ownHouseSite-err"></span>

														
														<div id="siteExtentField" style="display: none;">
															<label for="siteExtent">Extent of site:</label><br>
															<input type="text" class="form-control" id="siteExtent" name="siteExtent">
														</div>
														<span class="errors" id="siteExtentField-err"></span>

													</div>
											</div>
											<div class="col-md-6">
													<div class="form-group">
														<label for="ownAgricultureLand">Whether you own agriculture land?</label><br>
														<input type="radio" id="ownAgricultureLandYes" name="ownAgricultureLand" value="yes">
														<label for="ownAgricultureLandYes">Yes</label><br>
														<input type="radio" id="ownAgricultureLandNo" name="ownAgricultureLand" value="no">
														<label for="ownAgricultureLandNo">No</label><br><br>
														<span class="errors" id="ownAgricultureLand-err"></span>


														<div id="landExtentField" style="display: none;">
															<label for="landExtent">Extent of land:</label><br>
															<input type="text" class="form-control" id="landExtent" name="landExtent">
														</div>
														<span class="errors" id="landExtentField-err"></span>

													</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="lDistrict">District <span class="required">*</span></label><br>
													<select class="form-select select2" id="lDistrict">
														<option value="">Select a District</option>
													</select>
													<span class="errors" id="lDistrict-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="lTaluka">Taluka <span class="required">*</span></label><br>
													<select class="form-control select2" id="lTaluka">
														<option value="">Select a Taluka</option>
													</select>
													<span class="errors" id="lTaluka-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="lvillage">village <span class="required">*</span></label><br>
													<select class="form-control select2" id="lvillage">
														<option value="">Select a village</option>
													</select>
													<span class="errors" id="lvillage-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="surveynumber">survey number <span class="required">*</span></label>
													<input type="text" id="surveynumber" class="form-control" placeholder="surveynumber"  value="<?php if($isEdit==true){ echo $EditData[0]->surveynumber;} ?>">
													<span class="errors" id="surveynumber-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="subdivnumber">Sub Divison number <span class="required">*</span></label>
													<input type="text" id="subdivnumber" class="form-control" placeholder="Sub Divison number"  value="<?php if($isEdit==true){ echo $EditData[0]->subdivnumber;} ?>">
													<span class="errors" id="subdivnumber-err"></span>
												</div>
											</div>
										</div>	
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingSix">
									<button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
										Bank Details
									</button>
								</h2>
								<div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="AccNumber">Acc Number <span class="required">*</span></label>
												
													<input type="text" id="AccNumber" class="form-control" placeholder="Account Number" 	value="<?php if($isEdit==true){ echo $EditData[0]->AccNumber;} ?>">
													<span class="errors" id="AccNumber-err"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="ifsccode">IFSC code <span class="required">*</span></label>
												
													<input type="text" id="ifsccode" class="form-control " placeholder="IFSC code" value="<?php if($isEdit==true){ echo $EditData[0]->ifsccode;} ?>">
													<span class="errors" id="ifsccode-err"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body p-20">
								
							<input type="hidden" id="IsEditval" class="form-control"   value="{{$isEdit}}">
							
							<div class="row">
								<div class="col-sm-12 text-right">
									@if($crud['view']==true)
									<a href="{{url('/')}}/users-and-permissions/Beneficiary/" class="btn btn-sm  btn-outline-light btn-air-success" id="btnCancel">Back</a>
									@endif
									
									@if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
										<button class="btn  btn-sm btn-outline-primary btn-air-success" id="btnSubmit">@if($isEdit==true) Update @else Save @endif</button>
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
		$('input[name="disability"]').change(function(){
			if($(this).val() == "yes"){
				$('#disabilityType').show();
			}else{
				$('#disabilityType').hide();
			}
		});
		document.querySelectorAll('input[name="ownHouseSite"]').forEach(function(el) {
        el.addEventListener('change', function() {
            var siteExtentField = document.getElementById('siteExtentField');
            if (this.value === 'yes') {
                siteExtentField.style.display = 'block';
            } else {
                siteExtentField.style.display = 'none';
            }
        });
    });
	document.querySelectorAll('input[name="ownAgricultureLand"]').forEach(function(el) {
        el.addEventListener('change', function() {
            var landExtentField = document.getElementById('landExtentField');
            if (this.value === 'yes') {
                landExtentField.style.display = 'block';
            } else {
                landExtentField.style.display = 'none';
            }
        });
    });
	$('.clonefamilymeber:first .form-group #remove-btn').hide();
	
	$('#addBtn').click(function() {
	var accordionItems = $('.clonefamilymeber');
	var lastAccordionItem = accordionItems.last();

	// Clone the last accordion item and append it to the accordion
	lastAccordionItem.clone().appendTo('.addclonefamilymeber');

	// Show the remove button for the newly added family member
	lastAccordionItem.next().find('.form-group #remove-btn').show();

	// Hide the add more button for the newly added family member
	lastAccordionItem.next().find('.form-group #addBtn').hide();
});
$('.addclonefamilymeber').on('click', '.form-group #remove-btn', function() {
	$(this).closest('.clonefamilymeber').remove();
});
		var isEdit = $('#IsEditval').val();

if(isEdit == true){
					$('.EditPassword').prop("hidden", true);
					$('.EditCPassword').prop("hidden", true);
				}

const formValidation=()=>{

			$('.errors').html('');
            let status=true;

let FirstName = $('#FirstName').val();
let LastName = $('#LastName').val();
let MotherName = $('#MotherName').val();
// let ConfirmPass = $('#ConfirmPass').val();
let DOB = $('#DOB').val();
// let DOJ = $('#DOJ').val();
let Gender = $('#Gender').val();
let religion = $('#religion').val();
let lstCategory = $('#lstCategory').val();
let lstMaritalStatus = $('#lstMaritalStatus').val();
let Email = $('#Email').val();
let MobileNumber = $('#MobileNumber').val();
let disability = $('input[name="disability"]:checked').val();
let disabilityType = $('#disabilityTypeSelect').val();
let lstActiveStatus = $('#lstActiveStatus').val();
let disabilitydiseases = $('#disabilitydiseases').val();

//Address Details

let Address1 = $('#Address1').val();
let Address2 = $('#Address2').val();
let Country = $('#Country').val();
// let ConfirmPass = $('#ConfirmPass').val();
let State = $('#State').val();
// let DOJ = $('#DOJ').val();
let City = $('#City').val();
let PinCode = $('#PinCode').val();
let Block = $('#Block').val();
let Taluka = $('#Taluka').val();
let Village = $('#Village').val();
let Panchayat = $('#Panchayat').val();
let MLA =$('#MLA').val();
let MP=$('#MP').val();

 //Family Details
// let familyname = $('#familyname').val();
// let dob = $('#dob').val();
// var selectedGender = $("#gender1").val();
// let age1 = $('#age1').val();
// let relation = $('#relation').val();
// let education = $('#education').val();
// let loccupation = $('#loccupation').val();

//Identify Details
let AadhaarNumber = $('#AadhaarNumber').val();
let familycardnumber = $('#familycardnumber').val();
let mgnregsnumber = $('#mgnregsnumber').val();
let Occupation = $('#Occupation').val();
let AnnualIncome = $('#AnnualIncome').val();

//Bank Details

let AccNumber = $('#AccNumber').val();
let ifsccode = $('#ifsccode').val();



var leprosyChecked = $("#leprosy").prop("checked");
var cancerChecked = $("#cancer").prop("checked");
var hivAidsChecked = $("#hivAids").prop("checked");
var noneChecked = $("#None").prop("checked");
 
//Land Details 

var ownHouseSiteYesChecked = $("#ownHouseSiteYes").prop("checked");
var ownHouseSiteNoChecked = $("#ownHouseSiteNo").prop("checked");
let siteExtentFieldSite = $('#siteExtent').val();


var ownAgricultureLandYesChecked = $("#ownAgricultureLandYes").prop("checked");
var ownAgricultureLandNoChecked = $("#ownAgricultureLandNo").prop("checked");
let landExtentFieldLand = $('#landExtent').val();

let lDistrict = $('#lDistrict').val();
let lvillage = $('#lvillage').val();
let surveynumber = $('#surveynumber').val();
let lTaluka = $('#lTaluka').val();
let subdivnumber = $('#subdivnumber').val();
 function setError(field, message) {
            $('#' + field + '-err').html(message);
            errors[field] = message;
            status = false; // Set status to false when there's an error
        }

        // Function to clear error messages
        function clearError(field) {
            $('#' + field + '-err').html('');
            delete errors[field];
        }



	if (FirstName == "") {
		$('#FirstName-err').html('First Name is required');status = false;
	} else if (FirstName.length > 50) {
		$('#FirstName-err').html('First Name may not be greater than 50 characters');status = false;
	}else if (FirstName.length <3) {
		$('#FirstName-err').html('First Name may not be leesthen than 3 characters');status = false;
	}
	if (MotherName == "") {
		$('#MotherName-err').html(' Mother Name is required');status = false;
	} else if (MotherName.length > 50) {
		$('#MotherName-err').html('Mother Name may not be greater than 50 characters');status = false;
	}else if (LastName.length < 3) {
		$('#MotherName-err').html('Mother Name may not be leesthen than 3 characters');status = false;
	}
	if (LastName == "") {
		$('#LastName-err').html('Last Name is required');status = false;
	} else if (LastName.length > 50) {
		$('#LastName-err').html('Last Name may not be greater than 50 characters');status = false;
	}else if (LastName.length < 3) {
		$('#LastName-err').html('Last Name may not be leesthen than 3 characters');status = false;
	}
	if (DOB == "") {
		$('#DOB-err').html('Date of Birth  is required');status = false;
	}
	if (Gender == "") {
		$('#Gender-err').html('plese select Gender');status = false;
	}if (religion == "") {
		$('#religion-err').html('This Field   is required');status = false;
	}if (lstCategory == "") {
		$('#lstCategory-err').html('This Field   is required');status = false;
	}
	if (lstMaritalStatus == "") {
		$('#lstMaritalStatus-err').html('please select the Field');status = false;
	}
	if (Email == "") {
		$('#Email-err').html('E-Mail  is required');status = false;
	}
	var mailformat = /^\w+([\.-]?\w+)@\w+([\.-]?\w+)(\.\w{2,3})+$/;
		if(!Email.match(mailformat))
		{
			$('#Email-err').html('Valid E-Mail  is required');status = false;
		}
	if (MobileNumber == "") {
		$('#MobileNumber-err').html('Mobile Number  is required');status = false;
	}
	if (disability === undefined) {
            $('#disability-err').text('Please select if person has a disability');
            status = false;
        }
		
        if (disability === "yes" && disabilityType === "") {
            $('#disabilityType-err').text('Please select the type of disability');
            status = false;
        }
		
	if (lstActiveStatus == "") {
		$('#lstActiveStatus-err').html('Please Select any one');status = false;
	}
	if (!leprosyChecked && !cancerChecked && !hivAidsChecked && !noneChecked) {
    $('#disabilitydiseases-err').html('Please Select any one');
    status = false;
}

	if (Address1 == "") {
		$('#Address1-err').html('plese Enter Your Address');status = false;
	}if (Address2 == "") {
		$('#Address2-err').html('plese Enter Your Address');status = false;
	}if (Country == "") {
		$('#Country-err').html('This Field is required');status = false;
	}
	if (State == "") {
		$('#State-err').html('please select the Field');status = false;
	}if (City == "") {
		$('#City-err').html('plese select the Field');status = false;
	}if (PinCode == "") {
		$('#PinCode-err').html('This Field   is required');status = false;
	}if (Block == "") {
		$('#Block-err').html('This Field   is required');status = false;
	}
	if (Taluka == "") {
		$('#Taluka-err').html('This Field is require');status = false;
	}if (Village == "") {
		$('#Village-err').html('This Field is require');status = false;
	}if (Panchayat == "") {
		$('#Panchayat-err').html('This Field   is required');status = false;
	}if (MLA == "") {
		$('#MLA-err').html('This Field   is required');status = false;
	}
	if (MP == "") {
		$('#MP-err').html('This Field is required');status = false;
	}
	if (AadhaarNumber == "") {
		$('#AadhaarNumber-err').html('This Field   is required');status = false;
	}if (familycardnumber == "") {
		$('#familycardnumber-err').html('This Field   is required');status = false;
	}
	if (mgnregsnumber == "") {
		$('#mgnregsnumber-err').html('This Field is require');status = false;
	}if (Occupation == "") {
		$('#Occupation-err').html('This Field is require');status = false;
	}if (AnnualIncome == "") {
		$('#AnnualIncome-err').html('This Field   is required');status = false;
	}
	if (AccNumber == "") {
		$('#AccNumber-err').html('This Field is require');status = false;
	}if (ifsccode == "") {
		$('#ifsccode-err').html('This Field   is required');status = false;
	}
// 	if (familyname == "") {
// 		$('#familyname-err').html('This Field   is required');status = false;
// 	}if (dob == "") {
// 		$('#dob-err').html('This Field   is required');status = false;
// 	}if (age1 == "") {
// 		$('#age1-err').html('This Field   is required');status = false;
// 	}if (relation == "") {
// 		$('#relation-err').html('This Field   is required');status = false;
// 	}if (education == "") {
// 		$('#education-err').html('This Field   is required');status = false;
// 	}if (loccupation == "") {
// 		$('#loccupation-err').html('This Field   is required');status = false;
// 	}   
// 	if (selectedGender === "") {
//             $("#gender1-err").html("Please select a gender.");status = false;
//         } 
if (!ownHouseSiteYesChecked && !ownHouseSiteNoChecked) {
    $('#ownHouseSite-err').html('Please Select any one');
    status = false;
}

if (!ownAgricultureLandYesChecked && !ownAgricultureLandNoChecked) {
    $('#ownAgricultureLand-err').html('Please Select any one');
    status = false;
}

if (ownHouseSiteYesChecked && siteExtentFieldSite.trim() === "") {
    $('#siteExtentField-err').html('Please Type the Extent Site');
    status = false;
}

if (ownAgricultureLandYesChecked && landExtentFieldLand.trim() === "") {
    $('#landExtentField-err').html('Please Type the Extent Site');
    status = false;
}
    if (lDistrict == "") {
		$('#lDistrict-err').html('This Field   is required');status = false;
	}
	if (lvillage == "") {
		$('#lvillage-err').html('This Field   is required');status = false;
	}
	if (surveynumber == "") {
		$('#surveynumber-err').html('This Field   is required');status = false;
	}
	if (lTaluka == "") {
		$('#lTaluka-err').html('This Field   is required');status = false;
	}
	if (subdivnumber == "") {
		$('#subdivnumber-err').html('This Field   is required');status = false;
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
			
			
			
// 			getCountry();
            GetCityName();
			getGender();
// 			getRole();
			
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
								$GenderID=$EditData[0]->Gender;
							}
						@endphp
						if(item.Gender=="{{$GenderID}}"){selected="selected";}
						$('#Gender').append('<option '+selected+'  value="'+item.Gender+'">'+item.Gender+'</option>');
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
								$CountryID=$EditData[0]->Country;
							}
						@endphp
						if(item.CountryName=="{{$CountryID}}"){selected="selected";}
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
								$StateID=$EditData[0]->State;
							}else{
								// $StateName=$Location['State'];
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
// 		GetCityName();
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
								$CityID=$EditData[0]->District;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#City').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#City').val()!=""){
						GetTalukaName();
			GetBlockName();
			GetVillageName();
			GetPanName();
			GetPinCode();
			GetMLA();
			GetMB();
			GetDistrictName();
					
					}
				}
			});
			
			$('#City').select2();
		}
		const GetTalukaName=async()=>{
		    let CityID=$('#City').val();
			$('#Taluka').select2('destroy');
			$('#Taluka option').remove();
			$('#Taluka').append('<option value="">Select a Taluka</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Taluka",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->Taluka;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#Taluka').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#Taluka').val()!=""){
				// 		GetTalukaName();
					
					}
				}
			});
			
			$('#Taluka').select2();
		}
		const GetLTalukaName=async()=>{
		    let CityID=$('#City').val();
			$('#lTaluka').select2('destroy');
			$('#lTaluka option').remove();
			$('#lTaluka').append('<option value="">Select a Taluka</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Taluka",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->lTaluka;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#lTaluka').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#lTaluka').val()!=""){
				// 		GetTalukaName();
					
					}
				}
			});
			
			$('#Taluka').select2();
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
								$PostalCode=$EditData[0]->PostalCode;
								// $PID=$EditData[0]->PID;
							}
						@endphp
						
						if(item.PostalCode=="{{$PostalCode}}"){selected="selected";}
						else if(item.PostalCode=="{{$PostalCode}}"){selected="selected";}
						$('#PinCode').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
				}
			});
			$('#PinCode').select2({tags:true});
		}
// 		GetPinCode();
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
		$('#lDistrict').change(function(){
			GetLTalukaName();
			GetLVillageName();
		})
		const GetLVillageName=async()=>{
		    let CityID=$('#City').val();
			$('#lvillage').select2('destroy');
			$('#lvillage option').remove();
			$('#lvillage').append('<option value="">Select a Village</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Village",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->Village;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#lvillage').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#lvillage').val()!=""){
				// 		GetLVillageName();
					
					}
				}
			});
			
			$('#Block').select2();
		}
		
        $('#City').change(function(){
			GetTalukaName();
			GetBlockName();
			GetVillageName();
			GetPanName();
			GetPinCode();
			GetMLA();
			GetMB();
			GetDistrictName();
		})
		const GetDistrictName=async()=>{
			let CountryID=$('#Country').val();
			let StateID=$('#State').val();
			$('#lDistrict').select2('destroy');
			$('#lDistrict option').remove();
			$('#lDistrict').append('<option value="">Select a District</option>');
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
								$CityID=$EditData[0]->lDistrict;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
				// 		$('#City').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
						$('#lDistrict').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#lDistrict').val()!=""){
						GetLTalukaName();
						GetLVillageName();
					
					}
				}
			});
			
			$('#City').select2();
		}
		const GetBlockName=async()=>{
		    let CityID=$('#City').val();
			$('#Block').select2('destroy');
			$('#Block option').remove();
			$('#Block').append('<option value="">Select a Block</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Block",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->Block;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$CityID}}"){selected="selected";}
						$('#Block').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#Block').val()!=""){
				// 		GetBlockName();
					
					}
				}
			});
			
			$('#Block').select2();
		}
		const GetVillageName=async()=>{
		    let CityID=$('#City').val();
			$('#Village').select2('destroy');
			$('#Village option').remove();
			$('#Village').append('<option value="">Select a Village</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Village",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->Village;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#Village').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#Village').val()!=""){
				// 		GetVillageName();
					
					}
				}
			});
			
			$('#Block').select2();
		}
		const GetPanName=async()=>{
		    let CityID=$('#City').val();
			$('#Panchayat').select2('destroy');
			$('#Panchayat option').remove();
			$('#Panchayat').append('<option value="">Select a Village</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Village",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->Panchayat;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#Panchayat').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#Panchayat').val()!=""){
				// 		GetVillageName();
					
					}
				}
			});
			
			$('#Block').select2();
		}
		const GetMLA=async()=>{
		    let CityID=$('#City').val();
			$('#MLA').select2('destroy');
			$('#MLA option').remove();
			$('#MLA').append('<option value="">Select a MLA</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Village",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->MLA;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#MLA').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#MLA').val()!=""){
				// 		GetVillageName();
					
					}
				}
			});
			
			$('#Block').select2();
		}
		const GetMB=async()=>{
		    let CityID=$('#City').val();
			$('#MP').select2('destroy');
			$('#MP option').remove();
			$('#MP').append('<option value="">Select a MP</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/Get/Village",
				data:{CityID:CityID},
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
								$CityID=$EditData[0]->MP;
							}
						@endphp
						if(item.CityName=="{{$CityID}}"){selected="selected";}
						else if(item.CityName=="{{$City}}"){selected="selected";}
						$('#MP').append('<option '+selected+'  value="'+item.CityName+'">'+item.CityName+'</option>');
					}
					if($('#MP').val()!=""){
				// 		GetVillageName();
					
					}
				}
			});
			
			$('#Block').select2();
		}
		$('.userImage .dropify-clear').click(function(){
			$('#txtCImage').attr('data-default-file', '');
		})
		appInit();
        $('#btnSubmit').click(function(){
            
            let status=formValidation();
            if(status){
			
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this Beneficiary!",
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
				formData.append('MotherName', $('#MotherName').val());
				formData.append('DOB', $('#DOB').val());
				formData.append('Gender', $('#Gender').val());
				formData.append('Religion', $('#religion').val());
				formData.append('lstCategory', $('#lstCategory').val());
				formData.append('lstMaritalStatus', $('#lstMaritalStatus').val());
				formData.append('Email', $('#Email').val());
				formData.append('MobileNumber', $('#MobileNumber').val());
				formData.append('leprosy', $('#leprosy').val());
				formData.append('Address1', $('#Address1').val());
				formData.append('Address2', $('#Address2').val());
				formData.append('City', $('#City').val());
				formData.append('Taluka', $('#Taluka').val());
				formData.append('Block', $('#Block').val());
				formData.append('Village', $('#Village').val());
				formData.append('Panchayat', $('#Panchayat').val());
				formData.append('PinCode', $('#PinCode').val());
				formData.append('MLA', $('#MLA').val());
				formData.append('MP', $('#MP').val());
				formData.append('AadhaarNumber', $('#AadhaarNumber').val());
				formData.append('familycardnumber', $('#familycardnumber').val());
				formData.append('mgnregsnumber', $('#mgnregsnumber').val());
				formData.append('Occupation', $('#Occupation').val());
				formData.append('AnnualIncome', $('#AnnualIncome').val());
				formData.append('lDistrict', $('#lDistrict').val());
				formData.append('lTaluka', $('#lTaluka').val());
				formData.append('lvillage', $('#lvillage').val());
				formData.append('surveynumber', $('#surveynumber').val());
				formData.append('subdivnumber', $('#subdivnumber').val());
				formData.append('AccNumber', $('#AccNumber').val());
				formData.append('ifsccode', $('#ifsccode').val());
				formData.append('ActiveStatus', $('#lstActiveStatus').val());



				formData.append('ownHouseSite',$('input[name="ownHouseSite"]:checked').val());		
				formData.append('siteExtent', $('#siteExtent').val());
				formData.append('ownAgricultureLand', $('input[name="ownAgricultureLand"]:checked').val());		
				formData.append('landExtent', $('#landExtent').val());

				
				formData.append('disability', $('input[name="disability"]:checked').val());					
				formData.append('disabilityType', $('#disabilityTypeSelect').val());
				formData.append('disabilitydiseases', $('input[name="disabilitydiseases"]:checked').val());		

				formData.append('RoleID', $('#UserRole').val());

                    if($('#txtCImage').val()!=""){
                        formData.append('ProfileImage', $('#txtCImage')[0].files[0]);
                    }

					@if($isEdit == true)
					formData.append('BID ',"{{$EditData[0]->BID }}");
					var  submiturl = "{{ url('/') }}/users-and-permissions/Beneficiary/edit/{{$EditData[0]->BID}}";
           			 @else
						var  submiturl = "{{ url('/') }}/users-and-permissions/Beneficiary/create";
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
                                        window.location.replace("{{url('/')}}//users-and-permissions/Beneficiary/");
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

			document.addEventListener("DOMContentLoaded", function() {
    const cloneContainer = document.querySelector('.clone-container');

    // Event delegation for "Add More" button
    cloneContainer.addEventListener('click', function(event) {
        if (event.target && event.target.id === 'addBtn') {
            const clonedDiv = createClonedDiv();
            cloneContainer.appendChild(clonedDiv);
        }
    });

    // Event delegation for "Remove" button
    cloneContainer.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('remove-btn')) {
            const clonedDiv = event.target.closest('.cloned-div');
            if (clonedDiv) {
                clonedDiv.remove();
            }
        }
    });

    function createClonedDiv() {
        const clonedDiv = document.createElement('div');
        clonedDiv.classList.add('cloned-div');
        clonedDiv.innerHTML = `
            <!-- Your cloned form fields here -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="familyname"> Name <span class="required">*</span></label>
                    <input type="text" class="form-control" placeholder="Your Name">
                    <span class="errors" id="familyname-err"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-danger remove-btn">Remove</button>
                </div>
            </div>
        `;
        return clonedDiv;
    }
});

        });
		
		




    });
</script>


@endsection