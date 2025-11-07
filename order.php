<?php
// Konfigurasi database
$host = "localhost";
$user = "root";       // ganti sesuai user MySQL
$pass = "";           // ganti sesuai password MySQL
$db   = "indexmenucafe";

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan data ada
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama    = $conn->real_escape_string($_POST['nama']);
    $telepon = $conn->real_escape_string($_POST['telepon']);
    $menu    = $conn->real_escape_string($_POST['menu']);
    $alamat  = $conn->real_escape_string($_POST['alamat']);

    // Simpan ke database
    $sql = "INSERT INTO orders (nama, telepon, menu, alamat) 
            VALUES ('$nama', '$telepon', '$menu', '$alamat')";
    
    if ($conn->query($sql) === TRUE) {
        // Kirim email ke admin
        $to      = "hello@mompop.cafe";  // ganti dengan email tujuan
        $subject = "Pesanan Baru dari $nama";
        $message = "
            <h2>Pesanan Baru Jajanan Nusantara</h2>
            <p><strong>Nama:</strong> $nama</p>
            <p><strong>Telepon:</strong> $telepon</p>
            <p><strong>Menu:</strong> $menu</p>
            <p><strong>Alamat:</strong> $alamat</p>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Jajanan Nusantara <no-reply@jajanannusantara.com>" . "\r\n";

        // Kirim email
        if (mail($to, $subject, $message, $headers)) {
            echo "<script>alert('Pesanan berhasil dikirim!'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Pesanan tersimpan, tapi email gagal terkirim.'); window.location.href='index.html';</script>";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
