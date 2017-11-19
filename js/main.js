function detailsModal(id) {
    $('.spin' + id).css({ 'display': 'block' });
    var data = { 'id': id };
    $.ajax({
        method: 'POST',
        data: data,
        url: 'includes/details-modal.php',
        success: function(data) {
            $('#details-modal').remove();
            $('body').append(data);
            $('#details-modal').modal('toggle');
            $('.spin' + id).css({ 'display': 'none' });
        },
        error: function() {
            toastr["error"]("Network Connection failed,Try again");
            $('.spin' + id).css({ 'display': 'none' });
        }
    });
}

//incrementing the quantiy in the databse
function update_cart(mode, edit_id, edit_size) {
    $('.loadcart-spin' + edit_id).css({ 'display': 'block' });
    var data = { 'mode': mode, 'edit_id': edit_id, 'edit_size': edit_size };
    $.ajax({
        url: '/shop/admin/parsers/update_cart.php',
        data: data,
        method: 'POST',
        success: function() {
            load_cart();
        },
        error: function() {
            toastr["error"]("Network Connection failed,Try again");
        }
    });
}

function load_cart() {
    $.ajax({
        url: '/shop/admin/parsers/loadcart.php',
        method: 'GET',
        success: function(data) {
            $('.cart-wrapper').html(data);
        }
    });
}


function add_to_cart() {
    $('#modal_errors').html('');
    var size = $('#size').val();
    var quantity = $('#quantity').val();

    //gets no available from the hidden input
    var available = $('#available').val();
    available = parseInt(available);
    var error = '';
    //gets for data from the form
    var data = $('#add_to_cart_form').serialize();

    if (size == '' || quantity == '' || quantity == 0) {
        error = '<p class ="text-danger red lighten-4 text-center">You must choose a size and quantity</p>';
        $('#modal_errors').html(error);
        return;

    } else if (quantity > available) {
        error = '<p class ="text-danger red lighten-4 text-center">We have only ' + available + ' in stock</p>';
        $('#modal_errors').html(error);

        return;

    } else {
        $('.sidecart-spin').css({ 'display': 'block' });

        $.ajax({
            url: '/shop/admin/parsers/add_cart.php',
            method: 'POST',
            data: data,
            success: function() {
                $('.modal-backdrop').fadeOut(function() {
                    $('#details-modal').modal('toggle');
                    $('.modal-backdrop').remove();
                });
                sidecarts();

            },
            error: function() {
                toastr["error"]("Network Connection failed,Try again");
                $('.sidecart-spin').css({ 'display': 'none' });
            }
        });
    }
}

function sidecarts() {
    $.ajax({
        url: '/shop/includes/widgets/side-carts.php',
        method: 'GET',
        success: function(data) {
            $('#sidereload').html(data);
            $('.sidecart-spin').css({ 'display': 'none' });
        },
    });
}

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
            toastr["error"]("Network Connection failed,Try again");
        }
    });
}
var obj = {};

function payWithPaystack() {
    var total = $('#grand_total').val();
    grand_total = parseInt(total * 100);
    var cart_id = $('#cart_id').val();
    var full_name = $('#full_name').val();
    var phone1 = $('#phone1').val();
    var phone2 = ($('#phone2').val() != '') ? $('#phone2').val() : '';
    var email = $('#email').val();
    var state = $('#state').val();
    var address = $('#address').val();
    var description = $('#description').val();
    var handler = PaystackPop.setup({
        key: 'pk_test_86d8e282e01b04726f1bb1a766016c1c789e0eb5',
        email: email,
        amount: grand_total,
        metadata: {
            custom_fields: [{
                    display_name: "Full Name",
                    variable_name: "full_name",
                    value: full_name,
                },
                {
                    display_name: "Mobile Number",
                    variable_name: "mobile_number",
                    value: phone1 + ' ' + phone2
                },
                {
                    display_name: "Receipt",
                    variable_name: "cart_id",
                    value: cart_id,
                },
                {
                    display_name: "Address",
                    variable_name: "address",
                    value: address,
                },
                {
                    display_name: "State",
                    variable_name: "state",
                    value: state,
                }
            ]
        },
        callback: function(response) {
            var reference = response.reference;
            obj = {
                'full_name': full_name,
                'email': email,
                'state': state,
                'address': address,
                'phone1': phone1,
                'phone2': phone2,
                'description': description,
                'reference': reference,
                'cart_id': cart_id,
                'grand_total': total
            };
            $.ajax({
                url: '/shop/admin/parsers/thankyouparser.php',
                method: 'POST',
                data: obj,
                success: function() {
                    mail();
                    location.href = 'thankyou.php';
                },
                error: function() {
                    toastr["error"]("Network Connection failed,Try again");
                }
            });
        },
        onClose: function() {
            $('.paystackspin').css({ 'display': 'none' });
        }
    });
    handler.openIframe();
}

function mail() {
    $.ajax({
        url: '/shop/admin/parsers/email.php',
        method: 'POST',
        data: obj,
    });
}
