<?php
session_start();
require_once '../koneksi.php';

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php?pesan=belum_login");
    exit;
}

// Data Statistik
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM destinasi"));
$alam = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM destinasi WHERE jenis_daya_tarik='Alam'"));
$buatan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM destinasi WHERE jenis_daya_tarik='Buatan'"));
$budaya = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM destinasi WHERE jenis_daya_tarik='Budaya'"));

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $q = mysqli_query($conn, "SELECT gambar FROM destinasi WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);
    if ($data['gambar'] && file_exists('../uploads/' . $data['gambar'])) {
        unlink('../uploads/' . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM destinasi WHERE id='$id'");
    header("location:index.php");
}

$result = mysqli_query($conn, "SELECT * FROM destinasi ORDER BY tanggal_input DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - DolanKuy</title>
    
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
<style>
        :root {
            --primary: #0064D2; /* Tiket Blue */
            --bg-light: #F5F6F8;
            --sidebar-width: 280px; /* Kita kunci lebarnya di sini */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden; /* Biar gak ada scroll samping */
        }
        
        h1, h2, h3, h4, h5 { font-family: 'DM Sans', sans-serif; font-weight: 700; }
        
/* SIDEBAR FIXED (ANTI SCROLL) */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            height: 100vh; /* Tinggi Full Layar */
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #0064D2 0%, #004eb3 100%); 
            color: white;
            z-index: 1000;
            
            /* INI PERUBAHANNYA: Hidden = Gak ada scrollbar sama sekali */
            overflow: hidden; 
            
            box-shadow: 4px 0 20px rgba(0, 100, 210, 0.1);
            display: flex;
            flex-direction: column; /* Biar isinya rapi atas-bawah */
        }
        
        .sidebar-heading {
            padding: 2.5rem 2rem 2rem;
            font-size: 1.5rem;
            color: white;
            font-weight: 800;
        }
        
        /* MENU ITEMS */
        .list-group-item {
            background: transparent;
            color: rgba(255,255,255,0.8);
            border: none;
            padding: 14px 24px;
            font-weight: 500;
            margin: 4px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        
        .list-group-item:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .list-group-item.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-weight: 700;
        }
        
        /* LOGOUT BUTTON */
        .btn-logout-custom {
            background: rgba(255, 255, 255, 0.1);
            color: #ffcccc !important;
            border-radius: 12px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-logout-custom:hover {
            background: #ef4444;
            color: white !important;
            transform: translateY(-2px);
        }

        /* CONTENT AREA (DIGESER KE KANAN) */
        #page-content-wrapper {
            margin-left: var(--sidebar-width); /* Geser biar gak ketutupan sidebar */
            padding: 40px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        /* CARD STATISTIK */
        .stat-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            transition: transform 0.3s;
            height: 100%;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card h2 { font-size: 2.5rem; margin-bottom: 0; color: #1e293b; margin-top: 10px; }
        .stat-card p { color: #64748b; margin-bottom: 0; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
        
        .card-blue { border-bottom: 4px solid #3b82f6; }
        .card-green { border-bottom: 4px solid #10b981; }
        .card-purple { border-bottom: 4px solid #8b5cf6; }
        .card-orange { border-bottom: 4px solid #f59e0b; }

        /* TABLE */
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            padding: 30px;
        }
        .table thead th {
            background-color: white;
            color: #94a3b8;
            border-bottom: 2px solid #f1f5f9;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding-bottom: 15px;
        }
        .table tbody td {
            padding: 20px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
        }
        .img-thumb { width: 50px; height: 50px; border-radius: 12px; object-fit: cover; }
        
        /* Action Buttons */
        .btn-action {
            width: 35px; height: 35px; border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            border: none; transition: 0.2s;
        }
        .btn-edit { background: #E0F2FE; color: #0284C7; }
        .btn-delete { background: #FEE2E2; color: #DC2626; }
        .btn-action:hover { transform: scale(1.1); }
    </style>
</head>
<body>

    <div class="d-flex" id="wrapper">
        
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="fas fa-mountain me-2 text-white"></i> DolanKuy<span class="text-warning">.</span>
            </div>
            
            <div class="list-group list-group-flush mt-4 d-flex flex-column h-100">
                <div>
                    <a href="index.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-home me-3" style="width:20px"></i>Dashboard
                    </a>
                    <a href="tambah.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus-square me-3" style="width:20px"></i>Tambah Data
                    </a>
                    <a href="../index.php" target="_blank" class="list-group-item list-group-item-action">
                        <i class="fas fa-external-link-alt me-3" style="width:20px"></i>Lihat Website
                    </a>
                </div>

                <div class="mt-4 px-3">
                    <p class="text-white-50 fw-bold small mb-2" style="font-size: 0.7rem;">AKUN</p>
                    <a href="logout.php" class="btn-logout-custom">
                        <i class="fas fa-sign-out-alt me-3"></i>Logout
                    </a>
                </div>
            </div>
        </div>
        <div id="page-content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="mb-1">Dashboard Overview</h2>
                    <p class="text-muted m-0">Selamat datang kembali, <strong><?php echo $_SESSION['username']; ?></strong> 👋</p>
                </div>
                <a href="tambah.php" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                    <i class="fas fa-plus me-2"></i> Input Destinasi
                </a>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="stat-card card-blue">
                        <p>Total Destinasi</p>
                        <h2><?php echo $total; ?></h2>
                        <i class="fas fa-map-marked-alt fa-3x text-primary opacity-25 position-absolute end-0 bottom-0 m-3"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card card-green">
                        <p>Wisata Alam</p>
                        <h2><?php echo $alam; ?></h2>
                        <i class="fas fa-tree fa-3x text-success opacity-25 position-absolute end-0 bottom-0 m-3"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card card-purple">
                        <p>Wisata Buatan</p>
                        <h2><?php echo $buatan; ?></h2>
                        <i class="fas fa-water fa-3x text-info opacity-25 position-absolute end-0 bottom-0 m-3"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card card-orange">
                        <p>Wisata Budaya</p>
                        <h2><?php echo $budaya; ?></h2>
                        <i class="fas fa-landmark fa-3x text-warning opacity-25 position-absolute end-0 bottom-0 m-3"></i>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold">Data Destinasi Terbaru</h5>
                    <small class="text-muted"><i class="fas fa-sync-alt me-1"></i> Realtime Data</small>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Visual</th>
                                <th width="25%">Nama Wisata</th>
                                <th width="15%">Kategori</th>
                                <th width="20%">Lokasi</th>
                                <th width="15%">Tgl Input</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): 
                                $badge = 'bg-secondary';
                                if($row['jenis_daya_tarik'] == 'Alam') $badge = 'bg-success';
                                if($row['jenis_daya_tarik'] == 'Buatan') $badge = 'bg-info';
                                if($row['jenis_daya_tarik'] == 'Budaya') $badge = 'bg-warning text-dark';
                                
                                $img = 'https://via.placeholder.com/50';
                                if($row['gambar'] && file_exists('../uploads/'.$row['gambar'])) {
                                    $img = '../uploads/'.$row['gambar'];
                                } elseif (stripos($row['nama_wisata'], 'bromo') !== false) {
                                    $img = 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=100';
                                } elseif (stripos($row['nama_wisata'], 'borobudur') !== false) {
                                    $img = 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=100';
                                } elseif (stripos($row['nama_wisata'], 'kuta') !== false) {
                                    $img = 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=100';
                                }
                            ?>
                            <tr>
                                <td class="text-center text-muted"><?php echo $no++; ?></td>
                                <td><img src="<?php echo $img; ?>" class="img-thumb shadow-sm"></td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo $row['nama_wisata']; ?></div>
                                    <small class="text-muted" style="font-size: 0.75rem"><?php echo $row['kode_wisata']; ?></small>
                                </td>
                                <td><span class="badge rounded-pill <?php echo $badge; ?>"><?php echo $row['jenis_daya_tarik']; ?></span></td>
                                <td class="text-muted"><i class="fas fa-map-marker-alt me-2 text-danger"></i><?php echo $row['kabupaten_kota']; ?></td>
                                <td class="text-muted small"><?php echo date('d M Y', strtotime($row['tanggal_input'])); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-pen"></i></a>
                                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Hapus data?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>