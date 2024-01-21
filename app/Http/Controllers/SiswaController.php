<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Nilai;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    public function listKelas()
    {
        $kelas = Kelas::all();
        return response()->json($kelas);
    }

    public function detailKelas($id)
    {
        $kelas = Kelas::with('siswa')->find($id);
        return response()->json($kelas);
    }

    public function tambahKelas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $kelas = Kelas::create($request->all());
        return response()->json($kelas, 201);
    }

    public function perbaruiKelas(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $kelas = Kelas::find($id);
        $kelas->update($request->all());

        return response()->json($kelas, 200);
    }

    public function listSiswa()
    {
        $siswa = Siswa::all();
        return response()->json($siswa);
    }

    public function detailSiswa($id)
    {
        $siswa = Siswa::with('nilai')->find($id);

        // Hitung nilai mata pelajaran untuk siswa
        $siswa->nilai_mata_pelajaran = $this->hitungNilaiMataPelajaran($siswa);

        // Hitung nilai per mata pelajaran
        $nilaiPerMataPelajaran = $this->hitungNilaiPerMataPelajaran($siswa);

        return response()->json(['siswa' => $siswa, 'nilai_per_mata_pelajaran' => $nilaiPerMataPelajaran]);
    }

    public function detailNilai($id)
    {
        $siswa = Siswa::with('nilai')->find($id);

        // Hitung nilai mata pelajaran untuk siswa
        $siswa->nilai_mata_pelajaran = $this->hitungNilaiMataPelajaran($siswa);

        // Hitung nilai per mata pelajaran
        $nilaiPerMataPelajaran = $this->hitungNilaiPerMataPelajaran($siswa);

        return response()->json(['siswa' => $siswa, 'nilai_per_mata_pelajaran' => $nilaiPerMataPelajaran]);
    }

    public function tambahNilai(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswa,id',
            'mata_pelajaran' => 'required|string',
            'latihan_soal' => 'required|numeric',
            'ulangan_harian' => 'required|numeric',
            'ulangan_tengah_semester' => 'required|numeric',
            'ulangan_semester' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $nilai = Nilai::create($request->all());

        return response()->json($nilai, 201);
    }

    private function hitungNilaiMataPelajaran($siswa)
    {
        $latihanSoal = $siswa->nilai->pluck('latihan_soal')->avg();
        $ulanganHarian = $siswa->nilai->pluck('ulangan_harian')->avg();
        $ulanganTengahSemester = $siswa->nilai->pluck('ulangan_tengah_semester')->avg();
        $ulanganSemester = $siswa->nilai->pluck('ulangan_semester')->avg();

        // Sesuaikan bobot rumus dengan persentase yang diberikan
        $nilaiMataPelajaran = 0.15 * $latihanSoal + 0.20 * $ulanganHarian + 0.25 * $ulanganTengahSemester + 0.40 * $ulanganSemester;

        return $nilaiMataPelajaran;
    }

    private function hitungNilaiPerMataPelajaran($siswa)
    {
        $nilaiPerMataPelajaran = [];

        foreach ($siswa->nilai as $nilai) {
            $mataPelajaran = $nilai->mata_pelajaran;
            $latihanSoal = $nilai->latihan_soal;
            $ulanganHarian = $nilai->ulangan_harian;
            $ulanganTengahSemester = $nilai->ulangan_tengah_semester;
            $ulanganSemester = $nilai->ulangan_semester;

            // Sesuaikan bobot rumus dengan persentase yang diberikan
            $nilaiMataPelajaran = 0.15 * $latihanSoal + 0.20 * $ulanganHarian + 0.25 * $ulanganTengahSemester + 0.40 * $ulanganSemester;

            $nilaiPerMataPelajaran[] = [
                'mata_pelajaran' => $mataPelajaran,
                'nilai' => $nilaiMataPelajaran,
            ];
        }

        return $nilaiPerMataPelajaran;
    }
}
