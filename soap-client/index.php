<?php
error_reporting(1);
include "client.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
<body>
<div class="navbar">
  <div class="navbar-inner">
	<a class="brand" href="#">SOAP</a>
	<ul class="nav">
	  <li><a href="?page=home"><i class="icon-home"></i> Home</a></li>
	  <li><a href="?page=tambah-data"><i class="icon-plus-sign"></i> Tambah Data</a></li>
	  <li><a href="?page=daftar-data-server"><i class="icon-list"></i> Data Server</a></li>
	  <li><a href="?page=daftar-data-client"><i class="icon-list"></i> Data Client</a></li>
	</ul>
  </div>
</div>

<div class="container">
<fieldset>

<?php if ($_GET['page']=='tambah-data') {
?>
<legend>Tambah Data</legend>
	<div class="row-fluid ">
    <div class="span8 alert alert-info">
	<form class="form-horizontal" name="form1" method="POST" action="proses.php" novalidate>
		<input type="hidden" name="aksi" value="tambah_data"/>
		<div class="control-group">
			<label class="control-label" for="nim">NIM</label>
			<div class="controls">
				<input type="text" name="nim" class="input-small" placeholder="NIM"
					rel="tooltip" data-placement="right" title="Masukkan NIM"
					required data-validation-required-message="Harus diisi">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="nama_mhs">Nama Mahasiswa</label>
			<div class="controls">
				<input type="text" name="nama_mhs" class="input-medium" placeholder="Nama Mahasiswa"
					rel="tooltip" data-placement="right" title="Masukkan Nama Mahasiswa"
					required data-validation-required-message="Harus diisi">
			</div>
		</div>

			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" name="simpan" class="btn btn-primary"><i class="icon-ok icon-white"></i> Simpan</button>
			</div>
		</div>
	</form>
	</div>
	</div>
<?php } elseif ($_GET['page']=='ubah-data') {
	$r = $objek->lihat_mhs($_GET['nim']);
?>
<legend>Ubah Data</legend>
	<form name="form1" method="post" action="proses.php" class="form-inline">
		<input type="hidden" name="aksi" value="ubah_data"/>
		<input type="hidden" name="nim" value="<?php echo $r['nim']?>" />
		<input type="text" disabled class="input-small" placeholder="NIM" value="<?php echo $r['nim']?>">
		<input type="text" name="nama_mhs" class="input-medium" placeholder="Nama Mahasiswa" value="<?php echo $r['nama']?>">
		<!--  -->
		<button type="submit" name="ubah" class="btn btn-primary"><i class="icon-ok icon-white"></i> Ubah</button>
	</form>

<?php  // menghapus variabel dari memory
	unset($r);
	} else if ($_GET['page']=='daftar-data-server') {
?>
<legend>Daftar Data Server</legend>
	<form name="form1" method="post" action="proses.php" class="form-inline">
		<input type="hidden" name="aksi" value="sinkronisasi"/>
		<button type="submit" name="sinkronisasi" class="btn btn-primary" onclick="return confirm('Apakah Anda akan melakukan proses sinkronisasi data?')" ><i class="icon-ok icon-white"></i> Sinkronisasi Data</button>
	</form>

	<table class="table table-hover">
	<tr><th width='5%'>No</th>
		<th width='10%'>NIM</th>
		<th width='40%'>Nama</th>
		<th width='40%'>Kelas</th>

	</tr>
	<?php	$no = 1;
		$data_array = $objek->daftar_mhs_server();
		foreach ($data_array as $r)	{
	?>	<tr><td><?php echo $no?></td>
			<td><?php echo $r['nim']?></td>
			<td><?php echo $r['nama']?></td>
			<td><a href="?page=ubah-data&nim=<?php echo $r['nim']?>" role="button" class="btn btn-success"><i class="icon-pencil"></i></a></td>
			<td><a href="proses.php?aksi=hapus&nim=<?php echo $r['nim']?>" role="button" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" ><i class="icon-remove" ></i></a></td>
	    </tr>
	<?php	$no++;
		}

		// menghapus variabel dari memory
		unset($data_array,$r,$no);
	?>
	</table>

<?php } else if ($_GET['page']=='daftar-data-client') { ?>
<legend>Daftar Data Client</legend>
	<table class="table table-hover">
	<tr><th width='10%'>No</th>
		<th width='10%'>NIM</th>
		<th width='80%'>Nama</th>

	</tr>
	<?php	$no = 1;
		$data_array = $objek->daftar_mhs_client();
		foreach ($data_array as $r)	{
	?>	<tr><td><?php echo $no?></td>
			<td><?php echo $r['nim']?></td>
			<td><?php echo $r['nama']?></td>

	    </tr>
	<?php	$no++;
		}

		// menghapus variabel dari memory
		unset($data_array,$r,$no);
	?>
	</table>

<?php } else {
?>
<legend>Home</legend>
	Aplikasi sederhana ini menggunakan web service SOAP (Simple Object Access Protocol) dengan format data XML (Extensible Markup Language).
</fieldset>
</div>
<?php	}
?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/tooltip.js"></script>

<!-- jqBootstrapValidation -->
<script src="js/jqBootstrapValidation.js"></script>
<script>
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>

</body>
</html>
