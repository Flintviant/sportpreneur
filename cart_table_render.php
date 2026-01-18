<?php
session_start();

$html = '';
$subtotal = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        $total_item = $item['harga'] * $item['qty'];
        $subtotal += $total_item;

        $html .= '
        <tr class="table_row">
            <td class="column-1">
                <div class="how-itemcart1">
                    <img src="images/product-01.jpg" alt="">
                </div>
            </td>
            <td class="column-2">'.$item['nama'].'</td>
            <td class="column-3">Rp '.number_format($item['harga'],0,',','.').'</td>
            <td class="column-4">
                <div class="wrap-num-product flex-w m-l-auto m-r-0">
                    <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m qty-btn"
                        data-id="'.$id.'" data-type="minus">
                        <i class="fs-16 zmdi zmdi-minus"></i>
                    </div>

                    <input class="mtext-104 cl3 txt-center num-product" type="number" value="'.$item['qty'].'" readonly>

                    <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m qty-btn"
                        data-id="'.$id.'" data-type="plus">
                        <i class="fs-16 zmdi zmdi-plus"></i>
                    </div>
                </div>
            </td>
            <td class="column-5">Rp '.number_format($total_item,0,',','.').'</td>
        </tr>';
    }
}

echo json_encode([
    'html' => $html,
    'subtotal' => number_format($subtotal,0,',','.')
]);
