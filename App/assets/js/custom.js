
$('.select2').select2();
$('.select2Tag').select2({tags: true});

$('.dropify').dropify(); 
function NumberFormat(value,type){ 
    try {
        if((value=="")||(value==undefined)||(isNaN(parseFloat(value)))){
            value=0;
        }
        Decimal="auto";
        let settings=$('#divsettings').html();
        if(settings!=""){
            settings=JSON.parse(settings);
        }
        type=type.toString().toLowerCase();
        if(type=="weight"){
            if(settings['WEIGHT-DECIMAL-LENGTH']!=undefined){
                Decimal=settings['WEIGHT-DECIMAL-LENGTH'];
            }
		}else if(type=="purity"){
            if(settings['PURITY-DECIMAL-LENGTH']!=undefined){
                Decimal=settings['PURITY-DECIMAL-LENGTH'];
            }
		}else if(type=="price"){
            if(settings['PRICE-DECIMAL-LENGTH']!=undefined){
                Decimal=settings['[PRICE]-DECIMAL-LENGTH'];
            }
		}else if(type=="qty"){
            if(settings['QTY-DECIMAL-LENGTH']!=undefined){
                Decimal=settings['QTY-DECIMAL-LENGTH'];
            }
		}else if(type=="percentage"){
            if(settings['PERCENTAGE-DECIMAL-LENGTH']!=undefined){
                Decimal=settings['PERCENTAGE-DECIMAL-LENGTH'];
            }
		}else{
			Decimal=0;
        }
		if(Decimal!="auto"){
			return parseFloat(value).toFixed(Decimal);
		}else{
			return value;
		}
    } catch (error) {
        return value;
    }

}
const randomString=async(length)=> {
    let result           = '';
    let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for ( let i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() *  charactersLength));
    }
   return result;
}
const btnLoading=async($this) =>{
    let loadingText = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing';
    if ($($this).html() !== loadingText) {
        $this.data('original-text', $($this).html());
        $this.html(loadingText);
    }
}
const btnReset=async($this)=> {
    $('.waves-ripple').remove();
    $this.html($this.data('original-text'));
    $this.removeAttr('disabled');
}
const btn_Loading=async($this) =>{
    var loadingText = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing';
    if ($($this).html() !== loadingText) {
        $this.data('original-text', $($this).html());
        $this.html(loadingText);
    }
}
const btn_reset=async($this)=> {
    $('.waves-ripple').remove();
    $this.html($this.data('original-text'));
    $this.removeAttr('disabled');
}

const ajax_errors=async(e, x, settings, exception)=> {
    
    let isSwal=false;let isToastr=false;
    try {
        if(swal){isSwal=true;}
    }
    catch(err) {
        console.log("toastr is missing");
    }
    try {
        if(toastr){isToastr=true;}
    }
    catch(err) {
        console.log("toastr is missing");
    }
    if ((e.status != 200) && (e.status != undefined)) {
        var message="";
        var statusErrorMap = {
            '400': "Server understood the request, but request content was invalid.",
            '401': "Unauthorized access.",
            '403': "Forbidden resource can't be accessed.",
            '404': "Sorry! Page Not Found",
            '405': "Sorry! Method not Allowed",
            '419': "Sorry! Page session has been expired",
            '500': "Internal server error.",
            '503': "Service unavailable."
        };
        if (e.status) {
            message = statusErrorMap[e.status];
        } else if (x == 'timeout') {
            message = "Request Time out.";
        } else if (x == 'abort') {
            //message = "Request was aborted by the server";
        }
        if ((message != "")&&(message!=undefined)) {
            if(isToastr==true){
                toastr.error(message, "Failed", {
                    positionClass: "toast-top-right",
                    containerId: "toast-top-right",
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    progressBar: !0
                })
            }else if(isSwal==true){
                swal("Error", message, "error");
            }
            if(e.status==419){
                setTimeout(async()=>{
                    window.location.reload();
                },100)
            }
        }
    } else if (x == 'parsererror') {
        if(isToastr==true){
            toastr.error("Parsing JSON Request failed.", "Failed", {
                positionClass: "toast-top-right",
                containerId: "toast-top-right",
                showMethod: "slideDown",
                hideMethod: "slideUp",
                progressBar: !0
            })
        }else if(isSwal==true){
            swal("Error", "Parsing JSON Request failed.", "error");
        }
    } else if (x == 'timeout') {
        if(isToastr==true){
            toastr.error("Request Time out.", "Failed", {
                positionClass: "toast-top-right",
                containerId: "toast-top-right",
                showMethod: "slideDown",
                hideMethod: "slideUp",
                progressBar: !0
            })
        }else if(isSwal==true){
            swal("Error", "Request Time out.", "error");
        }
    } else if (x == 'abort') {
        if(isToastr==true){
            toastr.error("Request was aborted by the server", "Failed", {
                positionClass: "toast-top-right",
                containerId: "toast-top-right",
                showMethod: "slideDown",
                hideMethod: "slideUp",
                progressBar: !0
            })
        }else if(isSwal==true){
            swal("Error", "Request was aborted by the server", "error");
        }
    }
}


function ajaxindicatorstart(text="") {
    var basepath=$('#txtRootUrl').val();
    if ($('body').find('#resultLoading').attr('id') != 'resultLoading') {
        if(text==""){text="Processing";}
        $('body').append('<div id="resultLoading" style="display:none"><div style="font-weight: 700;"><img src="' + basepath + '/assets/images/ajax-loader.gif"><div id="divProcessText">'+text+'</div></div><div class="bg"></div></div>');
    }
    $('#resultLoading').css({
        'width': '100%',
        'height': '100%',
        'position': 'fixed',
        'z-index': '10000000',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto'
    });
    $('#resultLoading .bg').css({
        'background': '#000000',
        'opacity': '0.7',
        'width': '100%',
        'height': '100%',
        'position': 'absolute',
        'top': '0'
    });
    $('#resultLoading>div:first').css({
        'width': '50%',
        'height': '75px',
        'text-align': 'center',
        'position': 'fixed',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto',
        'font-size': '16px',
        'z-index': '10',
        'color': '#ffffff'
    });
    $('#resultLoading .bg').height('100%');
    $('#resultLoading').fadeIn(300);
    $('body').css('cursor', 'wait');
}

function ajaxindicatorstop() {
    $('#resultLoading .bg').height('100%');
    $('#resultLoading').fadeOut(300);
    $('body').css('cursor', 'default');
}
$(document).on('click','#btnLogout',async(e)=>{
    e.preventDefault();
    $('#logout-form').submit();
})/*
//Inspect element Disable Start
document.onkeydown = function (e) { 
            if (event.keyCode == 123) { 
                return false; 
            } 
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) { 
                return false; 
            } 
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) { 
                return false; 
            } 
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) { 
                return false; 
            } 
            if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) { 
                return false; 
            } 
        }

        
if((typeof devtoolsDetector!==undefined)&&(typeof devtoolsDetector!=='undefined')){
    devtoolsDetector.addListener(function(isOpen) {
        if(isOpen==true){
          $('body').html('');
          $('head').html('');
          $('body').html('DEVTOOLS detected.')
          setTimeout(async()=>{alert('DEVTOOLS detected. all operations will be terminated.');},100);
          
        }
    });
    devtoolsDetector.launch();
}
document.addEventListener('contextmenu', event => event.preventDefault());*/
//Inspect element Disable Stop
$(document).ready(async function(){
    let RootUrl=$('#txtRootUrl').val();
    // For Square Image
    
    const oneIsone=async()=>{
        $( ".oneIsone" ).each(function() {
                let onewid = $(this).width();
                $(this).height(onewid);
            });
        
    }
   setTimeout(async()=>{oneIsone();},100) 
    $(document).on('dblclick','.dt-buttons.btn-group',function(e){
        e.preventDefault()
    });
   $(document).on('dblclick','body',function(e){
        e.preventDefault()
   })
   $(document).on('change','select.ledgerView',function(){
       let id=$(this).attr('id');
       let val=$(this).val();
       $('label[for="'+id+'"] span.ledgerView').attr('data-ledger-id',val);
       if((val!="")&&(val!=undefined)){
            $('label[for="'+id+'"] span.ledgerView').show();
       }else{
        $('label[for="'+id+'"] span.ledgerView').hide();
       }
   });
   $(document).on('click','label span.ledgerView',function(){
       let LedgerID=$(this).attr('data-ledger-id');
       console.log(LedgerID);
       try {
			$.ajax({
				type:"post",
				url:RootUrl+"Get/LedgerView/"+LedgerID,
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
				error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
				complete: function(e, x, settings, exception){},
				success:function(response){
					bootbox.dialog({
						title: 'Ledger View',
						closeButton: true,
						message: response,
						size: 'large',
						buttons: {
						}
					});
				}
			});
		} catch (error) {
			toastr.error(error.message, "Error", {positionClass: "toast-top-right",containerId: "toast-top-right",showMethod: "slideDown",hideMethod: "slideUp",progressBar: !0})
        }
   });
    $(document).on('click','.btnShowOrderDetails',function(e){
        e.preventDefault();
        let OrderID=$(this).attr('data-order-id');
        $.ajax({
            type:"post",
            url:RootUrl+"Transaction/Order/Detail/View",
            data:{OrderID:OrderID},
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            success:async(response)=>{

                bootbox.dialog({
                    title: 'Order Details',
                    closeButton: true,
                    message: response,
                    size: 'large',
                    buttons: {
                    }
                });
            }
        });
    });
});