var FormValidation = function () {
    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#forgot_pass');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
              
                rules: {
                    forgot_email: {
						required: true,
						email : true
					}
                },
                messages: {
					email: "Email Is Required"                      
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                },
                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },
                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },
                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },
                submitHandler: function(form) {
				
					$(".overlay-loader").show();
					$("#submit").attr("disabled",true);	
					
					var url = $(form1).attr('action');
					
					$.ajax({
						url: url, 
						type: "POST",             
						data: $('#forgot_pass').serialize(),
						cache: false,             
						processData: false,      
						success: function(response) 
						{
							$(".overlay-loader").hide();
							
							if(response.status == true)
							{
								$("#submit").attr("disabled",false);
								
								var options = 
								{
									status:"success",
									sound:true,
									duration:3000
								};

								mkNoti(
									"Password send Success",
									response.message,
									options
								);	
								
								setTimeout(function(){ 
									window.location = response.redirect;
								}, 3000);
							}
							else
							{
								var options = 
								{
									status:"danger",
									sound:true,
									duration:5000
								};

								mkNoti(
									"Password send Fail",
									response.message,
									options
								);	
								
								$("#submit").attr("disabled",false);
								
								setTimeout(function(){ 
									$(".user_result").val("");
								}, 5000);
							}
							
						}
					});
					return false;
				}
            });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleValidation1();
        }
    };
}();