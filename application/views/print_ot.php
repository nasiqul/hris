<!DOCTYPE html>
<html>
<head>
	<title>OVERTIME</title>
</head>
<style type="text/css">
body {
	font-family: sans-serif;
}
div {
	border: 1px solid black;
	width: 90%;
	height: 25px;
	margin: 0 auto;
	text-align: center;
	line-height: 25px;
}
.kep {
	border: 1px solid black;
}
p {
	max-width: 300px;
	word-wrap: break-word;
}
#anggota {
	border-top: 1px solid black;
	border-collapse:collapse;
	text-align: center;
}
#anggota tr th { 
	border-bottom: 1px solid #000;
	padding: 5px 0 5px 0;
}
#anggota tr td { 
	padding: 10px 0 10px 0;
}
#anggota #bottom td { 
	border-top: 1px solid #000;
	padding: 5px 0 5px 0;
}
#tb-collapse {
	border-collapse:collapse; border: 1px solid black;
}
#tb-collapse td {
	border: 1px solid black;
}
</style>
<body onload="window.print()">
	<?php 
	$hari = date("D",strtotime($list[0]->tanggal));

	switch($hari){
		case 'Sun':
		$hari_ini = "Minggu";
		break;

		case 'Mon':			
		$hari_ini = "Senin";
		break;

		case 'Tue':
		$hari_ini = "Selasa";
		break;

		case 'Wed':
		$hari_ini = "Rabu";
		break;

		case 'Thu':
		$hari_ini = "Kamis";
		break;

		case 'Fri':
		$hari_ini = "Jumat";
		break;

		case 'Sat':
		$hari_ini = "Sabtu";
		break;
		
		default:
		$hari_ini = "Tidak di ketahui";		
		break;
	}
	?>
	<table width="100%" border="0" style="padding-right: 20px">
		<tr>
			<td width="85%" colspan="5"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></td>
		</tr>
		<tr>
			<td colspan="4"><center><h2>FORM LEMBUR KARYAWAN</h2></center></td>
			<td style="text-align: center;">
				<img src="../../app/qr_lembur/<?php echo $list[0]->id_over ?>.png" width="70px"><br>
				<?php echo $list[0]->id_over ?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding: 5px 0  5px 20px">Hari</td>
			<td width="2%">:</td>
			<td width="35%" ><?php echo $hari_ini; ?></td>
			<td colspan="2" style="padding-right: 20px">Keperluan :</td>
		</tr>
		<tr>
			<td width="10%" style="padding: 5px 0  5px 20px">Tanggal</td>
			<td width="2%">:</td>
			<td width="25%"><?php echo $list[0]->tanggal ?></td>
			<td colspan="2" rowspan="2" class='kep' style="padding: 7px"><p><?php echo $list[0]->keperluan ?></p></td>
		</tr>
		<tr>
			<td width="10%" style="padding: 5px 0  5px 20px">Bagian</td>
			<td width="2%">:</td>
			<td width="25%"><?php echo $list[0]->departemen ?> - <?php echo $list[0]->section ?></td>
		</tr>
	</table>
	<table width="98%" style="margin-top: 10px" id="anggota"  align="center" border="0">
		<tr>
			<th width="3%">No</th>
			<th width="15%">NIK</th>
			<th >Nama</th>
			<th width="7%">Dari</th>
			<th width="10%">Sampai</th>
			<th width="5%">Trans</th>
			<th width="8%">Makan</th>
			<th width="8%">E.Food</th>
			<th width="6%">TTD</th>
			<th width="6%">jam</th>
			<th width="6%">Revisi</th>
			<th width="8%">TTD Atasan</th>
		</tr>
		<?php $no=1; $jml=0; $total=0; $mkn=0; $efood=0; $b=0; $p=0; foreach ($list_anggota as $key) { ?>
			<tr>
				<td><?php echo $no ?></td>
				<td><?php echo $key->nik ?></td>
				<td><?php echo $key->namaKaryawan ?></td>
				<td><?php echo date("H:i",strtotime($key->dari)); ?></td>
				<td><?php echo date("H:i",strtotime($key->sampai)) ?></td>
				<td><div><?php echo $key->transport; ?></div></td>
				<td><?php if ($key->makan == 1){ echo "&#x2714"; $mkn+=1; }?></td>
				<td><?php if ($key->ext_food == 1){ echo "&#x2714"; $efood+=1;} ?></td>
				<td><div></div></td>
				<td><div><?php echo $key->jam ?></div></td>
				<td><div></div></td>
				<td><div></div></td>
			</tr>
			<?php 
			if ($key->transport == "B")
				$b+=1;
			if ($key->transport == "P")
				$p+=1;
			$jml += (float) $key->jam; 
			$no++;
			$total = $key->budget;
		} ?>
		<tr id="bottom">
			<td colspan="3" style="text-align: left;">B = Bangil ; P = Pasuruan</td>
			<td colspan="6" style="text-align: right;">Total = </td>
			<td><div><?php echo $jml; ?></div></td>
			<td>Jam</td>
			<td></td>
		</tr>
		<tr><td colspan="3"></td><td colspan="3" align="left">Catatan :</td></tr>
		<tr>
			<td colspan="3">
				<table width="100%" id="tb-collapse">
					<tr><td width="15%">B</td><td width="15%">P</td><td width="30%">Total Makan</td><td width="30%">Total E.Food</td></tr>
					<tr><td><?php echo $b ?></td><td><?php echo $p ?></td><td><?php echo $mkn ?></td><td><?php echo $efood ?></td></tr>
				</table>
			</td>
			<td colspan="9">
				<div style="height: 80px; margin: 0 5px 0 5px; width: 100%;"><?php echo $list[0]->catatan ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<table width="100%" id="tb-collapse" style="background-color: #dddddd">
					<tr><td width="34%">TARGET</td><td width="33%">ACTUAL</td><td width="33%">DIFF</td></tr>
					<tr><td height="20px"><?php echo $cc_member[0]->jml*$total ?></td><td></td><td></td></tr>
					<tr><td colspan="3" height="130px"></td></tr>
				</table>
			</td>
			<td colspan="9">
				<table width="100%" id="tb-collapse" style="margin-left: 5px; background-color: #dddddd">
					<tr><td>Diusulkan,</td><td>Disetujui,</td><td>Diketahui,</td><td>Diterima,</td></tr>
					<tr><td>Staff / Leader</td><td>Chief / Foreman</td><td>Dept. Manager</td><td>HR Dept.</td></tr>
					<tr><td height="92px"></td><td></td><td></td><td></td></tr>
					<tr><td style="text-align: left;">tgl. </td><td style="text-align: left;">tgl. </td><td style="text-align: left;">tgl. </td><td style="text-align: left;">tgl. </td></tr>
				</table>
			</td>
		</tr>
	</table>

</body>
</html>