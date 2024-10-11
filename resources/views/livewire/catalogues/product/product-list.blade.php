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
    public string $barcode = '';

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
            //->when($this->search, fn(Builder $query) => $query->where('name', 'like', "%{$this->search}%"))
            ->when($this->search, function (Builder $query) {
                // Recherche uniquement par nom
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->barcode, function (Builder $query) {
                // Recherche uniquement par code-barres
                $query->where('barcode', 'like', "%{$this->barcode}%");
            })
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
        <div class="relative flex space-x-4 text-sm text-gray-800">
            <x-mary-input icon="o-qr-code" placeholder="Code-barre"
                class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" wire:model.live="barcode" />
            <x-mary-input icon="o-magnifying-glass" placeholder="Libelle"
                class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" wire:model.live="search" />
            <x-mary-input icon="o-tag" placeholder="Categorie"
                class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
        </div>
    </div>


    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <x-mary-table :headers="$headers" :rows="$products" :sort-by="$sortBy" with-pagination />
        </div>
    </div>
</div>
