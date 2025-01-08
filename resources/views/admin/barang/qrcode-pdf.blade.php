<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar QR Code Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        /* Header for every page */
        @page {
            margin-top: 30px;
        }

        .page-header {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .qr-code {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
        }

        .qr-details {
            margin-left: 20px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Make sure the QR code section doesn't break across pages */
        .qr-code-container {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    @foreach ($barangs as $index => $barang)
        @if ($index > 0) <!-- Add a page break after the first page -->
            <div class="page-break"></div>
        @endif

        <!-- Header for each page -->
        <div class="page-header">
            <h1>Daftar QR Code Barang</h1>
        </div>

        <!-- QR Code and details for each barang -->
        <div class="qr-code-container">
            <div class="qr-code">
                <div>
                    <img src="data:image/png;base64,{{ base64_encode($qrCodes[$barang->id]) }}" alt="QR Code">
                </div>
                <div class="qr-details">
                    <p><strong>Nama Barang:</strong> {{ $barang->name }}</p>
                    <p><strong>Kode Barang:</strong> {{ $barang->code }}</p>
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
