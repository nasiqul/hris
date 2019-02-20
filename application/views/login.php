<!DOCTYPE html>
<html>

<?php require_once(APPPATH.'views/header/head.php'); ?>

<body class="hold-transition login-page">
	<?php if($this->session->flashdata('success')){ ?>  
		<div class="alert alert-success">  
			<a href="#" class="close" data-dismiss="alert">&times;</a>  
			<strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>  
		</div>  
	<?php } ?>
	<div class="login-box">
		<div class="login-logo">
			<b>Admin</b>LTE
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your session</p>

			<form onsubmit="login();return false;">
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

<script src="<?php echo base_url()?>app/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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
					alert('tes1');
				}
			})
		}

	</script>
</body>
</html>