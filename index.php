<?php
include_once 'config.php';
include_once 'Tagihan.php';

$database = new Database();
$db = $database->getConnection();
$tagihan = new Tagihan($db);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 5;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$stmt = $tagihan->read();
$num = $stmt->rowCount();

$search_keyword = '';
if(isset($_POST['search'])) {
    $search_keyword = $_POST['search_keyword'];
    $stmt = $tagihan->search($search_keyword);
    $num = $stmt->rowCount();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tagihan Kas Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-lunas { background-color: #d4edda; }
        .status-belum-bayar { background-color: #fff3cd; }
        .status-terlambat { background-color: #f8d7da; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-receipt"></i> Sistem Tagihan Kas Desa</h3>
                    </div>
                    <div class="card-body">
                        <!-- Search Form -->
                        <form method="POST" class="mb-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="search_keyword" class="form-control" 
                                           placeholder="Cari berdasarkan nama atau nomor tagihan..." 
                                           value="<?php echo $search_keyword; ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="search" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="index.php" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Action Buttons -->
                        <div class="mb-3">
                            <a href="create.php" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Tagihan Baru
                            </a>
                            <a href="laporan.php" class="btn btn-info">
                                <i class="fas fa-chart-bar"></i> Laporan
                            </a>
                        </div>

                        <!-- Table -->
                        <?php if($num > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>No. Tagihan</th>
                                        <th>Nama Pembayar</th>
                                        <th>Jenis Tagihan</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Jatuh Tempo</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                                        $status_class = '';
                                        switch($row['status']) {
                                            case 'Lunas': $status_class = 'status-lunas'; break;
                                            case 'Belum Bayar': $status_class = 'status-belum-bayar'; break;
                                            case 'Terlambat': $status_class = 'status-terlambat'; break;
                                        }
                                    ?>
                                    <tr class="<?php echo $status_class; ?>">
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nomor_tagihan']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_pembayar']); ?></td>
                                        <td><?php echo htmlspecialchars($row['jenis_tagihan']); ?></td>
                                        <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_jatuh_tempo'])); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php 
                                                if($row['status'] == 'Lunas') echo 'bg-success';
                                                elseif($row['status'] == 'Belum Bayar') echo 'bg-warning';
                                                else echo 'bg-danger';
                                                ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="read.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Yakin ingin menghapus tagihan ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Tidak ada data tagihan ditemukan.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>