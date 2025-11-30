<div class="d-flex justify-content-between align-items-center">
    <div class="per-page-selector">
        <span class="me-2">Show:</span>
        <div class="btn-group btn-group-sm" role="group">
            @foreach($perPageOptions as $option)
                <a href="{{ route($route, array_merge(request()->except('per_page', 'page'), ['per_page' => $option])) }}" 
                   class="btn {{ $perPage == $option ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $option }}</a>
            @endforeach
        </div>
    </div>
    <div>
        {{ $paginator->withQueryString()->links() }}
    </div>
</div>