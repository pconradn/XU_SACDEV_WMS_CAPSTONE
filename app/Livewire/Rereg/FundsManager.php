<?php

namespace App\Livewire\Rereg;

use App\Models\StrategicPlanSubmission;
use Livewire\Component;

class FundsManager extends Component
{
    public $submissionId;
    public $canEdit = false;
    public $confirmingSave = false;

    public $status = 'draft';
    public $editing = false;

    public $fixedFundTypes = [];
    public $fixedFundAmounts = [];

    public $otherSources = [];

    public $projectTotal = 0;
    
    public $submission;

    public function confirmSave()
    {
        if (! $this->canEdit || (! $this->editing && $this->status !== 'draft')) {
            return;
        }

        $this->confirmingSave = true;
    }

    public function saveConfirmed()
    {
        $this->confirmingSave = false;

        $this->validate([
            'fixedFundAmounts.*' => ['nullable', 'numeric', 'min:0'],
            'otherSources.*.label' => ['nullable', 'string', 'max:255'],
            'otherSources.*.amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $submission = \App\Models\StrategicPlanSubmission::with('fundSources')
            ->find($this->submissionId);

        if (! $submission) {
            session()->flash('error', 'Please save your Strategic Plan first before adding funds.');
            return;
        }

        $submission->fundSources()->delete();

        foreach ($this->fixedFundAmounts as $type => $amount) {
            if ($this->numeric($amount) > 0) {
                $submission->fundSources()->create([
                    'type' => $type,
                    'label' => null,
                    'amount' => $this->numeric($amount),
                ]);
            }
        }

        foreach ($this->otherSources as $source) {
            $label = trim((string) ($source['label'] ?? ''));
            $amount = $this->numeric($source['amount'] ?? 0);

            if ($label !== '' || $amount > 0) {
                $submission->fundSources()->create([
                    'type' => 'other',
                    'label' => $label,
                    'amount' => $amount,
                ]);
            }
        }

        $this->confirmingSave = false;

        return $this->redirectToStep(3, 'success', 'Funds saved.');
    }




    public function render()
    {
        return view('livewire.rereg.funds-manager');
    }


    public function mount($submissionId, $canEdit = false)
    {
        $this->submissionId = $submissionId;
        $this->canEdit = $canEdit;
        $this->submission = StrategicPlanSubmission::find($submissionId);

        $submission = \App\Models\StrategicPlanSubmission::with(['fundSources', 'projects'])
            ->findOrFail($submissionId);
       
        $this->status = $submission->status ?? 'draft';
        $this->editing = $this->status === 'draft';

        $this->fixedFundTypes = [
            ['type' => 'org_funds', 'label' => 'Student Org Funds'],
            ['type' => 'aeco', 'label' => 'AECO Fund (Finance Office)'],
            ['type' => 'pta', 'label' => 'PTA'],
            ['type' => 'membership_fee', 'label' => 'Membership Fee'],
            ['type' => 'raised_funds', 'label' => 'Raised Funds'],
        ];

        $this->fixedFundAmounts = [
            'org_funds' => '',
            'aeco' => '',
            'pta' => '',
            'membership_fee' => '',
            'raised_funds' => '',
        ];

        foreach ($submission->fundSources as $fs) {
            if (array_key_exists($fs->type, $this->fixedFundAmounts)) {
                $this->fixedFundAmounts[$fs->type] = (string) $fs->amount;
            } else {
                $this->otherSources[] = [
                    'label' => $fs->label ?? '',
                    'amount' => (string) $fs->amount,
                ];
            }
        }

        $this->projectTotal = $submission->projects->sum('budget');
    }

    public function getTotalFundsProperty()
    {
        $total = 0;

        foreach ($this->fixedFundAmounts as $value) {
            $total += $this->numeric($value);
        }

        foreach ($this->otherSources as $source) {
            $total += $this->numeric($source['amount'] ?? 0);
        }

        return $total;
    }

    public function getDifferenceProperty()
    {
        return $this->totalFunds - $this->projectTotal;
    }

    private function numeric($value)
    {
        $clean = str_replace(',', '', (string) $value);
        return is_numeric($clean) ? (float) $clean : 0;
    }    

    public function addOtherSource()
    {
        $this->otherSources[] = [
            'label' => '',
            'amount' => '',
        ];
    }

    public function removeOtherSource($index)
    {
        unset($this->otherSources[$index]);
        $this->otherSources = array_values($this->otherSources);
    }

    public function updatedFixedFundAmounts($value, $key)
    {
        $this->fixedFundAmounts[$key] = $this->normalize($value);
    }

    public function updatedOtherSources($value, $key)
    {
        $parts = explode('.', $key);

        if (count($parts) === 2 && $parts[1] === 'amount') {
            $index = (int) $parts[0];

            if (isset($this->otherSources[$index])) {
                $this->otherSources[$index]['amount'] = $this->normalize($value);
            }
        }
    }

    private function normalize($value)
    {
        return str_replace(',', '', (string) $value);
    }

    public function save()
    {
        if (! $this->canEdit || (! $this->editing && $this->status !== 'draft')) {
            return;
        }

        $this->validate([
            'fixedFundAmounts.*' => ['nullable', 'numeric', 'min:0'],
            'otherSources.*.label' => ['nullable', 'string', 'max:255'],
            'otherSources.*.amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $submission = \App\Models\StrategicPlanSubmission::with('fundSources')
            ->find($this->submissionId);

        if (! $submission) {
            session()->flash('error', 'Please save your Strategic Plan first before adding funds.');
            return;
        }

        $submission->fundSources()->delete();

        foreach ($this->fixedFundAmounts as $type => $amount) {
            if ($this->numeric($amount) > 0) {
                $submission->fundSources()->create([
                    'type' => $type,
                    'label' => null,
                    'amount' => $this->numeric($amount),
                ]);
            }
        }

        foreach ($this->otherSources as $source) {
            $label = trim((string) ($source['label'] ?? ''));
            $amount = $this->numeric($source['amount'] ?? 0);

            if ($label !== '' || $amount > 0) {
                $submission->fundSources()->create([
                    'type' => 'other',
                    'label' => $label,
                    'amount' => $amount,
                ]);
            }
        }

        $this->confirmingSave = false;

        return $this->redirectToStep(3, 'success', 'Funds saved.');
    }

    private function redirectToStep(int $step, string $type, string $message)
    {
        $previousUrl = request()->header('Referer') ?: route('org.rereg.b1.edit');

        $previousUrl = strtok($previousUrl, '#');

        return redirect()
            ->to($previousUrl . '#strategic-plan-stepper')
            ->with($type, $message)
            ->with('strategic_plan_step', $step);
    }

}