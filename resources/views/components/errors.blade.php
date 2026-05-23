@if($errors->any())
    <div class="alert-error" role="alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
