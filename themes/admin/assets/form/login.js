var FormValidation = function () {
    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#loginform');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
              
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                    }
                },
                messages: {
					email: "Email Is Required",
					password: "Password Is Required"
                      
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
                submitHandler: function (form) {
					
                    success1.show();
                    error1.hide();
					
					$(".overlay-loader").show();
					$("#submit").attr("disabled",true);			
					
                    var url = $(form1).attr('action');
                   
                    var email = $("#email").val();
                    var password = $("#password").val();
                    
                    var data = "email="+email+"&password="+password;
                               
                    $.ajax({
                        type: 'POST',       
                        url: url,
                        data: data,
                        success: function (response)
                        {
							$(".overlay-loader").hide();
							
                            if(response.status == true)
							{
								var options = 
								{
									status:"success",
									sound:true,
									duration:3000
								};

								mkNoti(
									"Login Success",
									response.message,
									options
								);	
											
								setTimeout(function(){
									$("#submit").attr("disabled",false);
									window.location = response.redirect;
                                },3000);
							}
							else
							{
								var options = 
								{
									status:"danger",
									sound:true,
									duration:3000
								};

								mkNoti(
									"Login Fail",
									response.message,
									options
								);
								
								$("#submit").attr("disabled",false);			
								return false;
							}
                        }
                    }); 
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