<h2 class="account-card__title">Alamat Kirim</h2>
<p class="account-card__hint">Kamu bisa menyimpan lebih dari satu alamat kirim. Tandai salah satu sebagai alamat default untuk memudahkan checkout nanti.</p>

@forelse ($accountUser->shippingAddresses as $shippingAddress)
    <div class="shipping-address-card">
        <div class="shipping-address-card__header">
            <h3 class="shipping-address-card__title">{{ $shippingAddress->label ?: 'Alamat Kirim' }}</h3>
            @if ($shippingAddress->is_default)
                <span class="shipping-address-card__badge">Default</span>
            @endif
        </div>

        <form action="{{ route('account.shipping-addresses.update', ['shippingAddress' => $shippingAddress, 'tab' => 'shipping']) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="account_tab" value="shipping">
            <div class="row">
                <div class="col-md-4 mb-20">
                    <label class="account-label">Label alamat</label>
                    <input class="account-input" type="text" name="label" value="{{ old('label', $shippingAddress->label) }}" placeholder="Contoh: Rumah, Gudang, Toko">
                </div>
                <div class="col-md-4 mb-20">
                    <label class="account-label">Penerima</label>
                    <input class="account-input" type="text" name="recipient_name" value="{{ old('recipient_name', $shippingAddress->recipient_name) }}" required>
                </div>
                <div class="col-md-4 mb-20">
                    <label class="account-label">Nomor HP</label>
                    <input class="account-input" type="tel" name="phone" value="{{ old('phone', $shippingAddress->phone) }}" required>
                </div>
                <div class="col-md-12 mb-20">
                    <label class="account-label">Alamat lengkap</label>
                    <textarea class="account-textarea" name="address_line" required>{{ old('address_line', $shippingAddress->address_line) }}</textarea>
                </div>
                <div class="col-md-3 mb-20">
                    <label class="account-label">Kecamatan / distrik</label>
                    <input class="account-input" type="text" name="district" value="{{ old('district', $shippingAddress->district) }}">
                </div>
                <div class="col-md-3 mb-20">
                    <label class="account-label">Kota / kabupaten</label>
                    <input class="account-input" type="text" name="city" value="{{ old('city', $shippingAddress->city) }}">
                </div>
                <div class="col-md-3 mb-20">
                    <label class="account-label">Provinsi</label>
                    <input class="account-input" type="text" name="province" value="{{ old('province', $shippingAddress->province) }}">
                </div>
                <div class="col-md-3 mb-20">
                    <label class="account-label">Kode pos</label>
                    <input class="account-input" type="text" name="postal_code" value="{{ old('postal_code', $shippingAddress->postal_code) }}">
                </div>
                <div class="col-md-12 mb-10">
                    <label class="account-label">Catatan alamat</label>
                    <textarea class="account-textarea" name="notes">{{ old('notes', $shippingAddress->notes) }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="account-checkbox">
                        <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $shippingAddress->is_default))>
                        <span>Jadikan alamat default</span>
                    </label>
                </div>
            </div>
            <div class="account-actions">
                <button type="submit" class="account-button">Simpan alamat</button>
            </div>
        </form>

        <form action="{{ route('account.shipping-addresses.destroy', ['shippingAddress' => $shippingAddress, 'tab' => 'shipping']) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="account-actions">
                <button type="submit" class="account-button--danger">Hapus alamat</button>
            </div>
        </form>
    </div>
@empty
    <div class="account-empty">Belum ada alamat kirim. Tambahkan alamat pertama kamu di form bawah.</div>
@endforelse

<div class="shipping-address-card">
    <div class="shipping-address-card__header">
        <h3 class="shipping-address-card__title">Tambah Alamat Kirim</h3>
    </div>
    <form action="{{ route('account.shipping-addresses.store', ['tab' => 'shipping']) }}" method="POST">
        @csrf
        <input type="hidden" name="account_tab" value="shipping">
        <div class="row">
            <div class="col-md-4 mb-20">
                <label class="account-label">Label alamat</label>
                <input class="account-input" type="text" name="label" value="{{ old('label') }}" placeholder="Contoh: Rumah, Gudang, Toko">
            </div>
            <div class="col-md-4 mb-20">
                <label class="account-label">Penerima</label>
                <input class="account-input" type="text" name="recipient_name" value="{{ old('recipient_name') }}" required>
            </div>
            <div class="col-md-4 mb-20">
                <label class="account-label">Nomor HP</label>
                <input class="account-input" type="tel" name="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="col-md-12 mb-20">
                <label class="account-label">Alamat lengkap</label>
                <textarea class="account-textarea" name="address_line" required>{{ old('address_line') }}</textarea>
            </div>
            <div class="col-md-3 mb-20">
                <label class="account-label">Kecamatan / distrik</label>
                <input class="account-input" type="text" name="district" value="{{ old('district') }}">
            </div>
            <div class="col-md-3 mb-20">
                <label class="account-label">Kota / kabupaten</label>
                <input class="account-input" type="text" name="city" value="{{ old('city') }}">
            </div>
            <div class="col-md-3 mb-20">
                <label class="account-label">Provinsi</label>
                <input class="account-input" type="text" name="province" value="{{ old('province') }}">
            </div>
            <div class="col-md-3 mb-20">
                <label class="account-label">Kode pos</label>
                <input class="account-input" type="text" name="postal_code" value="{{ old('postal_code') }}">
            </div>
            <div class="col-md-12 mb-10">
                <label class="account-label">Catatan alamat</label>
                <textarea class="account-textarea" name="notes">{{ old('notes') }}</textarea>
            </div>
            <div class="col-md-12">
                <label class="account-checkbox">
                    <input type="checkbox" name="is_default" value="1" @checked(old('is_default'))>
                    <span>Jadikan alamat default</span>
                </label>
            </div>
        </div>
        <div class="account-actions">
            <button type="submit" class="account-button">Tambah alamat</button>
        </div>
    </form>
</div>
