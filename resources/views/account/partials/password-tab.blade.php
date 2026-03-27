<h2 class="account-card__title">Ganti Password</h2>
<p class="account-card__hint">Gunakan password baru yang kuat agar akun buyer atau mitra kamu tetap aman.</p>
<form action="{{ route('account.password.update', ['tab' => 'password']) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="account_tab" value="password">
    <div class="row">
        <div class="col-md-6 mb-25">
            <label class="account-label" for="current_password">Password saat ini</label>
            <input id="current_password" class="account-input" type="password" name="current_password" required>
            @error('current_password')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-25">
            <label class="account-label" for="password">Password baru</label>
            <input id="password" class="account-input" type="password" name="password" required>
            @error('password')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-10">
            <label class="account-label" for="password_confirmation">Konfirmasi password baru</label>
            <input id="password_confirmation" class="account-input" type="password" name="password_confirmation" required>
            @error('password_confirmation')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="account-actions">
        <button type="submit" class="account-button">Simpan password</button>
        <a href="{{ route('home') }}" class="account-button--secondary">Kembali</a>
    </div>
</form>
