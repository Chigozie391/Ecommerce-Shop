</div>    
   	</div>

<footer class="text-center">
		<?php include 'includes/footerdetails.php'; ?>
</footer>

<script>
	//alerts
	toastr.options = { 
	"preventDuplicates": true,
	  "timeOut": 2800
	};

	function detailsModal(id){
		var data = {'id': id};
		$.ajax({
			method: 'POST',
			data: data,
			url: 'includes/details-modal.php',
			success:function(data){
				$('#details-modal').remove();
				$('body').append(data);
				$('#details-modal').modal('toggle');
			},
			error:function(){
				alert('Something Went Wrong');
			}
		});
	}



//incrementing the quantiy in the databse
 	function update_cart(mode,edit_id,edit_size){
 		var data = {'mode':mode,'edit_id': edit_id,'edit_size':edit_size};
 		$.ajax({
 			url:'/shop/admin/parsers/update_cart.php',
 			data:data,
 			method:'POST',
 			success:function(data){
 				
				load_cart();
 			},
 			error:function(){
 				alert('Something Went Wrong');
 			}
 		});
 	}

 	function load_cart(){
 		$.ajax({
 			url:'/shop/admin/parsers/loadcart.php',
 			method:'GET',
 			success:function(data){
 				$('#cart-reload').remove();
 				$('.cart-wrapper').append(data);
 			}
 		});
 	}


	function add_to_cart(){
		$('#modal_errors').html('');
		var size = $('#size').val();
		var quantity = $('#quantity').val();

		//gets no available from the hidden input
		var available = $('#available').val();
		available = parseInt(available);
		var error = '';
		//gets for data from the form
		var data = $('#add_to_cart_form').serialize();
		 
		if(size == '' || quantity == '' || quantity == 0 ){
			error ='<p class ="text-danger red lighten-4 text-center">You must choose a size and quantity</p>';
			$('#modal_errors').html(error);
			return;

		}else if(quantity > available){
			error ='<p class ="text-danger red lighten-4 text-center">We have only '+available+' in stock</p>';
			$('#modal_errors').html(error);

			return;

		}else{
			$.ajax({
				url:'/shop/admin/parsers/add_cart.php',
				method:'POST',
				data: data,
				success: function(){
						sidecarts();
					$('.modal-backdrop').fadeOut(function(){
					
						$('#details-modal').modal('toggle');
						$('.modal-backdrop').remove();
						
					});
			
				},
				error: function(){
					alert('Something went wrong');
				}
			});
		}
	}
	function sidecarts(){
 		$.ajax({
 			url:'/shop/includes/widgets/side-carts.php',
 			method:'GET',
 			success:function(data){
 				$('div.side-cart').remove();
 				$('.cont-sidebar').append(data);
 				
 			},
 		});
 	}

 	
</script>

</body>

</html>