<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Browse Gambar Konten</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-5.0.0-alpha-2.min.css') }}">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <h4 class="mb-4">Galeri Gambar Konten</h4>
        <div class="row g-3">
            @forelse($files as $fileUrl)
                <div class="col-md-3 col-sm-4 col-6">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $fileUrl }}" class="card-img-top" style="height: 140px; object-fit: cover;" alt="File">
                        <div class="card-body p-2 text-center">
                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="selectFile('{{ $fileUrl }}')">Pilih</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">Belum ada file gambar di folder konten.</p>
                </div>
            @endforelse
        </div>
    </div>
    <script>
    function selectFile(url) {
        if (window.opener && typeof window.opener.onFileSelected === 'function') {
            window.opener.onFileSelected(url);
            window.close();
        } else {
            navigator.clipboard.writeText(url);
            alert('URL disalin: ' + url);
        }
    }
    </script>
</body>
</html>
