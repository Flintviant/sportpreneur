<?php
require_once 'koneksi.php';
require_once 'auth.php';

$data = json_decode(file_get_contents("php://input"), true);

$id_barang = $data['id'];
$kategori  = $data['kategori'] ?? null;
$id_user   = $_SESSION['id_member'];

$response = [
    'status' => false,
    'login' => true,
    'message' => 'Aksi gagal'
];

switch ($kategori) {

    case 2:
        $stmt = $conn->prepare(
            "INSERT INTO jasa_request (id_barang, id_user) VALUES (?, ?)"
        );
        $stmt->execute([$id_barang, $id_user]);

        $response = [
            'status' => true,
            'login' => true,
            'redirect' => 'dashboard_jasa.php',
            'message' => 'Permintaan jasa berhasil dikirim'
        ];
        break;

    case 3:
        $stmt = $conn->prepare(
            "INSERT INTO event_request (id_barang, id_user) VALUES (?, ?)"
        );
        $stmt->execute([$id_barang, $id_user]);

        $response = [
            'status' => true,
            'login' => true,
            'redirect' => 'dashboard_event.php',
            'message' => 'Berhasil daftar event'
        ];
        break;

    case 4:
        $stmt = $conn->prepare(
            "INSERT INTO membership_request (id_user, paket) VALUES (?, ?)"
        );
        $stmt->execute([$id_user, 'Default']);

        $response = [
            'status' => true,
            'login' => true,
            'redirect' => 'membership.php',
            'message' => 'Pengajuan membership dikirim'
        ];
        break;

    case 5:

        if (empty($id_barang)) {
            echo json_encode([
                'status' => false,
                'login' => true,
                'message' => 'ID barang tidak valid'
            ]);
            exit;
        }

        $pesan = 'Hubungi pelatih';

        $stmt = $conn->prepare(
            "INSERT INTO pelatih_contact (id_barang, id_user, pesan) VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sis", $id_barang, $id_user, $pesan);
        $stmt->execute();

        $response = [
            'status' => true,
            'login' => true,
            'redirect' => 'product.php',
            'message' => 'Pelatih akan menghubungi Anda'
        ];

        break;

}

echo json_encode($response);
