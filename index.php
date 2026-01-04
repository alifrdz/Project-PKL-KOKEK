<?php
require_once 'koneksi.php';

// Handle search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query destinasi
if ($search) {
    $query = "SELECT * FROM destinasi WHERE 
              nama_wisata LIKE '%$search%' OR 
              kabupaten_kota LIKE '%$search%' OR 
              jenis_daya_tarik LIKE '%$search%' 
              ORDER BY tanggal_input DESC";
} else {
    $query = "SELECT * FROM destinasi ORDER BY tanggal_input DESC LIMIT 12";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DolanKuy - Temukan Wisata Impianmu</title>
    
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-tiket fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">DolanKuy<span class="text-warning">.</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destinasi">Destinasi</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-login-nav" href="login.php">
                            <i class="fas fa-user-circle me-1"></i> Login Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-banner-wrapper">
        <div class="hero-banner-image">
            <div class="container h-100 d-flex align-items-center">
                <div class="hero-text-content text-white text-center text-md-start">
                    <h1 class="hero-title display-4">Hai kamu, mau ke mana?</h1>
                    <p class="lead fw-medium mb-4" style="opacity: 0.9;">Temukan destinasi wisata terbaik di seluruh Nusantara.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="search-widget-wrapper">
        <div class="container">
            <div class="search-widget-box">
                <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4 fw-bold gap-2 d-flex align-items-center" type="button">
                            <i class="fas fa-map-marked-alt"></i> Destinasi Wisata
                        </button>
                    </li>
                </ul>
                
                <form action="index.php" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-9">
                            <label class="form-label fw-bold text-muted small text-uppercase ps-3 mb-1">Mau cari wisata apa?</label>
                            <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                                <span class="input-group-text bg-white border-0 ps-4 text-primary"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control border-0 py-3 fw-medium" 
                                       placeholder="Cari Bromo, Pantai, atau Kota..." 
                                       value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm btn-cari-tiket">
                                Cari Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light" id="destinasi">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="main-section-title fw-bold mb-0">
                        <?php echo $search ? "Hasil Pencarian: \"$search\"" : "Destinasi Populer"; ?>
                    </h2>
                    <p class="text-muted mt-2">Rekomendasi tempat liburan seru buat kamu</p>
                </div>
            </div>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col">
                    <div class="card h-100 card-wisata-tiket">
                        <div class="card-img-wrapper">
                            <?php 
                            // 1. Cek apakah ada file yang diupload user di folder uploads/
                            if ($row['gambar'] && file_exists('uploads/' . $row['gambar'])) {
                                $imagePath = 'uploads/' . $row['gambar'];
                            } else {
                                // 2. Kalau TIDAK ADA file, kita pakai "Smart Placeholder" dari Unsplash
                                // Cek nama wisatanya mengandung kata apa?
                                $nama = strtolower($row['nama_wisata']);
                                
                                if (strpos($nama, 'bromo') !== false) {
                                    // Kalau ada kata 'bromo', pakai foto Bromo
                                    $imagePath = 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=800&auto=format&fit=crop';
                                } elseif (strpos($nama, 'borobudur') !== false) {
                                    // Kalau ada kata 'borobudur', pakai foto Candi
                                    $imagePath = 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?q=80&w=800&auto=format&fit=crop';
                                } elseif (strpos($nama, 'kuta') !== false || strpos($nama, 'pantai') !== false) {
                                    // Kalau ada kata 'kuta' atau 'pantai', pakai foto Pantai
                                    $imagePath = 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=800&auto=format&fit=crop';
                                } else {
                                    // Kalau nama lain, pakai foto Travel Random yang estetik
                                    $imagePath = 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=800&auto=format&fit=crop';
                                }
                            }
                            ?>
                            <span class="badge-tiket">
                                <?php echo $row['jenis_daya_tarik']; ?>
                            </span>
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($row['nama_wisata']); ?>">
                        </div>
                        <div class="card-body card-body-tiket">
                            <h5 class="card-title card-title-tiket" title="<?php echo htmlspecialchars($row['nama_wisata']); ?>">
                                <?php echo htmlspecialchars($row['nama_wisata']); ?>
                            </h5>
                            <p class="card-text card-location-tiket">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i><?php echo htmlspecialchars($row['kabupaten_kota']); ?>
                            </p>
                            <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-detail-tiket">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/search-result-not-found-2130361-1800925.png" alt="Not Found" style="width: 200px; opacity: 0.7;">
                <h4 class="mt-3 fw-bold text-muted">Yah, destinasinya nggak ketemu :(</h4>
                <p class="text-muted">Coba cari pakai kata kunci lain ya.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

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
    
    <script src="assets/script.js"></script>
</body>
</html>