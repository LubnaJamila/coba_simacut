// Jalankan setelah halaman selesai dimuat
document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi DataTables jika ada tabel dengan id #example
    const table = document.querySelector("#example");
    if (table) {
        new DataTable("#example", {
            paging: true,
            searching: true,
            info: true,
            lengthChange: true,
            destroy: true, // Hapus instance lama kalau ada
        });
    }
});
