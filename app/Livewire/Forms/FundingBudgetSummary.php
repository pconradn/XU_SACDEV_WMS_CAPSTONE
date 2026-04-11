<?php

namespace App\Livewire\Forms;

use Livewire\Component;

class FundingBudgetSummary extends Component
{
    public $pta = 0;

    public $counterpart_amount = 0;
    public $counterpart_pax = 0;

    public $fund_sources = [
        'Finance Office' => 0,
        'PTA' => 0,
        'OSA-SACDEV' => 0,
        'Counterpart' => 0,
        'Solicitation' => 0,
        'Ticket-Selling' => 0,
        'Others' => 0,
    ];

    public function mount($budget = null, $proposalData = null)
    {
        if ($budget) {
            $this->pta = $budget->pta_amount ?? 0;
            $this->counterpart_amount = $budget->counterpart_amount_per_pax ?? 0;
            $this->counterpart_pax = $budget->counterpart_pax ?? 0;
        }

        if ($proposalData && $proposalData->fundSources) {
            foreach ($proposalData->fundSources as $fs) {
                if (array_key_exists($fs->source_name, $this->fund_sources)) {
                    $this->fund_sources[$fs->source_name] = $fs->amount;
                }
            }
        }
        $this->dispatch('budgetUpdated', total: $this->totalBudget);

        $this->fund_sources['Counterpart'] = $this->counterpartTotal;
    }

    public function updated($field)
    {
        if (in_array($field, ['counterpart_amount', 'counterpart_pax'])) {
            $this->fund_sources['Counterpart'] = $this->counterpartTotal;
        }

        $this->dispatch('budgetUpdated', total: $this->totalBudget);
    }

    public function getCounterpartTotalProperty()
    {
        return $this->numeric($this->counterpart_amount) * (int)$this->counterpart_pax;
    }

    public function getRaisedTotalProperty()
    {
        return
            $this->numeric($this->fund_sources['Solicitation']) +
            $this->numeric($this->fund_sources['Ticket-Selling']) +
            $this->numeric($this->fund_sources['Others']);
    }

    public function getTotalBudgetProperty()
    {
        return
            $this->numeric($this->pta) +
            $this->counterpartTotal +
            $this->raisedTotal;
    }

    private function numeric($value)
    {
        $clean = str_replace(',', '', (string)$value);
        return is_numeric($clean) ? (float)$clean : 0;
    }

    public function render()
    {
        $this->dispatch('budgetUpdated', total: $this->totalBudget);

        return view('livewire.forms.funding-budget-summary');
    }
}