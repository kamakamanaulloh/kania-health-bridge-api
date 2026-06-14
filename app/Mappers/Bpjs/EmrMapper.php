<?php

namespace App\Mappers\Bpjs;

use InvalidArgumentException;

class EmrMapper
{
    public function kunjungan(array $input): array
    {
        $this->require($input, ['no_rawat', 'no_kartu', 'nik', 'nama_pasien', 'tgl_kunjungan']);

        return [
            'no_rawat' => $input['no_rawat'],
            'no_sep' => $input['no_sep'] ?? null,
            'no_kartu' => $input['no_kartu'],
            'nik' => $input['nik'],
            'nama_pasien' => $input['nama_pasien'],
            'tanggal_kunjungan' => $input['tgl_kunjungan'],
            'kode_poli' => $input['kode_poli'] ?? null,
            'nama_poli' => $input['nama_poli'] ?? null,
            'kode_dokter' => $input['kode_dokter'] ?? null,
            'nama_dokter' => $input['nama_dokter'] ?? null,
            'jenis_pelayanan' => $input['jenis_pelayanan'] ?? 'rawat_jalan',
            'cara_masuk' => $input['cara_masuk'] ?? null,
            'keluhan_utama' => $input['keluhan_utama'] ?? null,
            'anamnesis' => $input['anamnesis'] ?? null,
            'pemeriksaan_fisik' => $input['pemeriksaan_fisik'] ?? null,
            'vital_sign' => $input['vital_sign'] ?? [
                'sistole' => $input['sistole'] ?? null,
                'diastole' => $input['diastole'] ?? null,
                'nadi' => $input['nadi'] ?? null,
                'respirasi' => $input['respirasi'] ?? null,
                'suhu' => $input['suhu'] ?? null,
                'spo2' => $input['spo2'] ?? null,
                'berat_badan' => $input['berat_badan'] ?? null,
                'tinggi_badan' => $input['tinggi_badan'] ?? null,
            ],
            'raw' => $input['raw'] ?? null,
        ];
    }

    public function diagnosa(array $input): array
    {
        $this->require($input, ['no_rawat', 'diagnosa']);

        return [
            'no_rawat' => $input['no_rawat'],
            'no_sep' => $input['no_sep'] ?? null,
            'diagnosa' => array_map(fn ($item) => [
                'kode' => $item['kode'] ?? $item['kd_diagnosa'] ?? null,
                'nama' => $item['nama'] ?? $item['nm_diagnosa'] ?? null,
                'tipe' => $item['tipe'] ?? $item['jenis'] ?? 'sekunder',
                'kasus' => $item['kasus'] ?? null,
            ], $this->arrayList($input['diagnosa'])),
        ];
    }

    public function tindakan(array $input): array
    {
        $this->require($input, ['no_rawat', 'tindakan']);

        return [
            'no_rawat' => $input['no_rawat'],
            'no_sep' => $input['no_sep'] ?? null,
            'tindakan' => array_map(fn ($item) => [
                'kode' => $item['kode'] ?? $item['kd_tindakan'] ?? null,
                'nama' => $item['nama'] ?? $item['nm_tindakan'] ?? null,
                'tanggal' => $item['tanggal'] ?? $input['tanggal'] ?? null,
                'jumlah' => $item['jumlah'] ?? 1,
                'dokter' => $item['dokter'] ?? $input['dokter'] ?? null,
            ], $this->arrayList($input['tindakan'])),
        ];
    }

    public function resep(array $input): array
    {
        $this->require($input, ['no_rawat', 'obat']);

        return [
            'no_rawat' => $input['no_rawat'],
            'no_sep' => $input['no_sep'] ?? null,
            'no_resep' => $input['no_resep'] ?? null,
            'tanggal_resep' => $input['tanggal_resep'] ?? $input['tanggal'] ?? null,
            'obat' => array_map(fn ($item) => [
                'kode' => $item['kode'] ?? $item['kd_obat'] ?? null,
                'nama' => $item['nama'] ?? $item['nm_obat'] ?? null,
                'jumlah' => $item['jumlah'] ?? 1,
                'satuan' => $item['satuan'] ?? null,
                'aturan_pakai' => $item['aturan_pakai'] ?? null,
                'signa' => $item['signa'] ?? null,
            ], $this->arrayList($input['obat'])),
        ];
    }

    public function laboratorium(array $input): array
    {
        $this->require($input, ['no_rawat', 'hasil_lab']);

        return [
            'no_rawat' => $input['no_rawat'],
            'no_sep' => $input['no_sep'] ?? null,
            'tanggal_periksa' => $input['tanggal_periksa'] ?? $input['tanggal'] ?? null,
            'hasil_lab' => array_map(fn ($item) => [
                'kode' => $item['kode'] ?? $item['kd_pemeriksaan'] ?? null,
                'nama' => $item['nama'] ?? $item['nm_pemeriksaan'] ?? null,
                'hasil' => $item['hasil'] ?? null,
                'satuan' => $item['satuan'] ?? null,
                'nilai_rujukan' => $item['nilai_rujukan'] ?? null,
                'keterangan' => $item['keterangan'] ?? null,
            ], $this->arrayList($input['hasil_lab'])),
        ];
    }

    public function radiologi(array $input): array
    {
        $this->require($input, ['no_rawat', 'hasil_radiologi']);

        return [
            'no_rawat' => $input['no_rawat'],
            'no_sep' => $input['no_sep'] ?? null,
            'tanggal_periksa' => $input['tanggal_periksa'] ?? $input['tanggal'] ?? null,
            'hasil_radiologi' => array_map(fn ($item) => [
                'kode' => $item['kode'] ?? $item['kd_pemeriksaan'] ?? null,
                'nama' => $item['nama'] ?? $item['nm_pemeriksaan'] ?? null,
                'hasil' => $item['hasil'] ?? null,
                'kesan' => $item['kesan'] ?? null,
                'dokter' => $item['dokter'] ?? $input['dokter'] ?? null,
            ], $this->arrayList($input['hasil_radiologi'])),
        ];
    }

    public function resume(array $input): array
    {
        $this->require($input, ['no_rawat', 'no_kartu', 'nik', 'nama_pasien']);

        return [
            'no_rawat' => $input['no_rawat'],
            'no_sep' => $input['no_sep'] ?? null,
            'no_kartu' => $input['no_kartu'],
            'nik' => $input['nik'],
            'nama_pasien' => $input['nama_pasien'],
            'tanggal_masuk' => $input['tanggal_masuk'] ?? $input['tgl_kunjungan'] ?? null,
            'tanggal_keluar' => $input['tanggal_keluar'] ?? null,
            'dokter_penanggung_jawab' => $input['dokter_penanggung_jawab'] ?? $input['nama_dokter'] ?? null,
            'diagnosa' => $input['diagnosa'] ?? [],
            'tindakan' => $input['tindakan'] ?? [],
            'terapi' => $input['terapi'] ?? [],
            'hasil_penunjang' => $input['hasil_penunjang'] ?? [],
            'kondisi_pulang' => $input['kondisi_pulang'] ?? null,
            'instruksi_lanjutan' => $input['instruksi_lanjutan'] ?? null,
        ];
    }

    private function require(array $input, array $fields): void
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $input) || $input[$field] === null || $input[$field] === '') {
                throw new InvalidArgumentException("$field wajib diisi");
            }
        }
    }

    private function arrayList(mixed $value): array
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Data item harus berupa array');
        }

        if ($value === []) {
            return [];
        }

        return array_is_list($value) ? $value : [$value];
    }
}
