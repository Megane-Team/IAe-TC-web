<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar QR Code Ruangan</title>
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
    @foreach ($ruangans as $index => $ruangan)
        @if ($index > 0) <!-- Add a page break after the first page -->
            <div class="page-break"></div>
        @endif

        <!-- Header for each page -->
        <div class="page-header">
            <h1>Daftar QR Code Ruangan</h1>
        </div>

        <!-- QR Code and details for each room -->
        <div class="qr-code-container">
            <div class="qr-code">
                <div>
                    <img src="data:image/png;base64,{{ base64_encode($qrCodes[$ruangan->id]) }}" alt="QR Code">
                </div>
                <div class="qr-details">
                    <p><strong>Kode:</strong> {{ $ruangan->code }}</p>
                    <p><strong>Nama Gedung:</strong> {{ $ruangan->tempat->name ?? 'Tidak Ada' }}</p>
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
