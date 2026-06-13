<?php
namespace App\Mappers\SatuSehat;
class EncounterMapper
{
    public function map(array $data): array
    {
        $org = $data['organization_id'] ?? config('satusehat.organization_id');
        return [
            'resourceType' => 'Encounter',
            'status' => $data['status'] ?? 'finished',
            'class' => ['system'=>'http://terminology.hl7.org/CodeSystem/v3-ActCode','code'=>$data['class_code'] ?? 'AMB','display'=>$data['class_display'] ?? 'ambulatory'],
            'subject' => ['reference' => 'Patient/'.$this->required($data, 'patient_ihs')],
            'participant' => [[
                'type' => [['coding' => [['system'=>'http://terminology.hl7.org/CodeSystem/v3-ParticipationType','code'=>'ATND','display'=>'attender']]]],
                'individual' => ['reference' => 'Practitioner/'.$this->required($data, 'practitioner_ihs')],
            ]],
            'period' => ['start' => $this->iso($this->required($data, 'period_start')), 'end' => $this->iso($data['period_end'] ?? $data['period_start'])],
            'serviceProvider' => ['reference' => 'Organization/'.$this->required(['organization_id'=>$org], 'organization_id')],
            'identifier' => [['system' => $data['identifier_system'] ?? 'http://sys-ids.kemkes.go.id/encounter/'.$org, 'value' => $this->required($data, 'no_rawat')]],
        ];
    }
    private function required(array $data, string $key): string { if (empty($data[$key])) throw new \InvalidArgumentException($key.' wajib diisi'); return (string) $data[$key]; }
    private function iso(string $date): string { return date('c', strtotime($date)); }
}
