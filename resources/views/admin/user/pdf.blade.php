<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            position: relative;
        }

        /* Grup logo kiri */
        .header .left-logos {
            display: flex;
            align-items: flex-end; /* Posisi DefendID lebih tinggi */
        }

        .header .left-logos img {
            height: 70px;
            object-fit: contain;
        }

        .header .left-logos .defendid {
            height: 30px; /* Ukuran lebih kecil */
            margin-left: 10px; /* Jarak antara logo BUMN dan DefendID */
            margin-bottom: 20px; /* Naik lebih tinggi ke atas */
        }

        /* Logo kanan */
        .header .right-logo {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .header .right-logo img {
            height: 70px;
            object-fit: contain;
        }

        /* Konten tengah (judul dan tanggal) */
        .header-content {
            text-align: center;
            flex: 1;
        }

        .header-content h1 {
            margin: 0;
            font-size: 18px;
        }

        .header-content p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        table td img {
            width: 50px;
            height: auto;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="header">
        <!-- Grup Logo Kiri -->
        <div class="left-logos">
            <img src="{{ public_path('assets/img/bumn.png') }}" alt="Logo BUMN">
            <img class="defendid" src="{{ public_path('assets/img/defendid.png') }}" alt="Logo DefendID">
        </div>

        <!-- Konten Tengah -->
        <div class="header-content">
            <h1>Daftar Pengguna</h1>
            <p>Generated on: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
        </div>

        <!-- Logo Kanan -->
        <div class="right-logo">
            <img src="{{ public_path('assets/img/ptdi.png') }}" alt="Logo PTDI">
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Unit</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if ($user->photo)
                            <img src="{{ public_path('storage/' . ($user->photo ?? 'assets/img/product/default.jpg')) }}" alt="Foto Pengguna">
                        @else
                            Tidak ada foto
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->unit }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user-> phoneNumber }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated by: {{ auth()->user()->name }}</p>
    </div>
</body>

</html>