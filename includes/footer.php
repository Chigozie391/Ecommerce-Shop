</div>    
   	</div>
<footer class="text-center">
		<?php include 'includes/footerdetails.php'; ?>
</footer>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/responsiveslides.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
<script>
	//alerts
	toastr.options = {
    "preventDuplicates": true,
    "timeOut": 2800
};

</script>

</body>

<?php
	mysqli_close($db);?>
</html>