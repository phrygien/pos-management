<?php

use App\Models\Product;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use Toast;
    use WithPagination;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Delete action
    public function delete($id): void
    {
        $this->warning("Will delete #$id", 'It is fake.', position: 'toast-bottom');
    }

    // Table headers
    public function headers(): array
    {
        return [['key' => 'id', 'label' => 'ID', 'class' => 'w-1'], ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'], ['key' => 'brand.name', 'label' => 'Brand', 'class' => 'w-64'], ['key' => 'category.name', 'label' => 'Category', 'class' => 'w-64'], ['key' => 'barcode', 'label' => 'Code bar', 'class' => 'w-20'], ['key' => 'price', 'label' => 'Price', 'class' => 'w-20']];
    }

    //List All Products
    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->with('category', 'brand', 'unit')
            ->when($this->search, fn(Builder $query) => $query->where('name', 'like', "%{$this->search}%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(20);
    }

    public function with(): array
    {
        return [
            'products' => $this->products(),
            'headers' => $this->headers(),
        ];
    }
}; ?>

<div>
    <div class="flex flex-col justify-between mt-6 mb-6 sm:flex-row">
        <div class="relative col-span-3 text-sm text-gray-800">
            <div class="absolute top-0 bottom-0 left-0 flex items-center pl-2 text-gray-500 pointer-events-none">
                <x-search-icon />
            </div>
            <input type="text" autocomplete="off" placeholder="Search product data..." wire:model.live="search"
                class="block py-2 pl-10 text-gray-900 border-0 rounded-lg ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
    </div>

    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <x-mary-table :headers="$headers" :rows="$products" :sort-by="$sortBy" with-pagination />
        </div>
    </div>
</div>
