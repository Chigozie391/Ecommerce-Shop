function detailsModal(a, e) {
    "index" == e ? $(".spin" + a).css({
        display: "block"
    }) : $(".popular-spin").css({
        display: "block"
    });
    var s = {
        id: a
    };
    $.ajax({
        method: "POST",
        data: s,
        url: "includes/details-modal.php",
        success: function(e) {
            $("#details-modal").remove(), $("body").append(e), $("#details-modal").modal("toggle"),
                $(".spin" + a).css({
                    display: "none"
                }), $(".popular-spin").css({
                    display: "none"
                });
        },
        error: function() {
            toastr.error("Poor Newtwork Connection,Try Again."), $(".spin" + a).css({
                display: "none"
            }), $(".popular-spin").css({
                display: "none"
            });
        }
    });
}

function update_cart(a, e, s) {
    $(".loadcart-spin" + e).css({
        display: "block"
    });
    var o = {
        mode: a,
        edit_id: e,
        edit_size: s
    };
    $.ajax({
        url: "/shop/admin/parsers/update_cart.php",
        data: o,
        method: "POST",
        success: function() {
            load_cart();
        },
        error: function() {
            toastr.error("Poor Newtwork Connection,Try Again.");
        }
    });
}

function load_cart() {
    $.ajax({
        url: "/shop/admin/parsers/loadcart.php",
        method: "GET",
        success: function(a) {
            $(".cart-wrapper").html(a);
        }
    });
}

function add_to_cart() {
    $("#modal_errors").html("");
    var a = $("#size").val(),
        e = $("#quantity").val(),
        s = $("#available").val();
    s = parseInt(s);
    var o = $("#add_to_cart_form").serialize();
    null != a && "" != a && "" != e && 0 != e ? e > s ? toastr.warning("We only have " + s + " Available.") : ($(".sidecart-spin").css({
        display: "block"
    }), $.ajax({
        url: "/shop/admin/parsers/add_cart.php",
        method: "POST",
        data: o,
        success: function() {
            $(".modal-backdrop").fadeOut(function() {
                $("#details-modal").modal("toggle"), $(".modal-backdrop").remove();
            }), sidecarts();
        },
        error: function() {
            toastr.error("Poor Newtwork Connection,Try Again."), $(".sidecart-spin").css({
                display: "none"
            });
        }
    })) : toastr.warning("Please Choose a Size and Quantity.");
}

function sidecarts() {
    $.ajax({
        url: "/shop/includes/widgets/side-carts.php",
        method: "GET",
        success: function(a) {
            $("#sidereload").html(a), $(".sidecart-spin").css({
                display: "none"
            });
        }
    });
}

function check_address() {
    var a = {
        full_name: $("#full_name").val(),
        email: $("#email").val(),
        address: $("#address").val(),
        state: $("#state").val(),
        phone1: $("#phone1").val(),
        phone2: $("#phone2").val()
    };
    $.ajax({
        url: "/shop/admin/parsers/check_address.php",
        method: "post",
        data: a,
        success: function(a) {
            "passed" != a && $("#modal_errors").html(a), "passed" == a && ($("#modal_errors").remove(),
                $("#check-out-modal").modal("toggle"), $(".paystackspin").css({
                    display: "block"
                }), payWithPaystack());
        },
        error: function() {
            toastr.error("Poor Newtwork Connection,Try Again.");
        }
    });
}

function payWithPaystack() {
    var a = $("#grand_total").val();
    grand_total = parseInt(100 * a);
    var e = $("#cart_id").val(),
        s = $("#full_name").val(),
        o = $("#phone1").val(),
        r = "" != $("#phone2").val() ? $("#phone2").val() : "",
        n = $("#email").val(),
        t = $("#state").val(),
        l = $("#address").val(),
        i = $("#description").val();
    PaystackPop.setup({
        key: "pk_test_86d8e282e01b04726f1bb1a766016c1c789e0eb5",
        email: n,
        amount: grand_total,
        metadata: {
            custom_fields: [{
                display_name: "Full Name",
                variable_name: "full_name",
                value: s
            }, {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: o + " " + r
            }, {
                display_name: "Receipt",
                variable_name: "cart_id",
                value: e
            }, {
                display_name: "Address",
                variable_name: "address",
                value: l
            }, {
                display_name: "State",
                variable_name: "state",
                value: t
            }]
        },
        callback: function(d) {
            var c = d.reference;
            obj = {
                full_name: s,
                email: n,
                state: t,
                address: l,
                phone1: o,
                phone2: r,
                description: i,
                reference: c,
                cart_id: e,
                grand_total: a
            }, $.ajax({
                url: "/shop/admin/parsers/thankyouparser.php",
                method: "POST",
                data: obj,
                success: function() {
                    mail(), location.href = "thankyou.php";
                },
                error: function() {
                    toastr.error("Poor Newtwork Connection,Try Again.");
                }
            });
        },
        onClose: function() {
            $(".paystackspin").css({
                display: "none"
            });
        }
    }).openIframe();
}

function mail() {
    $.ajax({
        url: "/shop/admin/parsers/email.php",
        method: "POST",
        data: obj
    });
}

var obj = {};
