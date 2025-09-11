<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
    #previewContainer {
        width: 80%;
        height: 500px; /* ubah sesuai kebutuhan */
    }
    #pdfPreview {
        width: 80%;
        height: 100%;
        border-radius: 10px; /* opsional biar lebih rapi */
    }
</style>
</head>

<body class="antialiased">
    <div class="container mt-4">
        <h3>Upload Jurnal (PDF)</h3>

        <form>
            <div class="mb-3">
                <label for="pdfFile" class="form-label">Pilih File PDF</label>
                <input type="file" class="form-control" id="pdfFile" accept="application/pdf">
            </div>

            <div class="mt-3">
                <h5>Preview PDF:</h5>
                <div id="previewContainer" class="border p-2 rounded" style="height:500px; display:none;">
                    <embed id="pdfPreview" type="application/pdf" width="100%" height="100%">
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<script>
    document.getElementById('pdfFile').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type === "application/pdf") {
            const fileURL = URL.createObjectURL(file);
            document.getElementById('pdfPreview').setAttribute('src', fileURL);
            document.getElementById('previewContainer').style.display = "block";
        } else {
            alert("Harap pilih file PDF!");
            document.getElementById('previewContainer').style.display = "none";
        }
    });
</script>