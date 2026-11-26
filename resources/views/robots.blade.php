<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0"><i class="icon-file-alt me-2 text-primary"></i>robots.txt</h5>
        <span class="badge bg-light text-muted border font-monospace">{{ $robotsPath }}</span>
    </div>
    <form action="{{ route('seo.robots.save') }}" method="POST">
        @csrf
        <div class="card-body">
            @if(!$writable)
                <div class="alert alert-warning small">{{ trans('lavero-admin-seo::seo.robots_write_error') }}</div>
            @endif
            <textarea name="content" rows="14" class="form-control font-monospace">{{ $robotsContent }}</textarea>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4">
                <i class="icon-save me-1"></i> {{ trans('admin.save') }}
            </button>
        </div>
    </form>
</div>
