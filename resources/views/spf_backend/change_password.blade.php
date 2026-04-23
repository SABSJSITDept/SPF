@include('includes.backend.header')

<div style="min-height: calc(100vh - 120px); display: flex; align-items: center; justify-content: center; padding: 40px 20px; background: #f0f2f5;">
    
    <div style="background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); max-width: 500px; width: 100%; padding: 40px 36px;">

        <h1 style="text-align: center; font-size: 22px; font-weight: 700; color: #1a237e; margin-bottom: 28px;">Change Password</h1>

        {{-- Success / Error Messages --}}
        @if(session('success'))
            <div style="background: #e8f5e9; border-left: 4px solid #388e3c; color: #1b5e20; padding: 12px 14px; border-radius: 6px; margin-bottom: 24px; font-size: 13px; font-weight: 500;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #ffebee; border-left: 4px solid #c62828; color: #b71c1c; padding: 12px 14px; border-radius: 6px; margin-bottom: 24px; font-size: 13px;">
                @foreach($errors->all() as $error)
                    <div style="margin-bottom: 6px;">✗ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form id="changePasswordForm" method="POST" action="{{ route('admin.password.update') }}" novalidate>
            @csrf

            {{-- Current Password --}}
            <div class="form-group" style="margin-bottom: 20px; position: relative;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px;">
                    Current Password <span style="color: #c62828;">*</span>
                </label>
                <input
                    type="password"
                    name="current_password"
                    id="current_password"
                    style="width: 100%; padding: 10px 40px 10px 14px; border: 1.5px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s;"
                    placeholder="Enter your current password"
                    required
                />
                <span id="toggleCurrent" onclick="toggleVisibility('current_password', 'toggleCurrent')"
                    style="position: absolute; right: 12px; top: 34px; cursor: pointer; user-select: none; font-size: 16px; color: #888;">👁</span>
                <span id="currentErr" style="display: none; color: #c62828; font-size: 12px; margin-top: 4px; display: block;"></span>
            </div>

            {{-- New Password --}}
            <div class="form-group" style="margin-bottom: 20px; position: relative;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px;">
                    New Password <span style="color: #c62828;">*</span>
                </label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    style="width: 100%; padding: 10px 40px 10px 14px; border: 1.5px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s;"
                    placeholder="Enter new password"
                    minlength="8"
                    required
                />
                <span id="toggleNew" onclick="toggleVisibility('password', 'toggleNew')"
                    style="position: absolute; right: 12px; top: 34px; cursor: pointer; user-select: none; font-size: 16px; color: #888;">👁</span>
                <span id="passwordErr" style="display: none; color: #c62828; font-size: 12px; margin-top: 4px; display: block;"></span>
            </div>

            {{-- Confirm Password --}}
            <div class="form-group" style="margin-bottom: 26px; position: relative;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px;">
                    Confirm Password <span style="color: #c62828;">*</span>
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    style="width: 100%; padding: 10px 40px 10px 14px; border: 1.5px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s;"
                    placeholder="Re-enter new password"
                    required
                />
                <span id="toggleConfirm" onclick="toggleVisibility('password_confirmation', 'toggleConfirm')"
                    style="position: absolute; right: 12px; top: 34px; cursor: pointer; user-select: none; font-size: 16px; color: #888;">👁</span>
                <span id="confirmErr" style="display: none; color: #c62828; font-size: 12px; margin-top: 4px; display: block;"></span>
            </div>

            {{-- Strength Indicator --}}
            <div style="margin-bottom: 26px;">
                <div style="font-size: 12px; color: #555; margin-bottom: 6px;">Password Strength: <strong id="strengthLabel" style="color: #aaa;">—</strong></div>
                <div style="height: 6px; background: #e0e0e0; border-radius: 3px; overflow: hidden;">
                    <div id="strengthBar" style="height: 100%; width: 0%; border-radius: 3px; transition: width 0.3s, background 0.3s;"></div>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display: flex; gap: 12px;">
                <button
                    type="submit"
                    id="submitBtn"
                    style="flex: 1; padding: 11px 16px; background: #1a237e; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#0d47a1'" onmouseout="this.style.background='#1a237e'"
                >
                    Update Password
                </button>
                <a href="{{ route('admin.dashboard') }}"
                    style="flex: 1; padding: 11px 16px; background: #e0e0e0; color: #333; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; transition: background 0.2s;"
                    onmouseover="this.style.background='#d0d0d0'" onmouseout="this.style.background='#e0e0e0'"
                >
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@include('includes.backend.footer')

<script>
    /* ── Toggle password visibility ── */
    function toggleVisibility(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.textContent = '🙈';
        } else {
            field.type = 'password';
            icon.textContent = '👁';
        }
    }

    /* ── Password strength ── */
    document.getElementById('password').addEventListener('input', function () {
        const val   = this.value;
        const bar   = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');
        let score   = 0;

        if (val.length >= 8)              score++;
        if (/[A-Z]/.test(val))            score++;
        if (/[0-9]/.test(val))            score++;
        if (/[^A-Za-z0-9]/.test(val))     score++;

        const levels = [
            { pct: '0%',   color: '#e0e0e0', text: '—' },
            { pct: '25%',  color: '#e53935', text: 'Weak' },
            { pct: '50%',  color: '#fb8c00', text: 'Fair' },
            { pct: '75%',  color: '#43a047', text: 'Good' },
            { pct: '100%', color: '#1b5e20', text: 'Strong' },
        ];
        bar.style.width      = levels[score].pct;
        bar.style.background = levels[score].color;
        label.textContent    = levels[score].text;
        label.style.color    = levels[score].color;
    });

    /* ── Client-side validation ── */
    document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
        let valid = true;

        // Current password
        const current = document.getElementById('current_password');
        const currentErr = document.getElementById('currentErr');
        if (current.value.trim().length === 0) {
            currentErr.textContent = 'Current password is required.';
            currentErr.style.display = 'block';
            current.style.borderColor = '#c62828';
            valid = false;
        } else {
            currentErr.style.display = 'none';
            current.style.borderColor = '#ddd';
        }

        // New password length
        const pwd = document.getElementById('password');
        const pwdErr = document.getElementById('passwordErr');
        if (pwd.value.length < 8) {
            pwdErr.textContent = 'Password must be at least 8 characters.';
            pwdErr.style.display = 'block';
            pwd.style.borderColor = '#c62828';
            valid = false;
        } else {
            pwdErr.style.display = 'none';
            pwd.style.borderColor = '#ddd';
        }

        // Confirm match
        const conf = document.getElementById('password_confirmation');
        const confErr = document.getElementById('confirmErr');
        if (pwd.value !== conf.value) {
            confErr.textContent = 'Passwords do not match.';
            confErr.style.display = 'block';
            conf.style.borderColor = '#c62828';
            valid = false;
        } else {
            confErr.style.display = 'none';
            conf.style.borderColor = '#ddd';
        }

        if (!valid) e.preventDefault();
    });

    /* ── Focus border highlight ── */
    document.querySelectorAll('input[type="password"], input[type="text"]').forEach(function (el) {
        if (el.name.includes('password')) {
            el.addEventListener('focus', function () { this.style.borderColor = '#1a237e'; });
            el.addEventListener('blur',  function () { this.style.borderColor = '#ddd'; });
        }
    });
</script>
