<?php
namespace App\Mappers\Bpjs;
class AntrolMapper
{
    public function map(array $data): array
    {
        return [
            'kodebooking' => $this->required($data,'kodebooking'),
            'jenispasien' => $data['jenispasien'] ?? 'JKN',
            'nomorkartu' => $data['nomorkartu'] ?? '',
            'nik' => $data['nik'] ?? '',
            'nohp' => $this->required($data,'nohp'),
            'kodepoli' => $this->required($data,'kodepoli'),
            'namapoli' => $this->required($data,'namapoli'),
            'pasienbaru' => (int) ($data['pasienbaru'] ?? 0),
            'norm' => $this->required($data,'norm'),
            'tanggalperiksa' => $this->required($data,'tanggalperiksa'),
            'kodedokter' => (int) $this->required($data,'kodedokter'),
            'namadokter' => $this->required($data,'namadokter'),
            'jampraktek' => $this->required($data,'jampraktek'),
            'jeniskunjungan' => (int) ($data['jeniskunjungan'] ?? 1),
            'nomorreferensi' => $data['nomorreferensi'] ?? '',
            'nomorantrean' => $this->required($data,'nomorantrean'),
            'angkaantrean' => (int) $this->required($data,'angkaantrean'),
            'estimasidilayani' => (int) $this->required($data,'estimasidilayani'),
            'sisakuotajkn' => (int) ($data['sisakuotajkn'] ?? 0),
            'kuotajkn' => (int) ($data['kuotajkn'] ?? 0),
            'sisakuotanonjkn' => (int) ($data['sisakuotanonjkn'] ?? 0),
            'kuotanonjkn' => (int) ($data['kuotanonjkn'] ?? 0),
            'keterangan' => $data['keterangan'] ?? 'Peserta harap datang 30 menit lebih awal.',
        ];
    }
    private function required(array $data, string $key): string { if (!isset($data[$key]) || $data[$key] === '') throw new \InvalidArgumentException($key.' wajib diisi'); return (string) $data[$key]; }
}
