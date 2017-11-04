		function check_address() {
		    var data = {
		        'full_name': $('#full_name').val(),
		        'email': $('#email').val(),
		        'address': $('#address').val(),
		        'state': $('#state').val(),
		        'phone1': $('#phone1').val(),
		        'phone2': $('#phone2').val(),
		    };
		    $.ajax({
		        url: '/shop/admin/parsers/check_address.php',
		        method: 'post',
		        data: data,
		        success: function(data) {
		            if (data != 'passed') {
		                $('#modal_errors').html(data);
		            }
		            if (data == 'passed') {
		                $('#modal_errors').remove();
		                $('#check-out-modal').modal('toggle');
		                $('.paystackspin').css({ 'display': 'block' });
		                payWithPaystack();

		            }
		        },
		        error: function() {
		            alert('Something Went Wrong');
		        }
		    });
		}


		function payWithPaystack() {
		    var grand_total = $('#grand_total').val();
		    grand_total = parseInt(grand_total) * 100;
		    var cart_id = $('#cart_id').val();
		    var handler = PaystackPop.setup({
		        key: 'pk_test_86d8e282e01b04726f1bb1a766016c1c789e0eb5',
		        email: $('#email').val(),
		        amount: grand_total,
		        metadata: {
		            custom_fields: [{
		                    display_name: "Full Name",
		                    variable_name: "full_name",
		                    value: $('#full_name').val(),

		                },
		                {
		                    display_name: "Mobile Number",
		                    variable_name: "mobile_number",
		                    value: $('#phone1').val() + ' ' + $('#phone2').val()
		                },
		                {
		                    display_name: "Receipt",
		                    variable_name: "cart_id",
		                    value: cart_id,

		                },
		                {
		                    display_name: "Address",
		                    variable_name: "address",
		                    value: $('#address').val(),

		                },
		                {
		                    display_name: "State",
		                    variable_name: "state",
		                    value: $('#state').val(),

		                }
		            ]
		        },
		        callback: function(response) {
		            $('#response').val(response.reference);
		            $('#payment-form').submit();
		        },
		        onClose: function() {
		            $('.paystackspin').css({ 'display': 'none' });
		        }
		    });

		    handler.openIframe();

		}
