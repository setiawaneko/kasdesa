<?php
class Tagihan {
    private $conn;
    private $table_name = "tagihan";

    public $id;
    public $nomor_tagihan;
    public $nama_pembayar;
    public $alamat;
    public $jenis_tagihan;
    public $jumlah;
    public $tanggal_tagihan;
    public $tanggal_jatuh_tempo;
    public $status;
    public $keterangan;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Generate nomor tagihan otomatis
    private function generateNomorTagihan() {
        $year = date('Y');
        $month = date('m');
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE YEAR(created_at) = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$year]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $sequence = $row['total'] + 1;
        return "TGH/" . $year . "/" . $month . "/" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // Create
    public function create() {
        $this->nomor_tagihan = $this->generateNomorTagihan();
        
        $query = "INSERT INTO " . $this->table_name . "
                SET nomor_tagihan=:nomor_tagihan, nama_pembayar=:nama_pembayar, alamat=:alamat, 
                jenis_tagihan=:jenis_tagihan, jumlah=:jumlah, tanggal_tagihan=:tanggal_tagihan, 
                tanggal_jatuh_tempo=:tanggal_jatuh_tempo, status=:status, keterangan=:keterangan";

        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":nomor_tagihan", $this->nomor_tagihan);
        $stmt->bindParam(":nama_pembayar", $this->nama_pembayar);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":jenis_tagihan", $this->jenis_tagihan);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":tanggal_tagihan", $this->tanggal_tagihan);
        $stmt->bindParam(":tanggal_jatuh_tempo", $this->tanggal_jatuh_tempo);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":keterangan", $this->keterangan);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nomor_tagihan = $row['nomor_tagihan'];
            $this->nama_pembayar = $row['nama_pembayar'];
            $this->alamat = $row['alamat'];
            $this->jenis_tagihan = $row['jenis_tagihan'];
            $this->jumlah = $row['jumlah'];
            $this->tanggal_tagihan = $row['tanggal_tagihan'];
            $this->tanggal_jatuh_tempo = $row['tanggal_jatuh_tempo'];
            $this->status = $row['status'];
            $this->keterangan = $row['keterangan'];
            return true;
        }
        return false;
    }

    // Update
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nama_pembayar=:nama_pembayar, alamat=:alamat, jenis_tagihan=:jenis_tagihan, 
                jumlah=:jumlah, tanggal_tagihan=:tanggal_tagihan, tanggal_jatuh_tempo=:tanggal_jatuh_tempo, 
                status=:status, keterangan=:keterangan
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_pembayar", $this->nama_pembayar);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":jenis_tagihan", $this->jenis_tagihan);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":tanggal_tagihan", $this->tanggal_tagihan);
        $stmt->bindParam(":tanggal_jatuh_tempo", $this->tanggal_jatuh_tempo);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":keterangan", $this->keterangan);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Search
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE nama_pembayar LIKE ? OR nomor_tagihan LIKE ? 
                 ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->execute();
        
        return $stmt;
    }
}
?>