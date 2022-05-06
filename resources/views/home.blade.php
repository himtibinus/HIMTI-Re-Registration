@extends('layouts.app')

@section('content')
    <div class="container my-5">
        @if ($QuartilActive != null)
            <div class="card">
                <div class="card-header">
                    Re-Registration
                </div>
                <div class="card-body">
                    <h5 class="card-title">PENDATAAN ULANG ANGGOTA HIMTI 2022</h5>
                    <p class="card-text">Halo, para aktivis dan pengurus HIMTI 2022! ☺️<br><br>

                        Di bulan ini, kami akan melakukan pendataan ulang yang mana data ini akan digunakan untuk keperluan
                        laporan kuartal ✨<br><br>

                        Maka dari itu, semua anggota HIMTI 2022 WAJIB untuk mengisi datanya dan diisi dengan baik dan benar.
                        ‼️<br><br>

                        Jika terdapat kesalahan pada data, maka hal tersebut berada di luar tanggung jawab kami.</p>
                    <a href="/re-regist/{{ $QuartilActive->Year }}/{{ $QuartilActive->Quartil }}"
                        class="btn btn-primary">Re-Regist</a>
                </div>
            </div>
        @endif
        <div class="card mt-5">
            <div class="card-header">
                Admin
            </div>
            <div class="card-body">
                <h5 class="card-title">To Manage Section and Question</h5>
                <p class="card-text">Coming Soon</p>
                <p class="card-text">Coming Soon</p>
                <a href="/Admin/{{ $QuartilActive->Year }}" class="btn btn-primary">Admin</a>
            </div>
        </div>
    </div>
@endsection
