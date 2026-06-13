<?php
namespace App\Mappers\SatuSehat;
class ObservationMapper
{
    public function map(array $data): array
    {
        return [
            'resourceType' => 'Observation',
            'status' => $data['status'] ?? 'final',
            'category' => [['coding' => [['system'=>'http://terminology.hl7.org/CodeSystem/observation-category','code'=>$data['category_code'] ?? 'vital-signs','display'=>$data['category_display'] ?? 'Vital Signs']]]],
            'code' => ['coding' => [['system'=>$data['code_system'] ?? 'http://loinc.org','code'=>$this->required($data,'loinc_code'),'display'=>$data['display'] ?? $data['loinc_code']]]],
            'subject' => ['reference'=>'Patient/'.$this->required($data,'patient_ihs')],
            'encounter' => ['reference'=>'Encounter/'.$this->required($data,'encounter_id')],
            'effectiveDateTime' => date('c', strtotime($data['effective_date'] ?? now())),
            'valueQuantity' => ['value'=>(float) $this->required($data,'value'), 'unit'=>$data['unit'] ?? '', 'system'=>'http://unitsofmeasure.org', 'code'=>$data['unit_code'] ?? $data['unit'] ?? ''],
        ];
    }
    private function required(array $data, string $key): string { if (!isset($data[$key]) || $data[$key] === '') throw new \InvalidArgumentException($key.' wajib diisi'); return (string) $data[$key]; }
}
