<?php
// session
require_once __DIR__ . '/session_modal.php';
// koneksi
require_once __DIR__ . '/koneksi.php';

$current = $_SERVER['REQUEST_URI'];

function activeMenu($path, $current) {
    return ($current == $path || strpos($current, $path.'/') === 0) ? 'active-menu' : '';
}

// produk marketplace
$sql_barang = $conn->query("
  SELECT 
    b.*, 
    k.nama_kategori
  FROM barang b
  JOIN kategori k 
    ON b.id_kategori = k.id_kategori
  ORDER BY b.id DESC
  LIMIT 4
");

if (!$sql_barang) {
  die('Query Error: ' . $conn->error);
}

$produk = [];
while ($row = $sql_barang->fetch_assoc()) {
  $produk[] = $row;
}
// end produk marketplace

// data global aman
$id_member = $_SESSION['id_member'] ?? null;

//member untuk profile dan checkout
if ($id_member) {

    $stmt = $conn->prepare("
        SELECT 
            m.nm_member,
            m.email,
            m.telepon,
            m.alamat_member,
            m.kota_member,

            m.id_role,
            r.role_name,

            a.olahraga,
            a.tawarkan,
            a.butuh,
            a.dampak

        FROM member m
        LEFT JOIN akun a 
            ON m.id_member = a.id_member
        LEFT JOIN roles r
            ON m.id_role = r.id_role
        WHERE m.id_member = ?
        LIMIT 1
    ");

    $stmt->bind_param('i', $id_member);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // DATA MEMBER
        $nama    = $row['nm_member'];
        $email   = $row['email'];
        $phone   = $row['telepon'];
        $address = $row['alamat_member'];
        $kota    = $row['kota_member'];

        // ROLE
        $role   = $row['id_role'];
        $role_name = $row['role_name']; // ✅ SEKARANG AMAN

        // DATA AKUN
        $olahraga = $row['olahraga'];
        $tawarkan = $row['tawarkan'];
        $butuh    = $row['butuh'];
        $dampak   = $row['dampak'];
    }
}