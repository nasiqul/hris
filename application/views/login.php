<!DOCTYPE html>
<html>

<?php require_once(APPPATH.'views/header/head.php'); ?>

<style type="text/css">
.error {
	border:1px solid red;
}
</style>

<body class="hold-transition login-page">
	<div class="alert alert-danger" id="notif" style="display: none; cursor: pointer;" onclick="check()">
		<strong>Login Gagal,</strong> NIK atau Password Salah !!
	</div>  
	<div class="login-box">
		<div class="login-logo">
			<b>Admin</b>LTE
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your session</p>

			<form onsubmit="login();return false;">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" placeholder="NIK" id="nik" name="nik" required="" onfocus="default2()">
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" placeholder="Password" required="" id="pass" onfocus="default3()">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<!-- /.col -->
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat">Log In <i class="fa fa-arrow-right"></i></button>
					</div>
					<!-- /.col -->
				</div>
			</form>

		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<script src="<?php echo base_url()?>app/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script>
		var notif = document.getElementById("notif");

		function check() {
			notif.style.display = "none";
		}

		function login() {
			var nik = $("#nik").val();
			var pass = $("#pass").val();

			$.ajax({
				url: "<?php echo base_url('login/proses_login/')?>",
				type : "POST",
				data: {
					nik:nik,
					pass:pass
				},
				success: function(data){
					var s = $.parseJSON(data);
					if (s == 0) {
						notif.style.display = "block";

						document.getElementById("nik").className = document.getElementById("nik").className + " error";  // this adds the error class
						document.getElementById("pass").className = document.getElementById("pass").className + " error";  // this adds the error class
					} else {
						window.location.replace("<?php echo base_url('home/client/') ?>");
					}
				}
			})
		}

		function default2() {
			document.getElementById("nik").className = document.getElementById("nik").className.replace(" error", ""); // this removes the error class
		}

		function default3() {
			document.getElementById("pass").className = document.getElementById("pass").className.replace(" error", ""); // this removes the error class
		}

	</script>
</body>
</html>