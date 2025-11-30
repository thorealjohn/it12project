<div class="card stats-card dashboard-item">
    <div class="stats-icon text-{{ $color ?? 'primary' }}">
        <i class="bi bi-{{ $icon ?? 'circle' }}"></i>
    </div>
    <div class="card-body">
        <h6 class="text-muted mb-2">{{ $title }}</h6>
        <h3 class="mb-0">{{ $value }}</h3>
        @if(isset($subtitle))
        <div class="mt-2 text-muted">
            <small>{{ $subtitle }}</small>
        </div>
        @endif
        @if(isset($link) && isset($linkText))
        <div class="mt-3">
            <a href="{{ $link }}" class="btn btn-sm btn-outline-{{ $color ?? 'primary' }}">
                {{ $linkText }} <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        @endif
    </div>
</div>