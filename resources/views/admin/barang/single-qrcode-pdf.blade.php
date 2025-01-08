<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        .qr-code {
            margin: 20px auto;
        }

        .details {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h1>QR Code Barang</h1>
    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
    </div>
    <div class="details">
        <p><strong>Nama Barang:</strong> {{ $barang->name }}</p>
        <p><strong>Kode Barang:</strong> {{ $barang->code }}</p>
    </div>
</body>

</html>
