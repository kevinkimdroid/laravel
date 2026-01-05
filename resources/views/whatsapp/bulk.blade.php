<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opening WhatsApp Reminders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-whatsapp display-1 text-success mb-3"></i>
                        <h3 class="mb-3">Opening WhatsApp Reminders</h3>
                        <p class="text-muted mb-4">
                            Opening WhatsApp for {{ count($links) }} member(s) in new tabs...
                        </p>
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const links = @json($links);
        
        // Open each WhatsApp link in a new tab with a small delay
        links.forEach((link, index) => {
            setTimeout(() => {
                window.open(link, '_blank');
            }, index * 500); // 500ms delay between each
        });
        
        // Redirect back after all links are opened
        setTimeout(() => {
            window.location.href = '{{ route("whatsapp.index") }}';
        }, (links.length * 500) + 1000);
    </script>
</body>
</html>

