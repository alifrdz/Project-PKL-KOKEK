<?php
require_once 'koneksi.php';

// Get ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query destinasi
$query = "SELECT * FROM destinasi WHERE id = $id";
$result = mysqli_query($conn, $query);

// Check if data exists
if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$data = mysqli_fetch_assoc($result);

// === LOGIKA SMART IMAGE (Sama seperti Index) ===
$imagePath = $data['gambar'] ? 'uploads/' . $data['gambar'] : '';
$nama = strtolower($data['nama_wisata']);

// Cek fisik file
if ($imagePath && file_exists($imagePath)) {
    // File ada, gunakan
} else {
    // File tidak ada, gunakan Smart Fallback Unsplash
    if (strpos($nama, 'bromo') !== false) {
        $imagePath = 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=1200&auto=format&fit=crop';
    } elseif (strpos($nama, 'borobudur') !== false) {
        $imagePath = 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?q=80&w=1200&auto=format&fit=crop';
    } elseif (strpos($nama, 'kuta') !== false || strpos($nama, 'pantai') !== false) {
        $imagePath = 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=1200&auto=format&fit=crop';
    } else {
        $imagePath = 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=1200&auto=format&fit=crop';
    }
}

// Logika Badge Warna
$badgeClass = 'bg-secondary';
if($data['jenis_daya_tarik'] == 'Alam') $badgeClass = 'bg-success';
elseif($data['jenis_daya_tarik'] == 'Buatan') $badgeClass = 'bg-primary';
elseif($data['jenis_daya_tarik'] == 'Budaya') $badgeClass = 'bg-danger';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['nama_wisata']); ?> - DolanKuy</title>
    
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="background-color: #F5F6F8;">

    <nav class="navbar navbar-expand-lg navbar-tiket fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">DolanKuy<span class="text-warning">.</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#destinasi">Destinasi</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-login-nav" href="login.php"><i class="fas fa-user-circle me-1"></i> Login Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="margin-top: 100px;">
        
        <div class="container mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($data['nama_wisata']); ?></li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <h1 class="fw-bold m-0" style="font-family: 'DM Sans', sans-serif;"><?php echo htmlspecialchars($data['nama_wisata']); ?></h1>
                <span class="badge rounded-pill <?php echo $badgeClass; ?> px-3 py-2">
                    <?php echo $data['jenis_daya_tarik']; ?>
                </span>
            </div>
            <p class="text-muted mt-2">
                <i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo htmlspecialchars($data['kabupaten_kota']); ?>
            </p>
        </div>

        <div class="container pb-5">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="bg-white p-2 rounded-4 shadow-sm mb-4">
                        <img src="<?php echo $imagePath; ?>" class="w-100 rounded-3" style="height: 400px; object-fit: cover;" alt="Foto Wisata">
                    </div>

                    <div class="bg-white p-4 rounded-4 shadow-sm">
                        <h4 class="fw-bold mb-3">Tentang Tempat Ini</h4>
                        <p style="line-height: 1.8; color: #4a4a4a; text-align: justify;">
                            <?php echo nl2br(htmlspecialchars($data['deskripsi'])); ?>
                        </p>

                        <hr class="my-4" style="opacity: 0.1;">

                        <h5 class="fw-bold mb-3">Fasilitas Tersedia</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php 
                            $fasilitas = explode(',', $data['fasilitas']);
                            foreach($fasilitas as $f): 
                                $f = trim($f);
                                if(!empty($f)):
                            ?>
                                <span class="badge bg-light text-dark border px-3 py-2 fw-normal">
                                    <i class="fas fa-check-circle text-success me-1"></i> <?php echo $f; ?>
                                </span>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm position-sticky" style="top: 100px;">
                        <h5 class="fw-bold mb-4">Informasi Penting</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Jam Operasional</label>
                            <div class="d-flex align-items-center mt-1">
                                <i class="far fa-clock text-primary me-2 fa-lg"></i>
                                <span class="fw-bold"><?php echo htmlspecialchars($data['jam_operasional']); ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Kontak</label>
                            <div class="d-flex align-items-center mt-1">
                                <i class="fas fa-phone-alt text-primary me-2 fa-lg"></i>
                                <span><?php echo htmlspecialchars($data['no_telp']); ?></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small fw-bold text-uppercase">Alamat Lengkap</label>
                            <div class="d-flex mt-1">
                                <i class="fas fa-map-pin text-primary me-2 fa-lg mt-1"></i>
                                <span class="text-muted" style="font-size: 0.95rem;"><?php echo htmlspecialchars($data['alamat']); ?></span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <?php if($data['sumber_link']): ?>
                                <a href="<?php echo htmlspecialchars($data['sumber_link']); ?>" target="_blank" class="btn btn-outline-primary fw-bold py-2 rounded-pill">
                                    <i class="fas fa-globe me-2"></i> Website Resmi
                                </a>
                            <?php endif; ?>
                            
                            <?php if($data['link_sosmed']): ?>
                                <a href="<?php echo htmlspecialchars($data['link_sosmed']); ?>" target="_blank" class="btn btn-primary fw-bold py-2 rounded-pill" style="background-color: var(--primary);">
                                    <i class="fab fa-instagram me-2"></i> Instagram / Sosmed
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <footer class="footer-tiket mt-auto">
        <div class="container">
            <div class="row justify-content-between align-items-center py-5">
                
                <div class="col-lg-5 text-center text-lg-start mb-4 mb-lg-0">
                    <h3 class="fw-bold text-white mb-2">DolanKuy<span class="text-warning">.</span></h3>
                    <p class="text-white-50 mb-3">Teman perjalanan terbaikmu menjelajahi Nusantara.</p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-5 text-center text-lg-end">
                    <h5 class="fw-bold text-white mb-3">Butuh Bantuan?</h5>
                    <ul class="list-unstyled text-white-50 mb-0">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> hello@dolankuy.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +62 812-3456-7890</li>
                        <li class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Surabaya, Indonesia</li>
                    </ul>
                </div>

            </div>

            <div class="border-top border-white-10 py-4 text-center">
                <p class="small text-white-50 m-0">&copy; <?php echo date('Y'); ?> DolanKuy Project. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>