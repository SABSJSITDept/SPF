</div>{{-- end .page-wrapper --}}

<footer class="admin-footer">
    <div class="footer-content">
        &copy; {{ date('Y') }} SPF Admin Panel &mdash; SABSJS IT Department. All rights reserved.
    </div>
</footer>

<style>
    .admin-footer {
        background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%);
        color: rgba(255,255,255,0.75);
        text-align: center;
        padding: 16px 24px;
        font-size: 12.5px;
        margin-top: auto;
        padding-top: 40px;
        width: 100%;
        flex-shrink: 0;
    }

    .footer-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        line-height: 1.5;
    }

    @media (max-width: 992px) {
        .admin-footer {
            padding: 14px 20px;
            font-size: 12px;
            padding-top: 32px;
        }

        .footer-content {
            padding: 0 16px;
        }
    }

    @media (max-width: 768px) {
        .admin-footer {
            padding: 14px 16px;
            font-size: 11.5px;
            padding-top: 24px;
        }

        .footer-content {
            padding: 0 12px;
        }
    }

    @media (max-width: 480px) {
        .admin-footer {
            padding: 12px 12px;
            font-size: 11px;
            padding-top: 20px;
        }

        .footer-content {
            padding: 0 10px;
        }
    }

    @media (max-width: 360px) {
        .admin-footer {
            padding: 10px 8px;
            font-size: 10px;
            padding-top: 16px;
        }

        .footer-content {
            padding: 0 8px;
        }
    }

    /* Safe area for notched devices */
    @supports (padding: env(safe-area-inset-bottom)) {
        .admin-footer {
            padding-bottom: max(16px, env(safe-area-inset-bottom));
            padding-left: max(24px, env(safe-area-inset-left));
            padding-right: max(24px, env(safe-area-inset-right));
        }
    }
</style>

<style>
/* Global Full Screen Spinner CSS */
#global-spinner-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(2px);
    z-index: 999999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
.spinner-ring {
    width: 60px;
    height: 60px;
    border: 5px solid #e0e0e0;
    border-top-color: #1a237e;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}
@keyframes spin { 100% { transform: rotate(360deg); } }
.spinner-text { color: #1a237e; font-weight: 600; font-size: 16px; font-family: 'Segoe UI', Arial, sans-serif; letter-spacing: 0.5px; }
</style>

<div id="global-spinner-overlay">
    <div class="spinner-ring"></div>
    <div class="spinner-text">Processing, please wait...</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showSpinner() {
        document.getElementById('global-spinner-overlay').style.display = 'flex';
    }
    function hideSpinner() {
        document.getElementById('global-spinner-overlay').style.display = 'none';
    }

    let downloadSpinnerTimer = null;
    function scheduleDownloadSpinnerHide() {
        if (downloadSpinnerTimer) {
            clearTimeout(downloadSpinnerTimer);
        }
        // For file downloads, browser usually stays on same page.
        // Auto-hide prevents spinner from getting stuck.
        downloadSpinnerTimer = setTimeout(() => {
            hideSpinner();
            downloadSpinnerTimer = null;
        }, 8000);
    }

    // Attach spinner to all synchronous form submissions automatically
    document.addEventListener('submit', function(e) {
        // Only show spinner if form isn't handled by JS `preventDefault`
        if (!e.defaultPrevented) {
            showSpinner();

            const submitter = e.submitter;
            const isExportDownload =
                submitter &&
                submitter.name === 'export' &&
                (submitter.value === 'excel' || submitter.value === 'pdf');

            if (isExportDownload) {
                scheduleDownloadSpinnerHide();
            }
        }
    });

    // In many browsers focus returns after download prompt; ensure spinner clears.
    window.addEventListener('focus', function() {
        hideSpinner();
    });

    // Custom confirm wrapper around sweet alert to replace window.confirm
    window.confirmSubmit = function(formElement, message, confirmColor = '#1a237e') {
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#757575',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                showSpinner();
                formElement.submit();
            }
        });
    };
    
    // Custom toast alerts
    window.showToast = function(message, type = 'warning') {
        Swal.fire({
            icon: type,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    };
</script>

</body>
</html>
