<li class="public-setting-dropdown">
    <div class="ht-setting-trigger">
        <span>{{ auth()->check() ? auth()->user()->name : 'Account' }}</span>
    </div>
    <div class="setting ht-setting">
        <ul class="ht-setting-list">
            @auth
                <li><span class="public-setting-item">{{ auth()->user()->role->label() }}</span></li>
                @if (auth()->user()->isShopper())
                    <li><a href="{{ route('account.show') }}">Akun Saya</a></li>
                @endif
                @if (auth()->user()->isManagementUser())
                    <li><a href="{{ url('/management') }}">Management</a></li>
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="public-setting-button public-setting-item">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('contact') }}#partner-registration">Daftar Mitra</a></li>
            @endauth
        </ul>
    </div>
</li>
@once
    <style>
        .public-setting-dropdown .public-setting-item {
            display: block;
            width: 100%;
            color: #666;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.4;
            letter-spacing: 0;
            text-align: left;
            font-family: inherit;
            padding: 10px 18px;
            margin: 0;
        }

        .public-setting-dropdown .public-setting-button {
            background: transparent;
            border: 0;
            cursor: pointer;
        }

        .public-setting-dropdown .ht-setting-list > li > a {
            display: block;
            width: 100%;
            padding: 10px 18px;
            margin: 0;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (event) {
                document.querySelectorAll('.public-setting-dropdown').forEach(function (dropdown) {
                    if (dropdown.contains(event.target)) {
                        return;
                    }

                    const trigger = dropdown.querySelector('.ht-setting-trigger');
                    const panel = dropdown.querySelector('.ht-setting');

                    if (trigger) {
                        trigger.classList.remove('is-active');
                    }

                    if (panel) {
                        panel.style.display = 'none';
                    }
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key !== 'Escape') {
                    return;
                }

                document.querySelectorAll('.public-setting-dropdown').forEach(function (dropdown) {
                    const trigger = dropdown.querySelector('.ht-setting-trigger');
                    const panel = dropdown.querySelector('.ht-setting');

                    if (trigger) {
                        trigger.classList.remove('is-active');
                    }

                    if (panel) {
                        panel.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endonce
