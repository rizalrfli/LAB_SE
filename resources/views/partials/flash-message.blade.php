@if (session('pesan'))
    @php
        $tipe = session('tipe_pesan', 'info');
        $alertClass = match ($tipe) {
            'success' => 'alert-success',
            'error', 'danger' => 'alert-danger',
            'warning' => 'alert-warning',
            default => 'alert-info',
        };
    @endphp
    <div class="alert {{ $alertClass }} alert-dismissible fade show" role="alert" style="border-radius: 10px; margin-bottom: 20px;">
        {{ session('pesan') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove()"></button>
    </div>
@endif
