<form method="GET" action="{{ $route }}" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3">
            <label for="search">Search</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Name, Phone, etc."
                value="{{ request('search') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ $route }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
    <div class="mb-3 mt-3">
        <a href="{{ $route . '?download=filtered&' . http_build_query(request()->query()) }}"
            class="btn btn-success btn-sm">Download Filtered</a>
        <a href="{{ $route . '?download=full&' . http_build_query(request()->query()) }}"
            class="btn btn-info btn-sm">Download Full Report</a>
    </div>
</form>