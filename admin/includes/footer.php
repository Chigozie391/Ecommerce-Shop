
</div>
<footer class="text-center">
		&copy; 2017 Developed By Chigoziemadubuko@gmail.com
</footer>

<script>
	function updateSizes() {
		var sizeString = '';
		for(var i = 1;i<=6;i++){
			if($('#size'+i).val() != ''){
				sizeString +=$('#size'+i).val() + ':' + $('#qty'+i).val() + ':' + $('#threshold'+i).val() + ',';
			}
		}
		$('#sizes').val(sizeString);
	}


	

	function get_child_options(selected){
		var parentID = $('#parent').val();
		if(typeof selected === 'undefined'){
			var selected = '';
		}
		$.ajax({
			url:'/shop/admin/parsers/child_category.php',
			type: 'POST',
			data: {parentID: parentID,selected:selected},
			success:function(data){
				$('#child').html(data);
			},
			error:function(){
				alert('Something Went Wrong with the child option');
			}
		});
	}

	$('select[name="parent"]').change(function(){
		get_child_options();
	});

	
</script>



</body>
</html>