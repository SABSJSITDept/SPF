@include('includes.frontend.header')

    <!-- Password Reset Wrapper -->
    <div class="form-wrapper" style="max-width:520px;">
        <div class="form-box">
            <h2 class="form-title" style="text-align:center;">Change Password</h2>

            <div class="form-content" style="padding:32px 36px;">

                {{-- Success / Error Messages --}}
                @if(session('success'))
                    <div style="background:#e8f5e9;border-left:4px solid #388e3c;color:#1b5e20;padding:12px 16px;border-radius:4px;margin-bottom:20px;font-size:13px;">
                        ✔ {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background:#ffebee;border-left:4px solid #c62828;color:#b71c1c;padding:12px 16px;border-radius:4px;margin-bottom:20px;font-size:13px;">
                        @foreach($errors->all() as $error)
                            <div>✖ {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form id="passwordResetForm" method="POST" action="{{ route('password.reset.submit') }}" novalidate>
                    @csrf

                    {{-- Mobile Number --}}
                    <div class="form-group" style="margin-bottom:20px;">
                        <label class="form-label" style="display:block;font-size:13px;font-weight:600;color:#333;margin-bottom:6px;">
                            Mobile Number <span class="required" style="color:#c62828;">*</span>
                        </label>
                        <input
                            type="tel"
                            name="mobile"
                            id="mobile"
                            class="form-input"
                            style="width:100%;padding:10px 12px;border:1px solid #bdbdbd;border-radius:4px;font-size:14px;outline:none;transition:border-color .2s;box-sizing:border-box;"
                            placeholder="Registered mobile number"
                            maxlength="10"
                            value="{{ old('mobile') }}"
                            required
                        />
                        <span id="mobileErr" style="display:none;color:#c62828;font-size:12px;margin-top:4px;"></span>
                    </div>

                    {{-- New Password --}}
                    <div class="form-group" style="margin-bottom:20px;position:relative;">
                        <label class="form-label" style="display:block;font-size:13px;font-weight:600;color:#333;margin-bottom:6px;">
                            New Password <span class="required" style="color:#c62828;">*</span>
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-input"
                            style="width:100%;padding:10px 40px 10px 12px;border:1px solid #bdbdbd;border-radius:4px;font-size:14px;outline:none;transition:border-color .2s;box-sizing:border-box;"
                            placeholder="Enter new password"
                            minlength="8"
                            required
                        />
                        <span id="toggleNew" onclick="toggleVisibility('password','toggleNew')"
                            style="position:absolute;right:12px;top:34px;cursor:pointer;user-select:none;font-size:16px;color:#888;">👁</span>
                        <span id="passwordErr" style="display:none;color:#c62828;font-size:12px;margin-top:4px;"></span>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group" style="margin-bottom:28px;position:relative;">
                        <label class="form-label" style="display:block;font-size:13px;font-weight:600;color:#333;margin-bottom:6px;">
                            Confirm Password <span class="required" style="color:#c62828;">*</span>
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-input"
                            style="width:100%;padding:10px 40px 10px 12px;border:1px solid #bdbdbd;border-radius:4px;font-size:14px;outline:none;transition:border-color .2s;box-sizing:border-box;"
                            placeholder="Re-enter new password"
                            required
                        />
                        <span id="toggleConfirm" onclick="toggleVisibility('password_confirmation','toggleConfirm')"
                            style="position:absolute;right:12px;top:34px;cursor:pointer;user-select:none;font-size:16px;color:#888;">👁</span>
                        <span id="confirmErr" style="display:none;color:#c62828;font-size:12px;margin-top:4px;"></span>
                    </div>

                    {{-- Strength Indicator --}}
                    <div style="margin-bottom:24px;">
                        <div style="font-size:12px;color:#555;margin-bottom:6px;">Password Strength: <strong id="strengthLabel" style="color:#aaa;">—</strong></div>
                        <div style="height:6px;background:#e0e0e0;border-radius:3px;overflow:hidden;">
                            <div id="strengthBar" style="height:100%;width:0%;border-radius:3px;transition:width .3s,background .3s;"></div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        id="submitBtn"
                        style="width:100%;padding:12px;background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:15px;font-weight:600;cursor:pointer;transition:background .2s;letter-spacing:0.3px;"
                        onmouseover="this.style.background='#1565c0'" onmouseout="this.style.background='#1976d2'"
                    >
                        Change Password
                    </button>

                    <p style="text-align:center;margin-top:16px;font-size:13px;color:#666;">
                        <a href="{{ url('/spf-registration') }}" style="color:#1976d2;text-decoration:none;">← Back to Registration</a>
                    </p>
                </form>

            </div><!-- /.form-content -->
        </div><!-- /.form-box -->
    </div><!-- /.form-wrapper -->

@include('includes.frontend.footer')

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
    document.getElementById('passwordResetForm').addEventListener('submit', function (e) {
        let valid = true;

        // Mobile
        const mobile = document.getElementById('mobile');
        const mobileErr = document.getElementById('mobileErr');
        if (!/^[6-9]\d{9}$/.test(mobile.value.trim())) {
            mobileErr.textContent = 'Enter a valid 10-digit Indian mobile number.';
            mobileErr.style.display = 'block';
            mobile.style.borderColor = '#c62828';
            valid = false;
        } else {
            mobileErr.style.display = 'none';
            mobile.style.borderColor = '#bdbdbd';
        }

        // Password length
        const pwd = document.getElementById('password');
        const pwdErr = document.getElementById('passwordErr');
        if (pwd.value.length < 8) {
            pwdErr.textContent = 'Password must be at least 8 characters.';
            pwdErr.style.display = 'block';
            pwd.style.borderColor = '#c62828';
            valid = false;
        } else {
            pwdErr.style.display = 'none';
            pwd.style.borderColor = '#bdbdbd';
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
            conf.style.borderColor = '#bdbdbd';
        }

        if (!valid) e.preventDefault();
    });

    /* ── Focus border highlight ── */
    document.querySelectorAll('.form-input').forEach(function (el) {
        el.addEventListener('focus', function () { this.style.borderColor = '#1976d2'; });
        el.addEventListener('blur',  function () { this.style.borderColor = '#bdbdbd'; });
    });
</script>
