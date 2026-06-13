<?php
namespace App\Mappers\SatuSehat;
class ConditionMapper
{
    public function map(array $data): array
    {
        return [
            'resourceType' => 'Condition',
            'clinicalStatus' => ['coding' => [['system'=>'http://terminology.hl7.org/CodeSystem/condition-clinical','code'=>'active','display'=>'Active']]],
            'category' => [['coding' => [['system'=>'http://terminology.hl7.org/CodeSystem/condition-category','code'=>'encounter-diagnosis','display'=>'Encounter Diagnosis']]]],
            'code' => ['coding' => [['system'=>'http://hl7.org/fhir/sid/icd-10','code'=>$this->required($data,'icd10_code'),'display'=>$data['diagnosis_name'] ?? $data['icd10_code']]]],
            'subject' => ['reference'=>'Patient/'.$this->required($data,'patient_ihs')],
            'encounter' => ['reference'=>'Encounter/'.$this->required($data,'encounter_id')],
            'recordedDate' => date('c', strtotime($data['recorded_date'] ?? now())),
        ];
    }
    private function required(array $data, string $key): string { if (empty($data[$key])) throw new \InvalidArgumentException($key.' wajib diisi'); return (string) $data[$key]; }
}
