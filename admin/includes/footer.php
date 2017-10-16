
</div>
<footer class="text-center">
		&copy; 2017 Developed By Chigoziemadubuko@gmail.com
</footer>

<script>
	function get_child_options(){
			console.log('jik');
		var parentID = $('#parent').val();
		$.ajax({
			url:'/shop/admin/parsers/child_category.php',
			type: 'POST',
			data: {parentID: parentID},
			success:function(data){
				$('#child').html(data);
			},
			error:function(){
				alert('Something Went Wrong with the child option');
			}
		});

	}
	$('select[name="parent"]').change(get_child_options);
</script>



</body>
</html>