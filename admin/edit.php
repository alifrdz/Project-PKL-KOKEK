<?php
session_start();
require_once '../koneksi.php';

// 1. CEK LOGIN YANG BENAR (Sesuai login.php)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php?pesan=belum_login");
    exit;
}

// 2. AMBIL ID & DATA
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM destinasi WHERE id = $id";
$result = mysqli_query($conn, $query);

// Kalau ID ga ketemu, balikin ke dashboard
if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data - DolanKuy Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #0064D2;
            --bg-light: #F5F6F8;
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5 { font-family: 'DM Sans', sans-serif; font-weight: 700; }
        
        /* SIDEBAR FIXED */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(180deg, #0064D2 0%, #004eb3 100%); 
            color: white;
            z-index: 1000;
            overflow: hidden;
            box-shadow: 4px 0 20px rgba(0, 100, 210, 0.1);
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
        
        /* CONTENT AREA */
        #page-content-wrapper {
            margin-left: var(--sidebar-width);
            padding: 40px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        /* FORM CARD STYLE */
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            padding: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 100, 210, 0.1);
        }

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
        }
    </style>
</head>
<body>

    <div id="sidebar-wrapper">
        <div class="sidebar-heading">
            <i class="fas fa-mountain me-2 text-white"></i> DolanKuy<span class="text-warning">.</span>
        </div>
        <div class="list-group list-group-flush mt-4 d-flex flex-column h-100">
            <div>
                <a href="index.php" class="list-group-item list-group-item-action">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Edit Destinasi</h2>
                <p class="text-muted m-0">Perbarui data wisata: <strong><?php echo htmlspecialchars($data['nama_wisata']); ?></strong></p>
            </div>
            <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="form-card">
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo $data['gambar']; ?>">

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Wisata</label>
                            <input type="text" class="form-control" name="nama_wisata" value="<?php echo htmlspecialchars($data['nama_wisata']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kode Wisata</label>
                            <input type="text" class="form-control bg-light" name="kode_wisata" value="<?php echo htmlspecialchars($data['kode_wisata']); ?>" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="jenis_daya_tarik" required>
                                    <option value="Alam" <?php echo $data['jenis_daya_tarik'] == 'Alam' ? 'selected' : ''; ?>>Alam</option>
                                    <option value="Buatan" <?php echo $data['jenis_daya_tarik'] == 'Buatan' ? 'selected' : ''; ?>>Buatan</option>
                                    <option value="Budaya" <?php echo $data['jenis_daya_tarik'] == 'Budaya' ? 'selected' : ''; ?>>Budaya</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kota/Kabupaten</label>
                                <input type="text" class="form-control" name="kabupaten_kota" value="<?php echo htmlspecialchars($data['kabupaten_kota']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat" rows="3" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="5" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fasilitas</label>
                            <input type="text" class="form-control" name="fasilitas" value="<?php echo htmlspecialchars($data['fasilitas']); ?>" placeholder="Contoh: Parkir, Musholla, Toilet">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Operasional</label>
                                <input type="text" class="form-control" name="jam_operasional" value="<?php echo htmlspecialchars($data['jam_operasional']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telp" value="<?php echo htmlspecialchars($data['no_telp']); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 border-top pt-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Link Website/Sosmed</label>
                                <input type="text" class="form-control" name="link_sosmed" value="<?php echo htmlspecialchars($data['link_sosmed']); ?>" placeholder="https://...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Upload Gambar Baru (Opsional)</label>
                                <input type="file" class="form-control" name="gambar" accept="image/*" onchange="previewImage(this)">
                                
                                <div class="mt-3 d-flex align-items-center gap-3">
                                    <div class="text-center">
                                        <small class="d-block mb-1 text-muted">Gambar Saat Ini:</small>
                                        <?php 
                                            $imgShow = $data['gambar'] ? '../uploads/'.$data['gambar'] : 'https://via.placeholder.com/100';
                                            if($data['gambar'] && !file_exists('../uploads/'.$data['gambar'])) $imgShow = 'https://via.placeholder.com/100?text=File+Hilang';
                                        ?>
                                        <img src="<?php echo $imgShow; ?>" class="rounded shadow-sm" style="width: 100px; height: 70px; object-fit: cover;">
                                    </div>
                                    <div class="text-center" id="previewBox" style="display: none;">
                                        <small class="d-block mb-1 text-success fw-bold">Akan Diganti:</small>
                                        <img id="imgPreview" src="" class="rounded shadow-sm" style="width: 100px; height: 70px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const previewBox = document.getElementById('previewBox');
            const previewImg = document.getElementById('imgPreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewBox.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                previewBox.style.display = 'none';
            }
        }
    </script>
</body>
</html>