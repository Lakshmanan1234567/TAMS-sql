class Validation{
    MobileNumber=async()=>{
        $('#txtMobileNumber-err').html('');
			let status=true;
			let MobileNumber=$('#txtMobileNumber').val().toString();
            let Length=$('#txtMobileNumber').attr('data-length');
            if((Length=="")||(Length==undefined)){Length=0;}
			if(MobileNumber==""){
				$('#txtMobileNumber-err').html('Mobile Number is required');status=false;
			}else if(!$.isNumeric(MobileNumber)){
				$('#txtMobileNumber-err').html('Mobile Number is not valid');status=false;
			}else if((Length!=MobileNumber.length)&&(Length!=0)){
                $('#txtMobileNumber-err').html('The Mobile Number not valid.');status=false;
            }
			return status;
    }
}