<?php
class APIserver
{	private $host="localhost";
	private $dbname="serviceserver";
	private $conn;

	// koneksi ke database mysql di server
	private $driver="mysql";
	private $user="root";
	private $password="";
	private $port="3306";

	/*
	// koneksi ke database postgresql di server
	private $driver="pgsql";
	private $user="postgres";
	private $password="postgres";
	private $port="5432";
	*/

	// diload pertama kali
	public function __construct()
	{	try
		{	if ($this->driver == 'mysql')
			{	$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8",$this->user,$this->password);
			} elseif ($this->driver == 'pgsql')
			{	$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");
			}
		} catch (PDOException $e)
		{	echo "Koneksi gagal";
		}
	}

	// fungsi menghapus selain huruf dan angka
	public function filter($data)
	{	$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
		unset($data);
	}

	public function tambah_mahasiswa($nim_mhs,$nama_mhs)
	{	// menggunakan fungsi dalam satu class
		$nim_mhs = $this->filter($nim_mhs);

		// query anti sql injection
		$query = $this->conn->prepare("insert into mahasiswa (nim,nama) values (?,?)");
		$query->execute(array($nim_mhs,$nama_mhs));

		// menghapus query dari memory
		$query->closeCursor();
		// $query = null;
		// menghapus variabel dari memory
		unset($nim_mhs,$nama_mhs);
	}

	public function daftar_mahasiswa()
	{	// query
		$query = $this->conn->prepare("select nim, nama from mahasiswa order by nim");
		$query->execute();

		// mengambil banyak record data dengan fetchAll()
		$data = $query->fetchAll(PDO::FETCH_ASSOC);

		// mengembalikan data
		return $data;

		// menghapus query dari memory
		$query->closeCursor();
		// atau bisa menggunakan
		// $query = null;

		// menghapus variabel dari memory
		unset($data);
	}

	public function lihat_mahasiswa($nim)
	{	// menggunakan fungsi dalam satu class
		$nim = $this->filter($nim);

		// query
		$query = $this->conn->prepare("select nim, nama from mahasiswa where nim = ? ");
		$query->execute(array($nim));

		// mengambil satu record data dengan fetch()
		$data=$query->fetch(PDO::FETCH_ASSOC);

		// mengembalikan data
		return $data;

		// menghapus query dari memory
		$query->closeCursor();
		// menghapus variabel dari memory
		unset($nim,$data);
	}

	public function ubah_mahasiswa($nim_mhs,$nama_mhs)
	{	// menggunakan fungsi dalam satu class
		$nim_mhs = $this->filter($nim_mhs);

		// query
		$query = $this->conn->prepare("update mahasiswa set nama = ? where nim = ? ");
		$query->execute(array($nama_mhs,$nim_mhs));

		// menghapus query dari memory
		$query->closeCursor();
		// menghapus variabel dari memory
		unset($nim_mhs,$nama_mhs);
	}

	public function hapus_mahasiswa($nim_mhs)
	{	// menggunakan fungsi dalam satu class
		$nim_mhs = $this->filter($nim_mhs);

		// query
		$query = $this->conn->prepare("delete from mahasiswa where nim = ? ");
		$query->execute(array($nim_mhs));

		// menghapus query dari memory
		$query->closeCursor();
		// menghapus variabel dari memory
		unset($nim_mhs);
	}

}

// set uri server
$options=array('uri'=>'http://localhost');
//$options=array('uri'=>'http://192.168.1.6');

// buat objek baru dari class Soap Server
$server = new SoapServer(NULL,$options);

// masukkan class API server ke objek SOAP Server
$server->setClass('APIserver');

// jalankan menggunakan SOAP requests handler
$server->handle();

?>
