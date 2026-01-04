<?php
session_start();
require_once '../koneksi.php';

// 1. CEK LOGIN YANG BENAR (Sesuai dengan login.php & index.php)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php?pesan=belum_login");
    exit;
}

// Cek apakah form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari form
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
    
    // Handle File Upload
    $gambar = ''; // Default kosong
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions (Ditambah WebP)
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        if (in_array($file_ext, $allowed)) {
            if ($file_size <= 2097152) { // Max 2MB
                // Generate nama unik biar gak bentrok
                $gambar = uniqid() . '_' . time() . '.' . $file_ext;
                $upload_path = '../uploads/' . $gambar;
                
                // Buat folder uploads jika belum ada
                if (!file_exists('../uploads/')) {
                    mkdir('../uploads/', 0777, true);
                }
                
                // Pindahkan file
                if (!move_uploaded_file($file_tmp, $upload_path)) {
                    echo "<script>alert('Gagal upload gambar ke folder!'); window.history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file tidak diizinkan! Gunakan JPG, PNG, GIF, atau WEBP'); window.history.back();</script>";
            exit;
        }
    }
    
    // Insert ke Database
    $query = "INSERT INTO destinasi (
                kode_wisata, nama_wisata, jenis_daya_tarik, no_telp, deskripsi, 
                sumber_link, alamat, kabupaten_kota, jam_operasional, fasilitas, 
                link_sosmed, gambar
              ) VALUES (
                '$kode_wisata', '$nama_wisata', '$jenis_daya_tarik', '$no_telp', '$deskripsi',
                '$sumber_link', '$alamat', '$kabupaten_kota', '$jam_operasional', '$fasilitas',
                '$link_sosmed', '$gambar'
              )";
    
    if (mysqli_query($conn, $query)) {
        // Berhasil
        header('Location: index.php?success=add');
        exit;
    } else {
        // Gagal Database -> Hapus gambar yang terlanjur diupload biar gak nyampah
        if ($gambar && file_exists('../uploads/' . $gambar)) {
            unlink('../uploads/' . $gambar);
        }
        echo "<script>alert('Gagal menyimpan data ke database: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
    
} else {
    // Kalau akses langsung tanpa POST, lempar balik
    header('Location: tambah.php');
    exit;
}
?>