<aside>

    <ul class="nav flex-column">
        {{-- Menu superadmin --}}
         @if (auth()->user()->role_user === 'superadmin')
            <li class="nav-item"><a class="nav-link" href="#">Kelola Semua User</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Pengaturan Sistem</a></li>
        @endif

        {{-- Menu admin --}}
        @if (auth()->user()->role_user == 'admin')
            <li class="nav-item"><a class="nav-link" href="#">Laporan Admin</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Kelola Produk</a></li>
        @endif

        {{-- Menu HRD --}}
        @if (auth()->user()->role_user == 'hrd')
            <li class="nav-item"><a class="nav-link" href="{{route('dashboard_hrd')}}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Riwayat Pengajuan</a></li>
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    href="#menuCuti" role="button" aria-expanded="false" aria-controls="menuCuti"
                    onclick="this.querySelector('i.rotate').classList.toggle('down')">
                    <span>Cuti</span>
                    <i class="bi bi-chevron-down rotate"></i>
                </a>

                <div class="collapse ps-2" id="menuCuti">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link" href="{{route('cuti_tahunan')}}">Cuti Tahunan</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('cuti_nontahunan')}}">Cuti Non-Tahunan</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{route('karyawan.index')}}">Karyawan</a></li>
        @endif

        {{-- Menu user biasa --}}
        @if (auth()->user()->role_user == 'user')
            <li class="nav-item"><a class="nav-link" href="#">Profil Saya</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Pengajuan Cuti</a></li>
        @endif

        {{-- Logout --}}
        <li class="nav-item mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </li>
    </ul>
</aside>
