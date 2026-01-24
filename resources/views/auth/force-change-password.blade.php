<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
</head>
<body>
    <h1>Change your password</h1>
    <p>This is required before you can continue.</p>

    @if ($errors->any())
        <div>
            <strong>Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.force.update') }}">
        @csrf

        <div>
            <label>New Password</label><br>
            <input type="password" name="password" required minlength="8">
        </div>

        <div style="margin-top: 8px;">
            <label>Confirm New Password</label><br>
            <input type="password" name="password_confirmation" required minlength="8">
        </div>

        <div style="margin-top: 12px;">
            <button type="submit">Update Password</button>
        </div>
    </form>
</body>
</html>
