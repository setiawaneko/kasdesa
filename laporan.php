<?php
include_once 'config.php';
include_once 'Tagihan.php';

$database = new Database();
$db = $database->getConnection();
$tagihan = new Tagihan($db);

// Hitung statistik
$query = "SELECT 
            COUNT(*) as total_tagihan,
            SUM(jumlah) as total_nominal,
            SUM(CASE WHEN status = 'Lunas' THEN jumlah ELSE 0 END) as total_lunas,
            SUM(CASE WHEN status = 'Belum Bayar' THEN jumlah ELSE 0 END) as total_belum_bayar,
            SUM(CASE WHEN status = 'Terlambat' THEN jumlah ELSE 0 END) as total_terlambat
          FROM tagihan";
$stmt = $db->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Data untuk chart
$query_chart = "SELECT status, COUNT(*) as count FROM tagihan GROUP BY status";
$stmt_chart = $db->prepare($query_chart);
$stmt_chart->execute();
$chart_data = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tagihan Kas Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="mb-0"><i class="fas fa-chart-bar"></i> Laporan Tagihan Kas Desa</h3>
                    </div>
                    <div class="card-body">
                        <!-- Statistik -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary">
                                    <div class="card-body">
                                        <h4><?php echo $stats['total_tagihan']; ?></h4>
                                        <p>Total Tagihan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success">
                                    <div class="card-body">
                                        <h4>Rp <?php echo number_format($stats['total_lunas'], 0, ',', '.'); ?></h4>
                                        <p>Total Lunas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning">
                                    <div class="card-body">
                                        <h4>Rp <?php echo number_format($stats['total_belum_bayar'], 0, ',', '.'); ?></h4>
                                        <p>Belum Bayar</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-danger">
                                    <div class="card-body">
                                        <h4>Rp <?php echo number_format($stats['total_terlambat'], 0, ',', '.'); ?></h4>
                                        <p>Terlambat</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="statusChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Ringkasan Keuangan</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><strong>Total Nominal Semua Tagihan</strong></td>
                                                <td class="text-end">Rp <?php echo number_format($stats['total_nominal'], 0, ',', '.'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total yang Sudah Dibayar</strong></td>
                                                <td class="text-end text-success">Rp <?php echo number_format($stats['total_lunas'], 0, ',', '.'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total Tunggakan</strong></td>
                                                <td class="text-end text-danger">Rp <?php echo number_format($stats['total_belum_bayar'] + $stats['total_terlambat'], 0, ',', '.'); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tagihan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart JS
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [<?php foreach($chart_data as $data) echo "'" . $data['status'] . "',"; ?>],
                datasets: [{
                    data: [<?php foreach($chart_data as $data) echo $data['count'] . ","; ?>],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Status Tagihan'
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>