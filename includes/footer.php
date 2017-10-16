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
</script>

</body>

</html>