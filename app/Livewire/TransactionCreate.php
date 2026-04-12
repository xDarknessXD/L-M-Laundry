<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\InventoryItem;

#[Layout('layouts.app')]
class TransactionCreate extends Component
{
    // Customer
    public string $customer_name = '';
    public string $customer_phone = '';

    // Service
    public $service_id = '';
    public string $material_type = 'light';
    public $kilos = 5;

    // Addons
    public array $addons = [];
    public bool $showAddonModal = false;
    public $selectedAddonId = '';
    public $addonQty = 1;

    // Computed values
    public $subtotal = 0;
    public $addonsTotal = 0;
    public $totalAmount = 0;

    // Payment
    public $payment_type = 'unpaid';
    public $amount_paid = 0;

    protected function rules()
    {
        return [
            'customer_name' => 'required|min:2',
            'service_id' => 'required|exists:services,id',
        ];
    }

    public function updatedServiceId()
    {
        $this->calculateTotals();
    }

    public function updatedKilos()
    {
        $this->calculateTotals();
    }

    public function updatedMaterialType()
    {
        $this->calculateTotals();
    }

    public function updatedPaymentType()
    {
        if ($this->payment_type === 'full') {
            $this->amount_paid = $this->totalAmount;
        } elseif ($this->payment_type === 'unpaid') {
            $this->amount_paid = 0;
        } else {
            // Provide a default 50% partial payment suggestion
            $this->amount_paid = max(0, ceil($this->totalAmount * 0.5));
        }
    }

    public function calculateTotals()
    {
        $service = Service::find($this->service_id);
        if (!$service) {
            $this->subtotal = 0;
            $this->totalAmount = 0;
            return;
        }

        $effectiveKilos = max((float)$this->kilos, $service->minimum_kilos);
        $this->subtotal = $effectiveKilos * $service->price_per_kilo;

        $this->addonsTotal = collect($this->addons)->sum(function ($a) {
            return ($a['price'] ?? 0) * ($a['quantity'] ?? 1);
        });

        $this->totalAmount = $this->subtotal + $this->addonsTotal;
        
        // Re-sync automatic payment amounts when invoice changes
        if ($this->payment_type === 'full') {
            $this->amount_paid = $this->totalAmount;
        } elseif ($this->payment_type === 'unpaid') {
            $this->amount_paid = 0;
        }
    }

    public function addAddon()
    {
        if (!$this->selectedAddonId) return;

        $item = InventoryItem::find($this->selectedAddonId);
        if (!$item) return;

        // Check stock availability
        $currentCartQuantity = 0;
        foreach ($this->addons as $addon) {
            if ($addon['item_id'] == $item->id) {
                $currentCartQuantity = $addon['quantity'];
                break;
            }
        }

        if (($currentCartQuantity + $this->addonQty) > $item->stock_quantity) {
            $this->addError('addonQty', "Only {$item->stock_quantity} left in stock.");
            return;
        }

        // Check if already added
        foreach ($this->addons as $key => $addon) {
            if ($addon['item_id'] == $item->id) {
                $this->addons[$key]['quantity'] += $this->addonQty;
                $this->calculateTotals();
                $this->showAddonModal = false;
                $this->selectedAddonId = '';
                $this->addonQty = 1;
                return;
            }
        }

        $this->addons[] = [
            'item_id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'quantity' => $this->addonQty,
        ];

        $this->calculateTotals();
        $this->showAddonModal = false;
        $this->selectedAddonId = '';
        $this->addonQty = 1;
    }

    public function removeAddon($index)
    {
        unset($this->addons[$index]);
        $this->addons = array_values($this->addons);
        $this->calculateTotals();
    }

    public function submit()
    {
        $this->validate();

        if ((float)$this->kilos < 1) {
            $this->addError('kilos', 'Weight must be at least 1kg.');
            return;
        }

        $service = Service::findOrFail($this->service_id);
        $effectiveKilos = max((float)$this->kilos, $service->minimum_kilos);
        $minutesPerKilo = $this->material_type === 'heavy' ? 10 : ($this->material_type === 'jeans' ? 8 : 6);

        // Calculate and validate Payment
        $minimumPartial = ceil($this->totalAmount * 0.5);
        $amountPaid = (float) $this->amount_paid;

        if ($this->payment_type === 'partial') {
            if ($amountPaid < $minimumPartial) {
                $this->addError('amount_paid', "Minimum down payment is 50% (₱" . number_format($minimumPartial, 2) . ").");
                return;
            }
            if ($amountPaid > $this->totalAmount) {
                $this->addError('amount_paid', "Cannot pay more than the total balance here.");
                return;
            }
        } elseif ($this->payment_type === 'full') {
            $amountPaid = $this->totalAmount;
        } else {
            $amountPaid = 0;
        }

        $balance = $this->totalAmount - $amountPaid;
        $paymentStatus = 'unpaid';
        if ($balance <= 0) {
            $paymentStatus = 'paid';
        } elseif ($amountPaid > 0) {
            $paymentStatus = 'partial';
        }

        $transaction = Transaction::create([
            'order_number' => Transaction::generateOrderNumber(),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'service_id' => $this->service_id,
            'material_type' => $this->material_type,
            'kilos' => $effectiveKilos,
            'minutes_per_kilo' => $minutesPerKilo,
            'subtotal' => $this->subtotal,
            'addons_total' => $this->addonsTotal,
            'total_amount' => $this->totalAmount,
            'amount_paid' => $amountPaid,
            'balance' => $balance,
            'payment_status' => $paymentStatus,
            'order_status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        // Save addons and deduct inventory
        foreach ($this->addons as $addon) {
            $transaction->addons()->create([
                'inventory_item_id' => $addon['item_id'],
                'quantity' => $addon['quantity'],
                'price' => $addon['price'],
            ]);

            // Deduct stock
            $item = InventoryItem::find($addon['item_id']);
            if ($item) {
                $item->stock_quantity -= $addon['quantity'];
                
                // Set status to out_of_stock if empty
                if ($item->stock_quantity <= 0) {
                    $item->stock_quantity = 0;
                    $item->status = 'out_of_stock';
                }
                $item->save();
            }
        }

        $this->dispatch('toast', message: "Transaction {$transaction->order_number} created!", type: 'success');
        return redirect()->route('transactions');
    }

    public function render()
    {
        $services = Service::where('is_active', true)->get();
        $selectedService = Service::find($this->service_id);
        $inventoryItems = InventoryItem::where('status', '!=', 'out_of_stock')
            ->where('stock_quantity', '>', 0)
            ->get();

        return view('livewire.transaction-create', compact('services', 'selectedService', 'inventoryItems'))
            ->layout('layouts.app');
    }
}
