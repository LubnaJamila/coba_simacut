@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Kalender Cuti Bersama {{ $year }}</h3>

        {{-- tampilkan hari ini --}}
        <p><strong>Hari ini: </strong><span id="todayText"></span></p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- pesan sukses / error --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- filter tahun --}}
        <form method="GET" action="{{ route('cuti_bersama') }}" class="mb-3">
            <div class="d-flex align-items-center gap-2">
                <label for="year">Pilih Tahun:</label>
                <select name="year" id="year" class="form-select w-auto" onchange="this.form.submit()">
                    @foreach ($tahunCutiTahunanList as $t)
                        <option value="{{ $t }}" {{ $t == $year ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="calendar-container">
            <div class="d-flex justify-content-between mb-2">
                <button class="btn btn-secondary btn-sm" id="prevMonth">&laquo; Sebelumnya</button>
                <h5 id="monthYear"></h5>
                <button class="btn btn-secondary btn-sm" id="nextMonth">Berikutnya &raquo;</button>
            </div>

            <div class="calendar" id="calendar"></div>
        </div>
    </div>

    <!-- Modal tambah cuti -->
    <div class="modal fade" id="cutiModal" tabindex="-1" aria-labelledby="cutiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('cuti_bersama.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cutiModalLabel">Tambah Cuti Bersama</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="tanggal_cuti_bersama" id="tanggalInput">
                        <div class="mb-3">
                            <label>Nama Cuti Bersama</label>
                            <input type="text" name="nama_cuti_bersama" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- style kalender --}}
    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .day-name,
        .day {
            padding: 10px;
            text-align: center;
            border-radius: 8px;
        }

        .day-name {
            font-weight: bold;
            background: #eee;
        }

        .day {
            min-height: 80px;
            cursor: pointer;
            background: #f9f9f9;
            position: relative;
        }

        .day small {
            display: block;
            font-size: 11px;
            margin-top: 4px;
        }

        .day.disabled {
            cursor: not-allowed;
            background: #f0f0f0;
            color: #aaa;
        }

        .day.weekend {
            background: #ffe5e5;
            /* merah muda untuk weekend */
            color: red;
        }
    </style>

    <script>
        const cutiBersamaData = @json($cutiBersamaList);
        const selectedYear = {{ $year }};
        const tahunCutiTahunan = {{ $tahunCutiTahunan ?? 'null' }}; // tahun cuti tahunan, null jika belum ada
        const today = new Date();
        const currentMonthToday = today.getMonth();
        let currentMonth = currentMonthToday;

        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        ];
        const calendar = document.getElementById("calendar");
        const monthYear = document.getElementById("monthYear");
        const todayText = document.getElementById("todayText");

        todayText.textContent = today.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        function renderCalendar(year, month) {
            calendar.innerHTML = "";
            monthYear.textContent = `${monthNames[month]} ${year}`;

            const daysName = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
            daysName.forEach(d => {
                const div = document.createElement("div");
                div.className = "day-name";
                div.textContent = d;
                calendar.appendChild(div);
            });

            const firstDay = new Date(year, month, 1).getDay();
            const lastDate = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                const div = document.createElement("div");
                div.className = "day empty";
                calendar.appendChild(div);
            }

            for (let d = 1; d <= lastDate; d++) {
                const div = document.createElement("div");
                div.className = "day";
                div.innerHTML = `<div>${d}</div>`;

                const tanggalFull = `${year}-${String(month+1).padStart(2,"0")}-${String(d).padStart(2,"0")}`;
                // cek cuti bersama
                const cuti = cutiBersamaData.find(c => c.tanggal_cuti_bersama === tanggalFull);
                if (cuti) {
                    const label = document.createElement("small");
                    label.textContent = cuti.nama_cuti_bersama;
                    label.style.color = "red";
                    div.appendChild(label);

                    // warna background dan angka tanggal juga merah
                    div.style.background = "#ffe5e5";
                    div.style.color = "red"; // ini membuat angka tanggal ikut merah
                }

                // tandai hari ini
                if (year === today.getFullYear() && month === today.getMonth() && d === today.getDate()) {
                    div.style.border = "2px solid #007bff";
                    div.title = "Hari ini";
                }

                // *** Tambahkan logika untuk Sabtu & Minggu ***
                const dayOfWeek = new Date(year, month, d).getDay();
                if (dayOfWeek === 0 || dayOfWeek === 6) { // Minggu = 0, Sabtu = 6
                    div.classList.add('weekend');
                }

                // aktifkan klik hanya jika:
                // 1. tanggal >= hari ini
                // 2. tahun sama dengan tahun cuti tahunan yang sudah diinput
                const todayDateOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                const dateOnly = new Date(year, month, d);

                if (dateOnly >= todayDateOnly) {
                    div.addEventListener("click", () => {
                        document.getElementById("tanggalInput").value = tanggalFull;
                        new bootstrap.Modal(document.getElementById("cutiModal")).show();
                    });
                } else {
                    div.classList.add("disabled");
                }


                calendar.appendChild(div);
            }
        }

        document.getElementById("prevMonth").addEventListener("click", () => {
            currentMonth--;
            if (currentMonth < 0) currentMonth = 11;
            renderCalendar(selectedYear, currentMonth);
        });

        document.getElementById("nextMonth").addEventListener("click", () => {
            currentMonth++;
            if (currentMonth > 11) currentMonth = 0;
            renderCalendar(selectedYear, currentMonth);
        });

        renderCalendar(selectedYear, currentMonth);
    </script>
@endsection
