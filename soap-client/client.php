<?php
class client
{	private $host="localhost";
	private $dbname="serviceclient";
	private $conn;
	private $options,$api;

	// koneksi ke database mysql di client
	private $driver="mysql";
	private $user="root";
	private $password="";
	private $port="3306";

	/*
	// koneksi ke database postgresql di client
	private $driver="pgsql";
	private $user="postgres";
	private $password="postgres";
	private $port="5432";
	*/

	// diload pertama kali
	public function __construct()
	{	// set uri SOAP server
		$this->options = array('location' => 'http://localhost/soap-server/server.php','uri' => 'http://localhost');
		//$this->options = array('location' => 'http://192.168.1.6/soap/server.php','uri' => 'http://192.168.1.6');

		// buat objek baru dari class SOAP Client
		$this->api = new SoapClient(NULL, $this->options);


		// koneksi database lokal client
		try
		{	if ($this->driver == 'mysql')
			{	$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8",$this->user,$this->password);
			} elseif ($this->driver == 'pgsql')
			{	$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");
			}
		} catch (PDOException $e)
		{	echo "Koneksi gagal";
		}
	}

	public function tambah_mhs($nim_mhs,$nama_mhs)
	{	// memanggil method/fungsi yang ada di server
		$this->api->tambah_mahasiswa($nim_mhs,$nama_mhs);

		// menghapus variabel dari memory
		unset($nim_mhs,$nama_mhs,$kelas_mhs,$jurusan,$alamat_mhs);
	}

	public function daftar_mhs_server()
	{	// memanggil method/fungsi yang ada di server dan dimasukkan ke variabel $data
		$data = $this->api->daftar_mahasiswa();

		// mengembalikan data
		return $data;

		// menghapus variabel dari memory
		unset($data);
	}

	public function lihat_mhs($nim)
	{	// memanggil method/fungsi yang ada di server dan dimasukkan ke variabel $data
		$data = $this->api->lihat_mahasiswa($nim);

		// mengembalikan data
		return $data;

		// menghapus variabel dari memory
		unset($nim,$data);
	}

	public function ubah_mhs($nim_mhs,$nama_mhs)
	{	// memanggil method/fungsi yang ada di server
		$this->api->ubah_mahasiswa($nim_mhs,$nama_mhs);

		// menghapus variabel dari memory
		unset($nim_mhs,$nama_mhs);
	}

	public function hapus_mhs($nim_mhs)
	{	// memanggil method/fungsi yang ada di server
		$this->api->hapus_mahasiswa($nim_mhs);

		// menghapus variabel dari memory
		unset($nim_mhs);
	}

	public function sinkronisasi()
	{	// query ke lokal database client
		$query = $this->conn->prepare("delete from mahasiswa ");
		$query->execute();
		// menghapus query dari memory
		$query->closeCursor();

		// memanggil method/fungsi yang ada di server dan dimasukkan ke variabel $data
		$data = $this->api->daftar_mahasiswa();
		foreach ($data as $r)
		{	// query ke lokal database client
			$query = $this->conn->prepare("insert into mahasiswa (nim,nama) values (?,?)");
			$query->execute(array($r['nim'],$r['nama']));

			// menghapus query dari memory
			$query->closeCursor();
		}

		// menghapus variabel dari memory
		unset($data,$r);
	}

	public function daftar_mhs_client()
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

	// diload terakhir kali
	public function __destruct()
	{	// menghapus variabel $api dari memory
		unset($this->api);
	}

}

// buat objek baru dari class Client
$objek = new client();

?>
