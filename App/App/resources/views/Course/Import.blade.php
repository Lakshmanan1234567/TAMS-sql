@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
	<div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="f-16 fa fa-home"></i></a></li>
                                <li class="breadcrumb-item "><a href="{{ url('/') }}/Course">Course</a></li>
                                <li class="breadcrumb-item active">Import</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Course</h4>
                    </div>
                </div>
            </div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
	<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header ">
							<!-- <h5 class="text-center ">Import</h5> -->
							<a href="{{url('/')}}/uploads/Course/CorseExcel.xlsx" class="btn btn-sm btn-outline-info text-right" id="btnFormatxl ">Formate Excel</a>
							<button  class="btn btn-sm btn-outline-warning text-right d-none" id="btnMapping">Mapping</button>
								
						</div>
						<div class="card-body">
								<div class="row mb-20  d-flex justify-content-center">
									<div class="col-sm-6">
										<input type="file" class="dropify" id="FileUpload" data-default-file=""  data-allowed-file-extensions='["xlsx","xls","csv"]' >
										<span class="errors" id="FileUpload-err"></span>
									</div>
									
								</div>
								
										<div class="row">
                                            @if($isEdit==false)
                                                <div class="col-sm-12 text-right ">
												@if($crud['view'])
                            <a href="{{ url('/Course') }}" class="btn btn-outline-light btn-air-success btn-sm" id="btnCancel">Back</a>
                            @endif
                                                    
                                                    @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                                        <button class="btn btn-sm btn-outline-success " id="btnSubmit">@if($isEdit==true) Update @else Import @endif</button>
                                                        @endif
                                                        
                                                    
                                                </div>
                                            @endif
                                        </div>
										
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.0/dist/xlsx.full.min.js"></script>

<script>
  $(document).ready(function() {
    $('.dropify').dropify();
  });

  var ExcelToJSON = function() {
    this.parseExcel = function(file) {
        var reader = new FileReader();

        reader.onload = function(e) {
        var data = e.target.result;
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
        workbook.SheetNames.forEach(function(sheetName) {
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            var productList = JSON.parse(JSON.stringify(XL_row_object));

            var rows = $('#tblItems tbody');
            for (i = 0; i < productList.length; i++) {
            var columns = Object.values(productList[i])
            rows.append(`
                            <tr>
                                <td>${columns[0]}</td>
                                <td>${columns[1]}</td>
                                <td>${columns[2]}</td>
                                <td>${columns[3]}</td>
                               
                            </tr>
                        `);
            }

        })
        };
        reader.onerror = function(ex) {
        console.log(ex);
        };

        reader.readAsBinaryString(file);
    };
  };

  function handleFileSelect(evt) {
      var files = evt.target.files; // FileList object
      var xl2json = new ExcelToJSON();
      xl2json.parseExcel(files[0]);
  }

  document.getElementById('FileUpload').addEventListener('change', handleFileSelect, false);

  $("#FileUpload").change(function(){
      $("#PreviewtBody").empty();
  });

  const formValidation = () => {
      $('.errors').html('');
      let status = true;
      if ($('#FileUpload').val() == "") {
          $('#FileUpload-err').html("Please select import file");
          status = false;
      }
      return status;
  }

  $('.Mappingdiv').hide();

  function btnLoading(button) {
      button.prop('disabled', true);  // Disable the button
      button.html('<img src="{{url('/')}}/App/assests/Images/Book.gif" alt="Loading...">');  // Replace button text with the preloader image
  }

  $('#btnSubmit').click(function(){
      console.log($('#FileUpload')[0].files[0]);

      let status = formValidation();
      console.log(status);

      if (status) {
          swal({
              title: "Are you sure?",
              text: "You want @if($isEdit==true)Update @else Save @endif this ImportFile!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-outline-primary",
              confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
              closeOnConfirm: false
          }, function(){
              swal.close();
              btnLoading($('#btnSubmit'));
              let postUrl = "{{url('/')}}/Course/Import/CUsave";
              let formData = new FormData();
              @if($isEdit == false)
              if ($('#FileUpload').val() != "") {
                  formData.append('importfile', $('#FileUpload')[0].files[0]);
              }
              @endif

              $.ajax({
                  type: "post",
                  url: postUrl,
                  headers: { 'X-CSRF-Token': $('meta[name=_token]').attr('content') },
                  data: formData,
                  cache: false,
                  processData: false,
                  contentType: false,
                  xhr: function() {
                      var xhr = new window.XMLHttpRequest();
                      xhr.upload.addEventListener("progress", function(evt) {
                          if (evt.lengthComputable) {
                              var percentComplete = (evt.loaded / evt.total) * 100;
                              percentComplete = parseFloat(percentComplete).toFixed(2);
                              $('#divProcessText').html(percentComplete + '% Completed.<br> Please wait for until upload process complete.');
                          }
                      }, false);
                      return xhr;
                  },
                  error: function(e, x, settings, exception) {
                      ajax_errors(e, x, settings, exception);
                  },
                  complete: function(e, x, settings, exception) {
                      btnReset($('#btnSubmit'));
                  },
                  success: function(response) {
                      document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                      if (response.status == true) {
                          swal({
                              title: "SUCCESS",
                              text: response.message,
                              type: "success",
                              showCancelButton: false,
                              confirmButtonClass: "btn-outline-primary",
                              confirmButtonText: "Okay",
                              closeOnConfirm: false
                          }, function() {
                              window.location.reload();
                          });
                      } else {
                          toastr.error(response.message, "Failed", {
                              positionClass: "toast-top-right",
                              containerId: "toast-top-right",
                              showMethod: "slideDown",
                              hideMethod: "slideUp",
                              progressBar: !0
                          });
                          if (response['errors'] != undefined) {
                              $('.errors').html('');
                              $.each(response['errors'], function(KeyName, KeyValue) {
                                  var key = KeyName;
                                  if (key == "importfile") {
                                      $('#FileUpload-err').html(KeyValue);
                                  }
                              });
                          }
                      }
                  }
              });
          });
      }
  });

  $('#btnMapping').click(function(){
      $('.Mappingdiv').toggle();
  });
</script>




@endsection