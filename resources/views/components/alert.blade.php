<div class="alert alert-{{ $type ?? 'info' }} alert-dismissible fade show" role="alert">
    <i class="bi bi-{{ $icon ?? 'info-circle' }}-fill me-2"></i> {{ $message }}
    @if($dismissible ?? true)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>