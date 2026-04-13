<?php

namespace App\Livewire;

use App\Models\Machine;
use App\Models\MachineLog;
use Livewire\Component;
use Livewire\WithPagination;

class MachineLogs extends Component
{
    use WithPagination;

    protected $poll = 5;

    public string $search = '';

    public string $filterCycleType = '';

    public string $filterStatus = '';

    public string $filterMachine = '';

    // Tabs
    public string $activeTab = 'logs';

    // New log form
    public bool $showLogModal = false;

    public $machineId = '';

    public string $cycleType = 'wash';

    public float $loadKilos = 0;

    public int $durationMinutes = 30;

    // Machine form
    public bool $showMachineModal = false;

    public bool $editingMachine = false;

    public ?int $editMachineId = null;

    public string $machineCode = '';

    public string $machineName = '';

    public string $machineType = 'washer';

    // Machine Management
    public function openAddMachine()
    {
        $this->editingMachine = false;
        $this->editMachineId = null;
        $this->resetMachineForm();
        $this->showMachineModal = true;
    }

    public function openEditMachine($id)
    {
        $machine = Machine::find($id);
        $this->editingMachine = true;
        $this->editMachineId = $id;
        $this->machineCode = $machine->machine_code;
        $this->machineName = $machine->name;
        $this->machineType = $machine->type;
        $this->showMachineModal = true;
    }

    public function saveMachine()
    {
        $this->validate([
            'machineCode' => 'required|string|max:20|unique:machines,machine_code' . ($this->editingMachine ? ",{$this->editMachineId}" : ''),
            'machineName' => 'required|string|max:100',
            'machineType' => 'required|in:washer,dryer',
        ]);

        Machine::updateOrCreate(
            ['id' => $this->editMachineId],
            [
                'machine_code' => $this->machineCode,
                'name' => $this->machineName,
                'type' => $this->machineType,
                'is_active' => true,
                'is_available' => true,
            ]
        );

        $this->showMachineModal = false;
        $this->resetMachineForm();
        $this->dispatch('toast', message: $this->editingMachine ? 'Machine updated!' : 'Machine added!', type: 'success');
    }

    public function toggleMachine($id)
    {
        $machine = Machine::find($id);
        $machine->update(['is_active' => !$machine->is_active]);
        $this->dispatch('toast', message: $machine->is_active ? 'Machine activated' : 'Machine deactivated', type: 'success');
    }

    public function deleteMachine($id)
    {
        Machine::destroy($id);
        $this->dispatch('toast', message: 'Machine deleted!', type: 'success');
    }

    public function resetMachineForm()
    {
        $this->machineCode = '';
        $this->machineName = '';
        $this->machineType = 'washer';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCycleType()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterMachine()
    {
        $this->resetPage();
    }

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

        Machine::where('id', $this->machineId)->update(['is_available' => false]);

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
            $log->machine->update(['is_available' => true]);
            $this->dispatch('toast', message: 'Cycle marked as completed.', type: 'success');
        }
    }

    protected function checkAndCompleteExpiredLogs()
    {
        $expiredLogs = MachineLog::where('status', 'in_progress')
            ->where('end_time', '<=', now())
            ->with('machine')
            ->get();

        foreach ($expiredLogs as $log) {
            $log->update([
                'status' => 'completed',
                'end_time' => now(),
            ]);
            if ($log->machine) {
                $log->machine->update(['is_available' => true]);
            }
        }
    }

    public function render()
    {
        $this->checkAndCompleteExpiredLogs();

        $logs = MachineLog::with(['machine', 'staff'])
            ->when($this->search, fn ($q) => $q->whereHas('machine', fn ($mq) => $mq->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterCycleType, fn ($q) => $q->where('cycle_type', $this->filterCycleType))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMachine, fn ($q) => $q->where('machine_id', $this->filterMachine))
            ->latest()
            ->paginate(15);

        $machines = Machine::where('is_active', true)->get();
        $allMachines = Machine::orderBy('created_at', 'desc')->get();
        $isAdmin = auth()->user()->isAdmin();

        return view('livewire.machine-logs', compact('logs', 'machines', 'allMachines', 'isAdmin'));
    }
}
