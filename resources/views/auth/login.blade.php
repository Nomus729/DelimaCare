<form action="/login" method="POST">
    @csrf
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email') <small>{{ $message }}</small> @error
    </div>

    <div>
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>
