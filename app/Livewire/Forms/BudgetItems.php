<?php

namespace App\Livewire\Forms;

use Livewire\Component;

class BudgetItems extends Component
{
    public $sections = [
        'fund_transfer'      => 'A. For Fund Transfer / Direct Payment to Supplier',
        'xucmpc'             => 'B. For XUCMPC',
        'bookstore'          => 'C. For Bookstore',
        'central_purchasing' => 'D. For Central Purchasing Unit',
        'cash_advance'       => 'E. For Cash Advance (Finance Office)',
    ];

    public $items = [];

    public $expectedBudget = 0;

    protected $listeners = ['budgetUpdated'];

    public function mount($budget = null)
    {
        foreach ($this->sections as $code => $label) {
            $this->items[$code] = [];
        }

        if ($budget && $budget->items) {
            foreach ($budget->items as $item) {
                $this->items[$item->section][] = [
                    'qty' => $item->qty,
                    'unit' => $item->unit,
                    'particulars' => $item->particulars,
                    'price' => number_format($item->price_per_unit, 2),
                ];
            }
        }
    }

    public function budgetUpdated($total)
    {
        $this->expectedBudget = $total;
    }

    public function addRow($section)
    {
        $this->items[$section][] = [
            'qty' => 1,
            'unit' => '',
            'particulars' => '',
            'price' => '0.00',
        ];
    }

    public function removeRow($section, $index)
    {
        unset($this->items[$section][$index]);
        $this->items[$section] = array_values($this->items[$section]);
    }

    public function getSectionTotal($section)
    {
        $total = 0;

        foreach ($this->items[$section] as $row) {
            $qty = (int)($row['qty'] ?? 0);
            $price = $this->numeric($row['price'] ?? 0);
            $total += $qty * $price;
        }

        return $total;
    }

    public function getGrandTotalProperty()
    {
        $total = 0;

        foreach ($this->sections as $code => $label) {
            $total += $this->getSectionTotal($code);
        }

        return $total;
    }

    public function getHasItemsProperty()
    {
        foreach ($this->items as $section) {
            if (count($section)) return true;
        }
        return false;
    }

    private function numeric($value)
    {
        $clean = str_replace(',', '', (string)$value);
        return is_numeric($clean) ? (float)$clean : 0;
    }

    public function render()
    {
        return view('livewire.forms.budget-items');
    }

    public function getIsMatchProperty()
    {
        return round($this->grandTotal, 2) === round($this->expectedBudget, 2);
    }


}