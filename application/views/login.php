<!DOCTYPE html>
<html>

<?php require_once(APPPATH.'views/header/head.php'); ?>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<b>Admin</b>LTE
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your session</p>

			<form action=" <?php echo base_url('login/submit_login') ?>" method="POST">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" placeholder="NIK" id="nik" name="nik" required="">
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" placeholder="Password" required="" id="pass">
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

	<!-- jQuery 3 -->
	<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="../../plugins/iCheck/icheck.min.js"></script>
	<script>

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

				}
			})
		}

		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' /* optional */
			});
		});
	</script>
</body>
</html>