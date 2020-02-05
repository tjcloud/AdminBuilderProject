var FormValidation = function () {
    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#product_form');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
              
                rules: {
                    product_name: {
                        required: true,
						minlength:2
                    },
					material_type:{
						required: true
					},
					product_description:{
						required: true
					},
					product_stock_qty:{
						required: true,
						number: true
					},
					no_of_unit:{
						required: true,
						number: true
					}
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
                    
					var check = check_items();
	
					if(check == '1')
					{
						var total = 0;
						var no_of_unit = $("#no_of_unit").val();
						$(".unit_total").each( function(){
							total = total + parseInt($(this).val() || 0);
						});	
						
						if(total != 0 && total < no_of_unit){
							var options = 
							{
								status:"danger",
								sound:true,
								duration:3500
							};

							mkNoti(
								"Less Unit Error",
								'Please check color unit!',
								options
							);
							return false;
						}
						
						$(".overlay-loader").show();
						$("#submit").attr("disabled",true);	
						
						var url = $(form1).attr('action');
						var form_data = new FormData(form);
							  
						$.ajax({
							type: 'POST',       
							url: url,
							data: form_data,
							processData: false,
							contentType: false,
							success: function (response)
							{
								$(".overlay-loader").hide();
								
								if(response.status == true)
								{
									var options = 
									{
										status:"success",
										sound:true,
										duration:2500
									};

									mkNoti(
										"Success",
										response.message,
										options
									);
									
									setTimeout(function(){
									   window.location = response.redirect;
									},2500);
								}
								else
								{
									var options = 
									{
										status:"danger",
										sound:true,
										duration:3500
									};

									mkNoti(
										"Fail",
										response.message,
										options
									);
									
									$("#submit").attr("disabled",false);	
									return false;
								}
							}
						}); 		
					}
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