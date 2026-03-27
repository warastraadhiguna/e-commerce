<h2 class="account-card__title">Profil Akun</h2>
<p class="account-card__hint">Buyer dan mitra bisa melengkapi profil dasar di sini. Data usaha dan PIC tetap boleh diisi untuk buyer, sementara approval omzet tetap khusus mitra di admin.</p>
<form action="{{ route('account.profile.update', ['tab' => 'profile']) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="account_tab" value="profile">
    <div class="row">
        <div class="col-md-6 mb-25">
            <label class="account-label" for="account_name">Nama lengkap</label>
            <input id="account_name" class="account-input" type="text" name="name" value="{{ old('name', $accountUser->name) }}" required>
            @error('name')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-25">
            <label class="account-label" for="account_phone">Nomor HP</label>
            <input id="account_phone" class="account-input" type="tel" name="phone" value="{{ old('phone', $accountUser->phone) }}" required>
            @error('phone')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-25">
            <label class="account-label" for="account_email">Email</label>
            <input id="account_email" class="account-input" type="email" name="email" value="{{ old('email', $accountUser->email) }}" required>
            @error('email')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-25">
            <label class="account-label" for="account_business_name">Nama usaha / instansi</label>
            <input id="account_business_name" class="account-input" type="text" name="business_name" value="{{ old('business_name', $accountUser->business_name) }}">
            @error('business_name')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-25">
            <label class="account-label" for="account_contact_person">PIC</label>
            <input id="account_contact_person" class="account-input" type="text" name="contact_person" value="{{ old('contact_person', $accountUser->contact_person) }}">
            @error('contact_person')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-12 mb-10">
            <label class="account-label" for="account_address">Alamat utama</label>
            <textarea id="account_address" class="account-textarea" name="address">{{ old('address', $accountUser->address) }}</textarea>
            @error('address')
                <div class="account-field-error">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="account-actions">
        <button type="submit" class="account-button">Simpan profil</button>
    </div>
</form>
