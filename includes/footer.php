    	</div>
   	</div>

<footer class="text-center">
		&copy; 2017 Developed By Chigoziemadubuko@gmail.com
</footer>
<script>
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
 			success:function(){
 				
			load_cart();
				setTimeout(function(){
					$('.flash').fadeOut('slow');
					$('.flash').remove();
				},5000);
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
 				$('.container-fluid').append(data);
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
		var data = $('#add_product_form').serialize();
		
		if(size == '' || quantity == '' || quantity == 0 ){
			error +='<p class ="text-danger text-center">You must choose a size and quantity</p>';
			$('#modal_errors').html(error);
			return;

		}else if(quantity > available){
			error +='<p class ="text-danger text-center">We have only '+available+' in stock</p>';
			$('#modal_errors').html(error);

			return;

		}else{
			$.ajax({
				url:'/shop/admin/parsers/add_cart.php',
				method:'POST',
				data: data,
				success: function(){
					location.reload();
				},
				error: function(){
					alert('Something went wrong');
				}
			});
		}
	}

	setTimeout(function(){
		$('.flash').fadeOut('slow');
		$('.flash').remove();
	},5000);

</script>

</body>

</html>