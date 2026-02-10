<?php
$nama = $_GET['nama'] ?? 'proposal';

$filename = 'Proposal_' . preg_replace('/[^a-zA-Z0-9-_]/','_', $nama) . '.pdf';
$file = __DIR__ . '/proposal_template.pdf';

if (!file_exists($file)) {
  die('File template tidak ditemukan');
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Length: ' . filesize($file));
readfile($file);
exit;
