<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-{{ $icon ?? 'circle' }} me-2"></i>{{ $title }}
        </h1>
        @if(isset($subtitle))
        <p class="text-muted">
            {{ $subtitle }}
        </p>
        @endif
    </div>
    <div>
        {{ $actions ?? '' }}
    </div>
</div>