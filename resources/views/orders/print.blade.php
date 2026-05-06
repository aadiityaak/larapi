<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <title>Cetak Order {{ $order->no_order ?? $order->id }}</title>
  <style>
    @page { size: 210mm 330mm; margin: 5mm; }
    * { box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #000; margin: 0; }
    .sheet { width: calc(100% - 2mm); max-width: calc(100% - 18mm); border: none; padding: 6mm; margin: 0 auto; }
    .letterhead-space { height: 60mm; }
    .header { text-align: center; margin-bottom: 4mm; }
    .title { font-size: 14pt; font-weight: 700; font-family: 'Playfair Display', DejaVu Serif, 'Times New Roman', Times, serif; }
    .subtitle { font-size: 8pt; margin-top: 2mm; }
    .contact { font-size: 8pt; margin-top: 1mm; }
    table { width: 100%; max-width: 100%; border-collapse: collapse; table-layout: fixed; }
    .outer { border: 1px solid #999; }
    .outer td, .outer th { border: 1px solid #999; vertical-align: middle; padding: 3mm; word-wrap: break-word; }
    .label { font-size: 9pt; font-weight: 600; color: #111; }
    .value { font-size: 12pt; font-weight: 700; }
    .noorder { background: #3b82f6; color: #fff; text-align: center; font-size: 26pt; font-weight: 800; letter-spacing: 1px; }
    .footer-cells td { font-size: 18pt; font-weight: 800; text-align: center; padding: 6mm; }
    .muted { font-size: 9pt; }

    .font-sans { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; }
    .font-serif { font-family: DejaVu Serif, Times New Roman, Times, serif; }
    .font-mono { font-family: DejaVu Sans Mono, Courier New, Courier, monospace; }
    .text-xs { font-size: 8pt; }
    .text-sm { font-size: 9pt; }
    .text-base { font-size: 11pt; }
    .text-lg { font-size: 12pt; }
    .text-xl { font-size: 14pt; }
    .fw-400 { font-weight: 400; }
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
    .fw-800 { font-weight: 800; }
    .outer td.va-top { vertical-align: top; }
    .min-h-10 { min-height: 10mm; }
    .min-h-20 { min-height: 20mm; }
    .min-h-30 { min-height: 30mm; }
  </style>
</head>
<body>
  <div class="sheet">
    <div class="letterhead-space"></div>
    <table class="outer">
      <tr>
        <td class="noorder" rowspan="3" style="width:55%; height:36mm;">
          <div class="label" style="color:#fff;">No Order</div>
          {{ $order->no_order }}
        </td>
        <td style="width:45%;">
          <div class="label">Tanggal Order</div>
          <div class="min-h-10">
            {{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->locale('id')->translatedFormat('j F Y') }}
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="label">Pemberi Order</div>
          <div class="min-h-10">
            <div class="value">{{ $order->pemberi_order ?? ($order->customer->name ?? '') }}</div>
          </div>
        </td>
        </tr>
        <tr>
        <td>
          <div class="min-h-10">
            <div class="label" style="margin-top: 2mm;">Contact Person</div>
            
            <?php
            $contactPersons = is_array($order->contact_persons ?? null) ? $order->contact_persons : [];
            $contactPersons = array_values(array_filter($contactPersons, function ($item) {
                if (!is_array($item)) return false;
                $name = trim((string)($item['name'] ?? ''));
                $phone = trim((string)($item['phone'] ?? ''));
                return $name !== '' || $phone !== '';
            }));
            ?>
            
            @if(!empty($contactPersons))
                @foreach($contactPersons as $cp)
                    <?php
                    $cpName = trim((string)($cp['name'] ?? ''));
                    $cpPhone = trim((string)($cp['phone'] ?? ''));
                    $line = trim($cpName . ($cpName !== '' && $cpPhone !== '' ? ' - ' : '') . $cpPhone);
                    ?>
                    <div class="value">{{ $line !== '' ? $line : '-' }}</div>
                @endforeach
            @else
                <?php
                $contactPhones = [];
                if ($order->pemberi_phone) {
                    $contactPhones[] = $order->pemberi_phone;
                } else {
                    if ($order->customer->phone) {
                        $contactPhones[] = $order->customer->phone;
                    }
                    
                    if ($order->customer->meta) {
                        foreach ($order->customer->meta as $meta) {
                            if (str_starts_with($meta->meta_key, 'phone_') && !empty($meta->meta_value)) {
                                $contactPhones[] = $meta->meta_value;
                            }
                        }
                    }
                }
                ?>

                @if(!empty($contactPhones))
                    @foreach($contactPhones as $phone)
                        <div class="value">{{ $phone }}</div>
                    @endforeach
                @else
                    <div class="value">-</div>
                @endif
            @endif
          </div>
        </td>
      </tr>
      <tr>
        <?php
        // Kumpulkan kedua klien
        $klienUtama = $order->customer;
        $klienRelasi = null;
        $tipeRelasi = null;
        
        if ($order->relatedOrder && $order->relatedOrder->customer) {
            $klienRelasi = $order->relatedOrder->customer;
            $tipeRelasi = $order->relation_type;
        } elseif ($order->relatedOrders->count() > 0) {
            $relatedOrder = $order->relatedOrders->first();
            if ($relatedOrder && $relatedOrder->customer) {
                $klienRelasi = $relatedOrder->customer;
                $tipeRelasi = $relatedOrder->relation_type;
            }
        }
        
        // Fungsi untuk menampilkan data klien
        function renderKlienSimple($customer, $label, $klienNumber) {
            if (!$customer) return;
            
            $displayLabel = $label ? $klienNumber . ' (' . $label . ')' : $klienNumber;
            echo '<div style="margin-bottom: 10px;">';
            echo '<div style="font-weight: bold; color: #4f46e5; margin-bottom: 3px; font-size: 10pt;">' . htmlspecialchars($displayLabel) . '</div>';
            echo '<div style="font-weight: bold; font-size: 11pt;">' . htmlspecialchars($customer->name ?? '-') . '</div>';
            echo '<div style="font-size: 9pt;">' . htmlspecialchars($customer->address ?? '-') . '</div>';
            
            // Tampilkan telepon
            $phones = [];
            if ($customer->phone) {
                $phones[] = $customer->phone;
            }
            
            if (property_exists($customer, 'meta') && $customer->meta) {
                foreach ($customer->meta as $meta) {
                    if (str_starts_with($meta->meta_key, 'phone_') && !empty($meta->meta_value)) {
                        $phones[] = $meta->meta_value;
                    }
                }
            }
            
            if (!empty($phones)) {
                foreach ($phones as $phone) {
                    echo '<div style="font-size: 9pt;">' . htmlspecialchars($phone) . '</div>';
                }
            } else {
                echo '<div style="font-size: 9pt;">-</div>';
            }
            echo '</div>';
        }
        ?>
        
        <td class="va-top" style="width: 55%;">
          <div class="label">Klien</div>
          <div class="min-h-20">
            <?php
            $customKlienHtml = data_get($order->meta, 'custom_client_html');
            $customKlienHtml = is_string($customKlienHtml) ? trim($customKlienHtml) : '';
            $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><span><div>';
            $customKlienSafe = $customKlienHtml !== '' ? strip_tags($customKlienHtml, $allowedTags) : '';
            ?>

            @if($customKlienSafe !== '')
              <div class="value font-sans" style="font-size: 11pt; font-weight: 700;">
                {!! $customKlienSafe !!}
              </div>
            @else
              @if($klienRelasi)
                  <?php renderKlienSimple($klienUtama, $tipeRelasi, 'Klien 1'); ?>
                  <?php renderKlienSimple($klienRelasi, null, 'Klien 2'); ?>
              @else
                  <?php renderKlienSimple($klienUtama, null, 'Klien'); ?>
              @endif
            @endif
          </div>
        </td>
        <td class="va-top" style="width: 45%;">
          <div class="label">Jenis Order</div>
          <div class="min-h-20">
            <?php
            $productNames = '-';
            if (isset($products) && is_array($products) && count($products)) {
                $names = array_values(array_filter(array_map(function ($p) {
                    return is_array($p) ? ($p['name'] ?? null) : null;
                }, $products)));
                if (!empty($names)) {
                    $productNames = implode(', ', $names);
                }
            } elseif (!empty($order->product->name ?? null)) {
                $productNames = $order->product->name;
            }
            ?>
            <div>{{ $productNames }}</div>
          </div>
        </td>
      </tr>
        <tr>
          <td colspan="2">
            <div class="label">Jaminan / Agunan / Objek</div>
            <div class="min-h-20">
              {{ data_get($order->meta, '8') ?: (data_get($order->meta, '17') ?: '-') }}
            </div>
          </td>
        </tr>
      <tr>
        <td colspan="2">
          <div class="label">Catatan & Keterangan</div>
          <div class="min-h-20">
            {{ data_get($order->meta, '1') ?? '' }}
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="label">Catatan & Keterangan Tagihan <span class="muted">(diisi Div. Keuangan)</span></div>
          <div class="min-h-20">{{ $order->billing_notes ?? '' }}</div>
        </td>
      </tr>
      <tr class="footer-cells">
        <td>
          PIC
          @if(!empty($pic))
            <div class="value" style="margin-top: 2mm; font-size: 14pt;">{{ $pic }}</div>
          @endif
        </td>
        <td>
          MAKER
          @if(!empty($maker))
            <div class="value" style="margin-top: 2mm; font-size: 14pt;">{{ $maker }}</div>
          @endif
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
