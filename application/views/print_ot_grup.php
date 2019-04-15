<!DOCTYPE html>
<html>
<head>
	<title>Overtime</title>
</head>

<link rel="stylesheet" href="<?php echo base_url()?>app/bower_components/bootstrap/dist/css/bootstrap.min.css">
<script src="<?php echo base_url()?>app/bower_components/jquery/dist/jquery.min.js"></script>

<style type="text/css">
	@page {
		size: A4 portrait;
		margin: 1;
	}
	@media print {
		#tb-collapse {
			background-color: #dddddd !important;
			-webkit-print-color-adjust: exact;
		}
		body {
			font-size: 12pt;
		}
		#tb-collapse tr td{
		padding: 2px;
	}
	}

	body {
		font-family: sans-serif;
		padding: 5px;
	}
	.main {
		width: 100%;
		margin: 5px 0px 20px 0px;
	}
	.main tr th, .main tr td {
		padding: 8px;
		vertical-align: top;
	}
</style>
<body>
	<div class="row">
		<div class="col-md-12">
			<b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b>
			<center><h3>LEMBAR PERSETUJUAN LEMBUR LIBUR</h3></center>
			<table class="main" border="1px">
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<th>ID SPL</th>
					<th>Bagian</th>
					<th>Jumlah <br> (orang)</th>
					<th>Jumlah <br> (jam)</th>
					<th>Max OT</th>
					<th>Aktual</th>
					<th>Diff</th>
				</tr>
				<?php $no = 1; 
				foreach ($anggota as $key) { ?>
					<tr>
						<td><?php echo $no ?></td>
						<td><?php echo date('d-m-Y',strtotime($key->tanggal)) ?></td>
						<td><?php echo $key->id ?></td>
						<td><?php echo $key->sec." - ".$key->sub." - ".$key->grup ?></td>
						<td><?php echo $key->jml_org ?></td>
						<td><?php echo $key->jml_jam ?></td>
						<td><?php echo $key->bgt ?></td>
						<td><?php echo $key->act ?></td>
						<td><?php echo $key->bgt-$key->act ?></td>
					</tr>
					<?php $no++; } ?>
				</table>

				<div style="width: 50%" class="pull-right">
					<table width="100%" border="1px" id="tb-collapse" style="background-color: #dddddd">
						<tr>
							<td width="33%" cellpading="0" cellspacing="0">Diketahui,</td>
							<td width="33%">Desetujui,</td>
							<td width="34%">Diketahui,</td>
						</tr>
						<tr>
							<td>Dept. Manager</td>
							<td>GM Devision</td>
							<td>HR Director</td>
						</tr>
						<tr>
							<td height="92px"></td><td></td><td></td>
						</tr>
						<tr><td style="text-align: left;">tgl. </td><td style="text-align: left;">tgl. </td><td style="text-align: left;">tgl. </td></tr>
					</table>
				</div>
			</div>
		</div>


		<script src="<?php echo base_url()?>app/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url()?>app/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url()?>app/bower_components/morris.js/morris.min.js"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo base_url()?>app/dist/js/adminlte.min.js"></script>
		<script src="<?php echo base_url()?>app/dist/js/jquery.gritter.min.js"></script>


		<script type="text/javascript">
			$(document).ready(function() {
				window.print();
			});
		</script>

	</body>
	</html>