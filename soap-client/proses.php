<?php
include "client.php";

if ($_POST['aksi']=='tambah_data')
{	// tambah data
	$objek->tambah_mhs($_POST['nim'],$_POST['nama_mhs']);
	header('location:index.php?page=daftar-data-server');
} else if ($_POST['aksi']=='ubah_data')
{	//ubah data
	$objek->ubah_mhs($_POST['nim'],$_POST['nama_mhs']);
	header('location:index.php?page=daftar-data-server');
} else if ($_GET['aksi']=='hapus')
{	// hapus data
	$objek->hapus_mhs($_GET['nim']);
	header('location:index.php?page=daftar-data-server');
} else if ($_POST['aksi']=='sinkronisasi')
{	// sinkronisasi data
	$objek->sinkronisasi();
	header('location:index.php?page=daftar-data-client');
}

?>
