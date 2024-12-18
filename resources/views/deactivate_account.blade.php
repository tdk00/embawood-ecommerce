<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deactivate Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Include Inputmask Standalone -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Deactivate Account</h2>
    <form method="POST" action="{{ route('deactivate.account') }}">
        @csrf
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="(51) 575-55-66" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <button type="submit" class="btn btn-danger w-100">Deactivate Account</button>
    </form>
</div>

<!-- Initialize Inputmask -->
<script>
    $(document).ready(function () {
        $('#phone_number').inputmask({
            mask: '(99) 999-99-99',
        });
    });
</script>
</body>
</html>
