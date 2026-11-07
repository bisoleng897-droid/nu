@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil Dikirim - NU Rancamaya')

@section('content')
    <section class="my-20 max-w-3xl mx-auto text-center">
        <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center shadow-xl transform hover:scale-110 transition-transform duration-300" style="background:#EEF4D9">
            <svg class="w-12 h-12" style="color:#2F6B2F" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>

        <h1 class="mt-8 text-5xl md:text-6xl font-black leading-tight" style="color:#183D22">Formulir Terkirim</h1>

        <p class="mt-8 text-xl md:text-2xl font-semibold" style="color:#25351F">
            Pendaftaran berhasil dikirim!
        </p>

        <p class="mt-5 text-lg" style="color:#5D6F5D">
            Selamat datang, <strong style="color:#183D22">{{ session('nama_lengkap') }}</strong>. Formulir pendaftaran Anda sedang menunggu persetujuan dari admin. Anda belum menjadi anggota aktif sampai admin menyetujui data Anda.
        </p>

        <div class="mt-12 bg-white rounded-[32px] md:rounded-[40px] shadow-xl p-10 md:p-12 border transform hover:-translate-y-1 transition-all duration-300" style="border-color:rgba(24,61,34,.08)">
            <p class="text-xs font-bold tracking-[0.3em] uppercase" style="color:#7D8575">Nomor Pendaftaran Anda</p>
            <p class="mt-5 text-4xl md:text-5xl font-black tracking-wider" style="color:#2F6B2F">{{ session('nomor_pendaftaran') }}</p>
            <p class="mt-5 text-lg leading-relaxed" style="color:#5D6F5D">
                💾 Simpan nomor ini. Anda membutuhkannya (bersama NIK) untuk mengecek status pendaftaran di halaman verifikasi.
            </p>
        </div>

        <div class="mt-14 flex flex-col sm:flex-row items-center justify-center gap-5">
            <a href="{{ route('cek-status') }}" class="px-10 py-4 rounded-full font-bold text-white transition-all hover:shadow-lg hover:scale-105 active:scale-95 shadow-md text-lg" style="background:#2F6B2F">
                Cek Status Pendaftaran
            </a>
            <a href="{{ route('home') }}" class="px-10 py-4 rounded-full font-bold border-2 transition-all hover:shadow-lg hover:scale-105 active:scale-95 text-lg" style="border-color:#2F6B2F; color:#2F6B2F">
                Kembali ke Beranda
            </a>
        </div>
    </section>
@endsection
