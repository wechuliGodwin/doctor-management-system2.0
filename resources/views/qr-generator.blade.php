<form method="POST" action="{{ route('generate.qr') }}">
    @csrf
    <div class="form-group">
        <label for="url">Enter URL:</label>
        <input type="url" name="url" id="url" class="form-control" placeholder="https://example.com" required>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Generate QR Code</button>
</form>

@if(session('qr_code'))
    <div class="mt-3">
        <p>Generated QR Code:</p>
        <img src="data:image/png;base64,{{ session('qr_code') }}" alt="QR Code">
    </div>
@endif

