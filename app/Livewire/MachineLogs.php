<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MachineLog;
use App\Models\Machine;

class MachineLogs extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCycleType = '';
    public string $filterStatus = '';
    public string $filterMachine = '';

    // New log form
    public bool $showLogModal = false;
    public $machineId = '';
    public string $cycleType = 'wash';
    public float $loadKilos = 0;
    public int $durationMinutes = 30;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCycleType() { $this->resetPage(); }
    public function updatedFilterStatus() { $this->resetPage(); }
    public function updatedFilterMachine() { $this->resetPage(); }

    public function startLog()
    {
        $this->validate([
            'machineId' => 'required|exists:machines,id',
            'cycleType' => 'required|in:wash,dry',
            'loadKilos' => 'required|numeric|min:0.1',
            'durationMinutes' => 'required|integer|min:1',
        ]);

        MachineLog::create([
            'machine_id' => $this->machineId,
            'cycle_type' => $this->cycleType,
            'load_kilos' => $this->loadKilos,
            'duration_minutes' => $this->durationMinutes,
            'start_time' => now(),
            'end_time' => now()->addMinutes($this->durationMinutes),
            'staff_id' => auth()->id(),
            'status' => 'in_progress',
        ]);

        $this->showLogModal = false;
        $this->reset(['machineId', 'cycleType', 'loadKilos', 'durationMinutes']);
        $this->cycleType = 'wash';
        $this->durationMinutes = 30;
        $this->dispatch('toast', message: 'Machine cycle started!', type: 'success');
    }

    public function completeLog($logId)
    {
        $log = MachineLog::find($logId);
        if ($log) {
            $log->update([
                'status' => 'completed',
                'end_time' => now(),
            ]);
            $this->dispatch('toast', message: 'Cycle marked as completed.', type: 'success');
        }
    }

    public function render()
    {
        $logs = MachineLog::with(['machine', 'staff'])
            ->when($this->search, fn($q) => $q->whereHas('machine', fn($mq) => $mq->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterCycleType, fn($q) => $q->where('cycle_type', $this->filterCycleType))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMachine, fn($q) => $q->where('machine_id', $this->filterMachine))
            ->latest()
            ->paginate(15);

        $machines = Machine::where('is_active', true)->get();
        $isAdmin = auth()->user()->isAdmin();

        return view('livewire.machine-logs', compact('logs', 'machines', 'isAdmin'));
    }
}
