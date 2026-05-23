{{-- Toast Notification Container --}}
<div class="toast-container" id="toastContainer" aria-live="polite">
    @if(session('toast_success'))
        <div class="toast toast-success" data-auto-dismiss>
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('toast_success') }}</span>
            <button class="toast-close">&times;</button>
        </div>
    @endif

    @if(session('toast_info'))
        <div class="toast toast-info" data-auto-dismiss>
            <i class="bi bi-info-circle-fill"></i>
            <span>{{ session('toast_info') }}</span>
            <button class="toast-close">&times;</button>
        </div>
    @endif

    @if(session('toast_error'))
        <div class="toast toast-error" data-auto-dismiss>
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('toast_error') }}</span>
            <button class="toast-close">&times;</button>
        </div>
    @endif
</div>
