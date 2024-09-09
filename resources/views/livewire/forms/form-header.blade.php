<div class="col-12 form-box-title pb-2">
    <div class="row g-0">
        <div class="col-12 mt-1">
            <div class="progress progress-style">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="{{ $progressBar }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progressBar }}%">{{ $progressBar }}%</div>
            </div>
        </div>
        <div class="col-12 text-center mt-3">
            <h5>Page {{ $page_number }} of {{ $page_total }} - {{ $page_title }}</h5>
        </div>
    </div>
</div>
