<?php
session_start();
require_once '../koneksi.php';

// 1. PERBAIKAN SESI LOGIN (Sesuai dengan login.php)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php?pesan=belum_login");
    exit;
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $id = intval($_POST['id']);
    $kode_wisata = mysqli_real_escape_string($conn, $_POST['kode_wisata']);
    $nama_wisata = mysqli_real_escape_string($conn, $_POST['nama_wisata']);
    $jenis_daya_tarik = mysqli_real_escape_string($conn, $_POST['jenis_daya_tarik']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $sumber_link = mysqli_real_escape_string($conn, $_POST['sumber_link']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kabupaten_kota = mysqli_real_escape_string($conn, $_POST['kabupaten_kota']);
    $jam_operasional = mysqli_real_escape_string($conn, $_POST['jam_operasional']);
    $fasilitas = mysqli_real_escape_string($conn, $_POST['fasilitas']);
    $link_sosmed = mysqli_real_escape_string($conn, $_POST['link_sosmed']);
    $gambar_lama = $_POST['gambar_lama'];
    
    // Handle file upload (if new image uploaded)
    $gambar = $gambar_lama; // Keep old image by default
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp'); // Saya tambah webp biar support modern format
        
        if (in_array($file_ext, $allowed)) {
            if ($file_size <= 2097152) { // 2MB
                // Generate unique filename
                $gambar = uniqid() . '_' . time() . '.' . $file_ext;
                $upload_path = '../uploads/' . $gambar;
                
                // Create uploads directory if not exists
                if (!file_exists('../uploads/')) {
                    mkdir('../uploads/', 0777, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Delete old image (Hapus gambar lama biar gak nyampah)
                    if ($gambar_lama && file_exists('../uploads/' . $gambar_lama)) {
                        unlink('../uploads/' . $gambar_lama);
                    }
                } else {
                    echo "<script>alert('Gagal upload gambar!'); window.history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file tidak diizinkan! Gunakan JPG, PNG, atau GIF'); window.history.back();</script>";
            exit;
        }
    }
    
    // Update database
    $query = "UPDATE destinasi SET 
                kode_wisata = '$kode_wisata',
                nama_wisata = '$nama_wisata',
                jenis_daya_tarik = '$jenis_daya_tarik',
                no_telp = '$no_telp',
                deskripsi = '$deskripsi',
                sumber_link = '$sumber_link',
                alamat = '$alamat',
                kabupaten_kota = '$kabupaten_kota',
                jam_operasional = '$jam_operasional',
                fasilitas = '$fasilitas',
                link_sosmed = '$link_sosmed',
                gambar = '$gambar'
              WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php?success=edit');
        exit;
    } else {
        echo "<script>alert('Gagal mengupdate data: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
    
} else {
    header('Location: index.php');
    exit;
}
?>