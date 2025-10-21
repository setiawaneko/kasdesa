<?php
include_once 'config.php';
include_once 'Tagihan.php';

$database = new Database();
$db = $database->getConnection();
$tagihan = new Tagihan($db);

$tagihan->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

$tagihan->readOne();

if($_POST){
    $tagihan->nama_pembayar = $_POST['nama_pembayar'];
    $tagihan->alamat = $_POST['alamat'];
    $tagihan->jenis_tagihan = $_POST['jenis_tagihan'];
    $tagihan->jumlah = $_POST['jumlah'];
    $tagihan->tanggal_tagihan = $_POST['tanggal_tagihan'];
    $tagihan->tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
    $tagihan->status = $_POST['status'];
    $tagihan->keterangan = $_POST['keterangan'];
    
    if($tagihan->update()){
        echo "<div class='alert alert-success'>Tagihan berhasil diupdate.</div>";
    } else{
        echo "<div class='alert alert-danger'>Gagal mengupdate tagihan.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tagihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Tagihan</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$tagihan->id}"); ?>" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nomor Tagihan</label>
                                <input type="text" class="form-control" value="<?php echo $tagihan->nomor_tagihan; ?>" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Pembayar *</label>
                                <input type="text" name="nama_pembayar" class="form-control" 
                                       value="<?php echo $tagihan->nama_pembayar; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3"><?php echo $tagihan->alamat; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jenis Tagihan *</label>
                                <select name="jenis_tagihan" class="form-control" required>
                                    <option value="Iuran Wajib" <?php echo $tagihan->jenis_tagihan == 'Iuran Wajib' ? 'selected' : ''; ?>>Iuran Wajib</option>
                                    <option value="Sumbangan" <?php echo $tagihan->jenis_tagihan == 'Sumbangan' ? 'selected' : ''; ?>>Sumbangan</option>
                                    <option value="Lainnya" <?php echo $tagihan->jenis_tagihan == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah *</label>
                                <input type="number" name="jumlah" class="form-control" step="0.01" 
                                       value="<?php echo $tagihan->jumlah; ?>" required>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Tagihan *</label>
                                    <input type="date" name="tanggal_tagihan" class="form-control" 
                                           value="<?php echo $tagihan->tanggal_tagihan; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Jatuh Tempo *</label>
                                    <input type="date" name="tanggal_jatuh_tempo" class="form-control" 
                                           value="<?php echo $tagihan->tanggal_jatuh_tempo; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-control" required>
                                    <option value="Belum Bayar" <?php echo $tagihan->status == 'Belum Bayar' ? 'selected' : ''; ?>>Belum Bayar</option>
                                    <option value="Lunas" <?php echo $tagihan->status == 'Lunas' ? 'selected' : ''; ?>>Lunas</option>
                                    <option value="Terlambat" <?php echo $tagihan->status == 'Terlambat' ? 'selected' : ''; ?>>Terlambat</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"><?php echo $tagihan->keterangan; ?></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-warning">Update</button>
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