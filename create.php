<?php
include_once 'config.php';
include_once 'Tagihan.php';

$database = new Database();
$db = $database->getConnection();
$tagihan = new Tagihan($db);

if($_POST){
    $tagihan->nama_pembayar = $_POST['nama_pembayar'];
    $tagihan->alamat = $_POST['alamat'];
    $tagihan->jenis_tagihan = $_POST['jenis_tagihan'];
    $tagihan->jumlah = $_POST['jumlah'];
    $tagihan->tanggal_tagihan = $_POST['tanggal_tagihan'];
    $tagihan->tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
    $tagihan->status = $_POST['status'];
    $tagihan->keterangan = $_POST['keterangan'];
    
    if($tagihan->create()){
        echo "<div class='alert alert-success'>Tagihan berhasil dibuat.</div>";
    } else{
        echo "<div class='alert alert-danger'>Gagal membuat tagihan.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tagihan Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-plus"></i> Tambah Tagihan Baru</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nama Pembayar *</label>
                                <input type="text" name="nama_pembayar" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jenis Tagihan *</label>
                                <select name="jenis_tagihan" class="form-control" required>
                                    <option value="">Pilih Jenis Tagihan</option>
                                    <option value="Iuran Wajib">Iuran Wajib</option>
                                    <option value="Sumbangan">Sumbangan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah *</label>
                                <input type="number" name="jumlah" class="form-control" step="0.01" required>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Tagihan *</label>
                                    <input type="date" name="tanggal_tagihan" class="form-control" required 
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Jatuh Tempo *</label>
                                    <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-control" required>
                                    <option value="Belum Bayar">Belum Bayar</option>
                                    <option value="Lunas">Lunas</option>
                                    <option value="Terlambat">Terlambat</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>