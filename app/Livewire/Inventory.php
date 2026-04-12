<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;

class Inventory extends Component
{
    public string $search = '';

    // Add/Edit Item
    public bool $showItemModal = false;
    public bool $editingItem = false;
    public ?int $editItemId = null;
    public $categoryId = '';
    public string $itemName = '';
    public string $itemBrand = '';
    public string $itemUnit = 'pcs';
    public float $itemPrice = 0;
    public int $itemStock = 0;

    // Add Category
    public bool $showCategoryModal = false;
    public string $newCategoryName = '';
    public string $newCategoryIcon = '📦';

    public function openAddItem($categoryId = null)
    {
        $this->resetItemForm();
        $this->categoryId = $categoryId ?? '';
        $this->showItemModal = true;
    }

    public function openEditItem($itemId)
    {
        $item = InventoryItem::find($itemId);
        if (!$item) return;

        $this->editingItem = true;
        $this->editItemId = $item->id;
        $this->categoryId = $item->inventory_category_id;
        $this->itemName = $item->name;
        $this->itemBrand = $item->brand ?? '';
        $this->itemUnit = $item->unit;
        $this->itemPrice = $item->price;
        $this->itemStock = $item->stock_quantity;
        $this->showItemModal = true;
    }

    public function saveItem()
    {
        $this->validate([
            'categoryId' => 'required|exists:inventory_categories,id',
            'itemName' => 'required|min:2',
            'itemUnit' => 'required',
            'itemPrice' => 'required|numeric|min:0',
            'itemStock' => 'required|integer|min:0',
        ]);

        $status = $this->itemStock <= 0 ? 'out_of_stock' : ($this->itemStock <= 10 ? 'low_stock' : 'available');

        $data = [
            'inventory_category_id' => $this->categoryId,
            'name' => $this->itemName,
            'brand' => $this->itemBrand ?: null,
            'unit' => $this->itemUnit,
            'price' => $this->itemPrice,
            'stock_quantity' => $this->itemStock,
            'status' => $status,
        ];

        if ($this->editingItem && $this->editItemId) {
            InventoryItem::where('id', $this->editItemId)->update($data);
            $msg = 'Item updated successfully!';
        } else {
            InventoryItem::create($data);
            $msg = 'Item added successfully!';
        }

        $this->showItemModal = false;
        $this->resetItemForm();
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function deleteItem($itemId)
    {
        InventoryItem::destroy($itemId);
        $this->dispatch('toast', message: 'Item deleted.', type: 'success');
    }

    public function saveCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:2',
        ]);

        InventoryCategory::create([
            'name' => $this->newCategoryName,
            'icon' => $this->newCategoryIcon,
        ]);

        $this->showCategoryModal = false;
        $this->newCategoryName = '';
        $this->newCategoryIcon = '📦';
        $this->dispatch('toast', message: 'Category created!', type: 'success');
    }

    private function resetItemForm()
    {
        $this->editingItem = false;
        $this->editItemId = null;
        $this->categoryId = '';
        $this->itemName = '';
        $this->itemBrand = '';
        $this->itemUnit = 'pcs';
        $this->itemPrice = 0;
        $this->itemStock = 0;
    }

    public function render()
    {
        $categories = InventoryCategory::with(['items' => function ($q) {
            if ($this->search) {
                $q->where('name', 'like', "%{$this->search}%");
            }
        }])->get();

        $isAdmin = auth()->user()->isAdmin();

        return view('livewire.inventory', compact('categories', 'isAdmin'));
    }
}
