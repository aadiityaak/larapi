<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <title>Cetak Order {{ $order->no_order ?? $order->id }}</title>
  <style>
    @page { size: A4; margin: 5mm; }
    * { box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #000; margin: 0; }
    .sheet { width: calc(100% - 2mm); max-width: calc(100% - 18mm); border: 1.5px solid #000; padding: 6mm; margin: 0 auto; }
    .header { text-align: center; margin-bottom: 4mm; }
    .title { font-size: 14pt; font-weight: 700; font-family: 'Playfair Display', DejaVu Serif, 'Times New Roman', Times, serif; }
    .subtitle { font-size: 8pt; margin-top: 2mm; }
    .contact { font-size: 8pt; margin-top: 1mm; }
    table { width: 100%; max-width: 100%; border-collapse: collapse; table-layout: fixed; }
    .outer { border: 1px solid #000; }
    .outer td, .outer th { border: 1px solid #000; vertical-align: middle; padding: 3mm; word-wrap: break-word; }
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
  </style>
</head>
<body>
  <div class="sheet">
    <div class="header">
      <div class="title">{{ $app_name }}</div>
      <div class="subtitle">{{ $app_description }}</div>
      <div class="contact">{{ $address }}</div>
      <div class="contact">Telp: {{ $phone }} | Email: {{ $email }}</div>
    </div>
    <hr style="margin: 1mm 0;">
    <hr style="margin: 1mm 0;">
    <table class="outer">
      <tr>
        <td class="noorder" rowspan="2" style="width:55%; height:36mm;">
          {{ $order->no_order }}
        </td>
        <td style="width:45%;">
          <div class="label">Tanggal Order</div>
          <div class="value">{{ \Illuminate\Support\Str::of($order->order_date ?? $order->created_at)->substr(0,10) }}</div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="label">Pemberi Order</div>
          <div class="value">{{ $order->pemberi_order ?? ($order->customer->name ?? '') }}</div>
          <div class="label" style="margin-top: 2mm;">Contact Person</div>
          <div class="value">{{ $order->pemberi_phone ?? ($order->customer->phone ?? '') }}</div>
        </td>
      </tr>
      <tr>
        <td class="va-top">
          <div class="label">Klien</div>
          <div>
            <div class="value">{{ $order->customer->name ?? '-' }}</div>
            <div>{{ $order->customer->address ?? '-' }}</div>
            <div>{{ $order->customer->phone ?? '-' }}</div>
          </div>
        </td>
        <td class="va-top">
          <div class="label">Jenis Order</div>
          <div>
            <div class="value">{{ $order->product->name ?? '-' }}</div>
          </div>
        </td>
      </tr>

        <tr>
          <td colspan="2">
            <div class="label">Jaminan / Agunan / Objek</div>
            <div>
              {{ data_get($order->meta, '8') ?? data_get($order->meta, '17') }}
            </div>
          </td>
        </tr>
      <tr>
        <td colspan="2">
          <div class="label">Catatan & Keterangan</div>
          <div>
            {{ data_get($order->meta, '1') ?? '' }}
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="label">Catatan & Keterangan Tagihan <span class="muted">(diisi Div. Keuangan)</span></div>
          <div>{{ $order->billing_notes ?? '' }}</div>
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
