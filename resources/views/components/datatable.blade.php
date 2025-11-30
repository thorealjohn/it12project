<div class="card shadow-sm mb-4">
    @if(isset($header))
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">{{ $header }}</h5>
        @if(isset($headerActions))
            <div>
                {{ $headerActions }}
            </div>
        @endif
    </div>
    @endif
    <div class="card-body p-0">
        <div class="table-responsive">
            {{ $slot }}
        </div>
    </div>
    @if(isset($footer))
        <div class="card-footer bg-white">
            {{ $footer }}
        </div>
    @endif
</div>