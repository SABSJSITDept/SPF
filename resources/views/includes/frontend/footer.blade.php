    {{-- ─── Site Footer ─────────────────────────────────────────────────────── --}}
    <style>
        /* ═══════════════════════════════════════════════════════════════════
           CONTACT SECTION (LANDSCAPE)
        ═══════════════════════════════════════════════════════════════════ */
        .contact-section {
            background: linear-gradient(135deg, #0d2b55 0%, #1a4a8a 50%, #0d2b55 100%);
            padding: 20px 0 0;
            position: relative;
            overflow: hidden;
        }

        .contact-section::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            pointer-events: none;
        }

        .contact-section::after {
            content: '';
            position: absolute;
            bottom: 20px;
            left: -40px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.025);
            pointer-events: none;
        }

        .contact-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            position: relative;
            z-index: 1;
        }

        /* ── Section label ── */
        .contact-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 100px;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.75);
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .contact-eyebrow span.dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4fc3f7;
            animation: blink 1.8s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* ── Landscape grid ── */
        .contact-landscape {
            display: grid;
            grid-template-columns: 1fr 1px 1fr 1px 1fr;
            gap: 0;
            align-items: stretch;
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255,255,255,0.035);
            backdrop-filter: blur(6px);
            margin-bottom: 16px;
        }

        .contact-divider {
            background: rgba(255,255,255,0.1);
            width: 1px;
            align-self: stretch;
            margin: 10px 0;
        }

        .contact-card {
            padding: 14px 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
            transition: background 0.3s ease;
        }

        .contact-card:hover {
            background: rgba(255,255,255,0.04);
        }

        .contact-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(79,195,247,0.12);
            border: 1px solid rgba(79,195,247,0.2);
            flex-shrink: 0;
            margin-bottom: 2px;
        }

        .contact-card-icon svg {
            color: #4fc3f7;
            fill: #4fc3f7;
        }

        .contact-card-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            margin: 0;
        }

        .contact-card-value {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            margin: 0;
            line-height: 1.4;
        }

        .contact-card-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.50);
            margin: 0;
            line-height: 1.4;
        }

        .contact-card a {
            color: #4fc3f7;
            text-decoration: none;
            transition: color 0.2s;
        }

        .contact-card a:hover {
            color: #81d4fa;
        }

        /* ── About stripe ── */
        .footer-about-stripe {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .footer-about-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .footer-about-logo img {
            width: 34px;
            height: 34px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: 0.85;
        }

        .footer-about-name {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .footer-about-tag {
            font-size: 10px;
            color: rgba(255,255,255,0.45);
            margin: 1px 0 0;
        }

        .footer-about-text {
            flex: 1;
            font-size: 11px;
            line-height: 1.5;
            color: rgba(255,255,255,0.55);
            margin: 0;
            max-width: 600px;
        }

        /* ── App Download ── */
        .footer-app-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }

        .footer-app-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.40);
            align-self: flex-start;
        }

        /* QR + Buttons side by side (reference image style) */
        .app-download-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qr-box {
            width: 56px;
            height: 56px;
            background: #fff;
            border-radius: 6px;
            padding: 4px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-box svg {
            width: 48px;
            height: 48px;
        }

        .store-buttons-col {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .store-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 6px;
            text-decoration: none;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.25s ease;
            min-width: 110px;
            letter-spacing: 0.2px;
        }

        .store-btn:hover {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.35);
            transform: translateX(2px);
            color: #fff;
        }

        .store-btn svg {
            flex-shrink: 0;
        }

        /* ── Bottom bar ── */
        .footer-bottom {
            background: rgba(0,0,0,0.25);
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .footer-bottom-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 8px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .footer-bottom-inner span {
            font-size: 11px;
            color: rgba(255,255,255,0.35);
        }

        .footer-bottom-inner span strong {
            color: rgba(255,255,255,0.55);
            font-weight: 600;
        }

        /* ══ RESPONSIVE ══════════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .contact-landscape {
                grid-template-columns: 1fr;
            }
            .contact-divider { display: none; }
            .contact-card {
                padding: 10px 16px;
                border-bottom: 1px solid rgba(255,255,255,0.08);
                flex-direction: row;
                align-items: center;
                gap: 12px;
            }
            .contact-card:last-child { border-bottom: none; }
            .contact-card-icon { margin-bottom: 0; }
            .footer-about-stripe {
                flex-wrap: wrap;
                gap: 12px;
            }
            .footer-app-section { align-items: flex-start; }
        }

        @media (max-width: 768px) {
            .contact-section { padding: 14px 0 0; }
            .contact-inner { padding: 0 14px; }
            .contact-card { padding: 10px 14px; }
            .footer-about-stripe { padding: 10px 14px; }
            .footer-about-text { display: none; }
            .footer-bottom-inner {
                flex-direction: column;
                text-align: center;
                padding: 6px 14px;
            }
            .app-download-row { gap: 8px; }
            .qr-box { width: 48px; height: 48px; }
            .qr-box svg { width: 40px; height: 40px; }
            .store-btn { min-width: 100px; font-size: 10px; padding: 5px 10px; }
        }
    </style>

    {{-- ════════════════════════════════════════════════════════════
         CONTACT US SECTION
    ════════════════════════════════════════════════════════════ --}}
    <section class="contact-section">
        <div class="contact-inner">

            {{-- Eyebrow label --}}
            <div class="contact-eyebrow">
                <span class="dot"></span>
                Get In Touch
            </div>

            {{-- Landscape contact grid --}}
            <div class="contact-landscape">

                {{-- Card 1: Address --}}
                <div class="contact-card">
                    <div class="contact-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 16 16">
                            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32.066 32.066 0 0 1 8 14.58a32.068 32.068 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94z"/>
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" fill="rgba(255,255,255,0.5)"/>
                        </svg>
                    </div>
                    <p class="contact-card-label">Our Office</p>
                    <p class="contact-card-value">Bikaner, Rajasthan</p>
                    <p class="contact-card-sub">Acharya Nanesh Marg, Gangashahar,<br>Nokha Road, Bikaner — 334001</p>
                </div>

                <div class="contact-divider"></div>

                {{-- Card 2: Phone --}}
                <div class="contact-card">
                    <div class="contact-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 16 16">
                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328z"/>
                        </svg>
                    </div>
                    <p class="contact-card-label">Phone / WhatsApp</p>
                    <p class="contact-card-value"><a href="tel:+917877643398">+91&nbsp;78776&nbsp;43398</a></p>
                    <p class="contact-card-sub">Mon – Sat, 10:00 AM – 6:00 PM IST</p>
                </div>

                <div class="contact-divider"></div>

                {{-- Card 3: Email --}}
                <div class="contact-card">
                    <div class="contact-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                        </svg>
                    </div>
                    <p class="contact-card-label">Email Address</p>
                    <p class="contact-card-value"><a href="mailto:spf@sadhumargi.com">spf@sadhumargi.com</a></p>
                    <p class="contact-card-sub">We reply within 24–48 business hours</p>
                </div>

            </div>{{-- /.contact-landscape --}}
        </div>{{-- /.contact-inner --}}

        {{-- ══ About + App Download stripe ══ --}}
        <div class="footer-about-stripe">

            {{-- Logo + Name --}}
            <div class="footer-about-logo">
                <img src="/uploads/SPF_logo.png" alt="SPF Logo" />
                <div>
                    <p class="footer-about-name">Sadhumargi Professional Forum</p>
                    <p class="footer-about-tag">Empowering Community Professionals</p>
                </div>
            </div>

            {{-- About text --}}
            <p class="footer-about-text">
                SPF is a global platform uniting professionals from the Sadhumargi Jain community —
                a space for connection, spiritual growth, and professional excellence, born from divine
                inspiration during Chaturmas 2019.
            </p>

            {{-- App Download (QR left · Buttons right) --}}
            <div class="footer-app-section">
                <p class="footer-app-label">Download the App</p>
                <div class="app-download-row">

                    {{-- QR Code — sadhumargi.in --}}
                    <a href="https://sadhumargi.in/" target="_blank" class="qr-box" title="Scan to visit sadhumargi.in" style="text-decoration:none;">
                        <img src="/uploads/sadhumargi_qr.png" alt="Scan QR — sadhumargi.in" style="width:100%;height:100%;object-fit:contain;border-radius:4px;" />
                    </a>

                    {{-- Store buttons (vertical stack) --}}
                    <div class="store-buttons-col">
                        <a href="https://sadhumargi.in/"
                           target="_blank" class="store-btn" aria-label="Visit on Play Store">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" viewBox="0 0 24 24">
                                <path d="M3.18 23.76c.35.19.75.2 1.12.01l12.2-6.87-2.56-2.56L3.18 23.76zM.54 1.2C.2 1.57 0 2.12 0 2.83v18.34c0 .71.2 1.26.54 1.63l.09.08 10.27-10.27v-.24L.63 1.12.54 1.2zm19.25 9.42-2.57-1.45-2.88 2.88 2.88 2.88 2.59-1.46c.74-.42.74-1.44-.02-1.85zM4.3.23c-.37-.19-.77-.18-1.12.01l-.09.09 10.28 10.28h.24L4.3.23z"/>
                            </svg>
                            Play Store
                        </a>
                        <a href="https://sadhumargi.in/"
                           target="_blank" class="store-btn" aria-label="Visit on App Store">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" viewBox="0 0 24 24">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2.01.77-3.27.82-1.31.05-2.3-1.3-3.14-2.53-1.7-2.52-2.85-7.12-1.19-10.28.82-1.65 2.28-2.65 3.85-2.7 1.27-.05 2.46.85 3.25.85.78 0 2.21-1.01 3.73-.85.43.07 1.65.27 2.5 2.01-.08.05-1.98 1.12-1.98 3.35 0 2.54 2.09 3.42 2.1 3.44-.04.06-.41 1.45-1.35 2.68zm-3.63-17.68c.04 1.35-.49 2.68-1.29 3.63-.81.97-2.03 1.71-3.2 1.62-.02-1.31.49-2.63 1.28-3.57.82-.98 2.08-1.72 3.21-1.68z"/>
                            </svg>
                            App Store
                        </a>
                    </div>

                </div>{{-- /.app-download-row --}}
            </div>{{-- /.footer-app-section --}}

        </div>{{-- /.footer-about-stripe --}}

        {{-- Bottom bar --}}
        <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <span>&copy; {{ date('Y') }} <strong>SABSJS IT Department</strong>. All rights reserved.</span>
                <span>Designed &amp; Developed for <strong>SPF Bikaner Office</strong></span>
            </div>
        </div>

    </section>{{-- /.contact-section --}}

</body>
</html>
