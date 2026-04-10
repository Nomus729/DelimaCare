<form action="/login" method="POST"> @csrf

    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <small>{{ $message }}</small>
        @enderror </div>

    <div>
        <label>Password</label>
        <input type="password" name="password" required>
        @error('password') <small>{{ $message }}</small>
        @enderror
    </div>

    <button type="submit">Login</button>
</form>
