<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Cetak Order {{ $order->no_order ?? $order->id }}</title>
  <style>
    @page { size: A4; margin: 18mm; }
    * { box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #000; }
    .sheet { width: 100%; max-width: 100%; border: 1.5px solid #000; padding: 6mm; margin: 0 auto; }
    .header { text-align: center; margin-bottom: 4mm; }
    .title { font-size: 16pt; font-weight: 700; }
    .subtitle { font-size: 9pt; margin-top: 2mm; }
    .contact { font-size: 9pt; margin-top: 1mm; }
    table { width: 100%; max-width: 100%; border-collapse: collapse; table-layout: fixed; }
    .outer { border: 1px solid #000; }
    .outer td, .outer th { border: 1px solid #000; vertical-align: middle; padding: 3mm; word-wrap: break-word; }
    .label { font-size: 9pt; font-weight: 600; color: #111; }
    .value { font-size: 12pt; font-weight: 700; }
    .noorder { background: #3b82f6; color: #fff; text-align: center; font-size: 26pt; font-weight: 800; letter-spacing: 1px; }
    .section-title { font-size: 9pt; color: #111; margin-bottom: 1mm; }
    .box { border: 1px solid #000; padding: 3mm; min-height: 22mm; }
    .box-lg { border: 1px solid #000; padding: 3mm; min-height: 35mm; }
    .footer-cells td { font-size: 18pt; font-weight: 800; text-align: center; padding: 6mm; }
    .muted { font-size: 9pt; }
  </style>
</head>
<body>
  <div class="sheet">
    <div class="header">
      <div class="title">{{ $app_name }}</div>
      <div class="subtitle">{{ $app_description }}</div>
      <div class="contact">{{ $address }}</div>
      <div class="contact">{{ $phone }}</div>
    </div>

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
          <div class="value">{{ $order->customer->name ?? '' }}</div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="label">Contact Person</div>
          <div class="value">{{ $order->customer->phone ?? '' }}</div>
        </td>
        <td>
          <div class="label">Jenis Order</div>
          <div class="value">{{ $order->product->name ?? '' }}</div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="section-title">Klien</div>
          <div class="box"></div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="section-title">Jaminan / Agunan / Objek</div>
          <div class="box">
            {{ data_get($order->meta, '8') ?? data_get($order->meta, '17') ?? '' }}
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="section-title">Catatan & Keterangan</div>
          <div class="box-lg">
            {{ data_get($order->meta, '1') ?? '' }}
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="section-title">Catatan & Keterangan Tagihan <span class="muted">(diisi Div. Keuangan)</span></div>
          <div class="box"></div>
        </td>
      </tr>
      <tr class="footer-cells">
        <td>PIC</td>
        <td>MAKER</td>
      </tr>
    </table>
  </div>
</body>
</html>
