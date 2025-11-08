<?php
// A simple logout/exit page that attempts to close the tab (best-effort).
// Browsers typically only allow window.close() for windows opened via script.
// This page provides a manual fallback button if automatic close is blocked.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Goodbye</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:#f8f9fa; }
        .card { max-width:420px; width:100%; }
    </style>
</head>
<body>
    <div class="card shadow-sm p-4 text-center">
        <h3 class="mb-3">You have been logged out</h3>
        <p class="text-muted mb-3">For security, please close this browser tab or window.</p>
        <div class="mb-3">
            <button id="closeBtn" class="btn btn-danger">Close this tab</button>
            <a href="index.php" class="btn btn-link">Return to site</a>
        </div>
        <small class="text-muted">If the tab didn't close automatically, use the button above or close it manually.</small>
    </div>

    <script>
        function tryClose() {
            try {
                // Try recommended sequence: navigate away then close
                window.location.href = 'about:blank';
                window.close();
            } catch (e) {
                // ignore
            }
        }

        document.getElementById('closeBtn').addEventListener('click', function() {
            tryClose();
            // If still not closed, give focus to user
            window.focus();
        });

        // Attempt to close shortly after load (best-effort). Many browsers will block this
        // unless the page was opened by script, but it's harmless to try.
        setTimeout(tryClose, 300);
    </script>
</body>
</html>
