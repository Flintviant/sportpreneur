<?php
session_start();

$html = '';
$total = 0;
$count = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        $subtotal = $item['harga'] * $item['qty'];
        $total += $subtotal;
        $count += $item['qty']; // 🔥 hitung badge

        $html .= '
        <li class="header-cart-item flex-w flex-t m-b-12">
            <div class="header-cart-item-txt p-t-8 w-full">
                <strong>'.$item['nama'].'</strong>
                <div style="display:flex;gap:8px;align-items:center">
                    <button class="qty-btn" data-id="'.$id.'" data-type="minus">-</button>
                    <span>'.$item['qty'].'</span>
                    <button class="qty-btn" data-id="'.$id.'" data-type="plus">+</button>
                    <span style="margin-left:auto">Rp '.number_format($subtotal,0,',','.').'</span>
                    <button class="remove-cart" data-id="'.$id.'">x</button>
                </div>
            </div>
        </li>';
    }
}

echo json_encode([
    'html'  => $html,
    'total' => number_format($total,0,',','.'),
    'count' => $count // 🔥 badge
]);
