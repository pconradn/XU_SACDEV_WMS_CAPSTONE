<?php

namespace App\Livewire\Rereg;

use Livewire\Component;
use App\Models\StrategicPlanProject;

class ProjectsModal extends Component
{
    public $show = false;
    public $viewMode = false;
    public $isEdit = false;

    public $submissionId;
    public $canEdit = false;
    public $isApproved = false;

    public $projectId = null;
    public $category = '';
    public $target_date = '';
    public $title = '';
    public $budget = '';
    public $implementing_body = '';

    public $objectives = [];
    public $beneficiaries = [];
    public $deliverables = [];
    public $partners = [];

    public $formAction = '';
    public $formMethod = 'POST';

    protected $listeners = [
        'openCreate' => 'openCreate',
        'openEdit' => 'openEdit',
        'openView' => 'openView',
    ];

    public function mount(int $submissionId, bool $canEdit = false, bool $isApproved = false): void
    {
        $this->submissionId = $submissionId;
        $this->canEdit = $canEdit;
        $this->isApproved = $isApproved;
        
    }

    public function resetForm(): void
    {
        $this->projectId = null;
        $this->category = '';
        $this->target_date = '';
        $this->title = '';
        $this->budget = '';
        $this->implementing_body = '';
        $this->objectives = [];
        $this->beneficiaries = [];
        $this->deliverables = [];
        $this->partners = [];
        $this->formAction = '';
        $this->formMethod = 'POST';
    }

    public function openCreate($category = null): void
    {
        if (!$this->canEdit || $this->isApproved) {
            return;
        }

        $this->resetForm();

        $this->category = is_array($category)
            ? ($category['category'] ?? '')
            : $category;

        $this->viewMode = false;
        $this->isEdit = false;
        $this->formAction = route('org.rereg.b1.projects.store');
        $this->formMethod = 'POST';
        $this->show = true;
    }

    public function openEdit($id = null): void
    {
        if (!$this->canEdit || $this->isApproved) {
            return;
        }

        if (is_array($id)) {
            $id = $id['id'] ?? null;
        }

        $project = StrategicPlanProject::with([
            'objectives',
            'beneficiaries',
            'deliverables',
            'partners',
        ])->findOrFail($id);

        $this->projectId = $project->id;
        $this->category = $project->category;
        $this->target_date = $project->target_date
            ? \Carbon\Carbon::parse($project->target_date)->format('Y-m-d')
            : '';
        $this->title = $project->title;
        $this->budget = $project->budget
    ? number_format((float)$project->budget, 0, '.', ',')
    : '';
        $this->implementing_body = $project->implementing_body;

        $this->objectives = $project->objectives->pluck('text')->toArray();
        $this->beneficiaries = $project->beneficiaries->pluck('text')->toArray();
        $this->deliverables = $project->deliverables->pluck('text')->toArray();
        $this->partners = $project->partners->pluck('text')->toArray();

        $this->viewMode = false;
        $this->isEdit = true;
        $this->formAction = route('org.rereg.b1.projects.update', $project->id);
        $this->formMethod = 'PUT';
        $this->show = true;
    }

    public function openView($id = null): void
    {
        if (is_array($id)) {
            $id = $id['id'] ?? null;
        }

        $project = StrategicPlanProject::with([
            'objectives',
            'beneficiaries',
            'deliverables',
            'partners',
        ])->findOrFail($id);

        $this->projectId = $project->id;
        $this->category = $project->category;
        $this->target_date = $project->target_date
            ? \Carbon\Carbon::parse($project->target_date)->format('Y-m-d')
            : '';
        $this->title = $project->title;
        $this->budget = $project->budget
    ? number_format((float)$project->budget, 0, '.', ',')
    : '';
        $this->implementing_body = $project->implementing_body;

        $this->objectives = $project->objectives->pluck('text')->toArray();
        $this->beneficiaries = $project->beneficiaries->pluck('text')->toArray();
        $this->deliverables = $project->deliverables->pluck('text')->toArray();
        $this->partners = $project->partners->pluck('text')->toArray();

        $this->viewMode = true;
        $this->isEdit = false;
        $this->formAction = '';
        $this->formMethod = 'POST';
        $this->show = true;
    }

    public function addObjective(): void
    {
        $this->objectives[] = '';
    }

    public function removeObjective($i): void
    {
        unset($this->objectives[$i]);
        $this->objectives = array_values($this->objectives);
    }

    public function addBeneficiary(): void
    {
        $this->beneficiaries[] = '';
    }

    public function removeBeneficiary($i): void
    {
        unset($this->beneficiaries[$i]);
        $this->beneficiaries = array_values($this->beneficiaries);
    }

    public function addDeliverable(): void
    {
        $this->deliverables[] = '';
    }

    public function removeDeliverable($i): void
    {
        unset($this->deliverables[$i]);
        $this->deliverables = array_values($this->deliverables);
    }

    public function addPartner(): void
    {
        $this->partners[] = '';
    }

    public function removePartner($i): void
    {
        unset($this->partners[$i]);
        $this->partners = array_values($this->partners);
    }

    public function render()
    {
        return view('livewire.rereg.projects-modal');
    }


}