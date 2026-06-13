<?php
namespace App\Mappers\Bpjs;
class SepMapper
{
    public function map(array $data): array
    {
        return ['request' => ['t_sep' => [
            'noKartu' => $this->required($data,'no_kartu'),
            'tglSep' => $data['tgl_sep'] ?? date('Y-m-d'),
            'ppkPelayanan' => $this->required($data,'ppk_pelayanan'),
            'jnsPelayanan' => $this->required($data,'jns_pelayanan'),
            'klsRawat' => ['klsRawatHak'=>$data['kls_rawat_hak'] ?? '3', 'klsRawatNaik'=>$data['kls_rawat_naik'] ?? '', 'pembiayaan'=>$data['pembiayaan'] ?? '', 'penanggungJawab'=>$data['penanggung_jawab'] ?? ''],
            'noMR' => $this->required($data,'no_rm'),
            'rujukan' => ['asalRujukan'=>$data['asal_rujukan'] ?? '2','tglRujukan'=>$data['tgl_rujukan'] ?? date('Y-m-d'),'noRujukan'=>$data['no_rujukan'] ?? '', 'ppkRujukan'=>$data['ppk_rujukan'] ?? ''],
            'catatan' => $data['catatan'] ?? '-',
            'diagAwal' => $this->required($data,'diag_awal'),
            'poli' => ['tujuan'=>$this->required($data,'poli_tujuan'),'eksekutif'=>$data['eksekutif'] ?? '0'],
            'cob' => ['cob'=>$data['cob'] ?? '0'],
            'katarak' => ['katarak'=>$data['katarak'] ?? '0'],
            'jaminan' => ['lakaLantas'=>$data['laka_lantas'] ?? '0','noLP'=>$data['no_lp'] ?? '', 'penjamin'=>['tglKejadian'=>$data['tgl_kejadian'] ?? '', 'keterangan'=>$data['keterangan'] ?? '', 'suplesi'=>['suplesi'=>$data['suplesi'] ?? '0','noSepSuplesi'=>$data['no_sep_suplesi'] ?? '', 'lokasiLaka'=>['kdPropinsi'=>$data['kd_propinsi'] ?? '', 'kdKabupaten'=>$data['kd_kabupaten'] ?? '', 'kdKecamatan'=>$data['kd_kecamatan'] ?? '']]]],
            'tujuanKunj' => $data['tujuan_kunj'] ?? '0',
            'flagProcedure' => $data['flag_procedure'] ?? '',
            'kdPenunjang' => $data['kd_penunjang'] ?? '',
            'assesmentPel' => $data['assesment_pel'] ?? '',
            'skdp' => ['noSurat'=>$data['no_surat'] ?? '', 'kodeDPJP'=>$data['kode_dpjp'] ?? ''],
            'dpjpLayan' => $data['dpjp_layan'] ?? '',
            'noTelp' => $this->required($data,'no_telp'),
            'user' => $data['user'] ?? 'Kania Health Bridge',
        ]]];
    }
    private function required(array $data, string $key): string { if (empty($data[$key])) throw new \InvalidArgumentException($key.' wajib diisi'); return (string) $data[$key]; }
}
