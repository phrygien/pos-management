<?php

use App\Models\Category;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Unit;
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

    #[Rule('required')]
    public ?int $category_searchable_id = null;

    #[Rule('required')]
    public ?int $brand_searchable_id = null;

    #[Rule('required')]
    public ?int $unit_searchable_id = null;

    public bool $showDrawerFilter = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Options list
    public Collection $categoriesSearchable;

    //Brand Options list
    public Collection $brandsSearchable;

    //Brand Options list
    public Collection $unitsSearchable;

    public function mount(): void
    {
        $this->search();
        $this->searchBrand();
        $this->searchUnit();
    }
    // Also called as you type
    public function search(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Category::where('id', $this->category_searchable_id)->get();

        $this->categoriesSearchable = Category::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption); // <-- Adds selected option
    }

    // Also called as you type
    public function searchBrand(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Brand::where('id', $this->brand_searchable_id)->get();

        $this->brandsSearchable = Brand::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption); // <-- Adds selected option
    }

    // Also called as you type
    public function searchUnit(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Unit::where('id', $this->unit_searchable_id)->get();

        $this->unitsSearchable = Unit::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption); // <-- Adds selected option
    }

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
        return [['key' => 'id', 'label' => 'ID', 'class' => 'w-1'], ['key' => 'name', 'label' => 'Libelle', 'class' => 'w-64'], ['key' => 'brand.name', 'label' => 'Marque', 'class' => 'w-64'], ['key' => 'category.name', 'label' => 'Category', 'class' => 'w-64'], ['key' => 'unit.name', 'label' => 'Unite', 'class' => 'w-64'], ['key' => 'barcode', 'label' => 'Code barre', 'class' => 'w-20'], ['key' => 'price', 'label' => 'Prix', 'class' => 'w-20']];
    }

    //List All Products
    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->with('category', 'brand', 'unit')
            ->when($this->search, function (Builder $query) {
                // Recherche uniquement par nom
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->barcode, function (Builder $query) {
                // Recherche uniquement par code-barres
                $query->where('barcode', 'like', "%{$this->barcode}%");
            })
            ->when($this->category_searchable_id, function (Builder $query) {
                // Filtrer par catégorie si `category_searchable_id` est défini
                $query->where('category_id', $this->category_searchable_id);
            })
            ->when($this->brand_searchable_id, function (Builder $query) {
                // Filtrer par marque si `brand_searchable_id` est défini
                $query->where('brand_id', $this->brand_searchable_id);
            })
            ->when($this->unit_searchable_id, function (Builder $query) {
                // Filtrer par unité si `unit_searchable_id` est défini
                $query->where('unit_id', $this->unit_searchable_id);
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
            {{-- <x-mary-button icon="o-funnel" label="Filtres" wire:click="$toggle('showDrawerFilter')"
                class="text-white btn-error" /> --}}
            <x-secondary-button wire:click="$toggle('showDrawerFilter')">Filtres</x-secondary-button>
        </div>
    </div>


    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <x-mary-table :headers="$headers" :rows="$products" :sort-by="$sortBy" with-pagination />
        </div>
    </div>

    {{-- Right --}}
    <x-mary-drawer wire:model="showDrawerFilter" class="w-11/12 lg:w-1/3" title="Filtrer les articles"
        subtitle="Par categorie, marque, unite de mesure ou code-barre" separator with-close-button close-on-escape
        right>
        <div>
            <div class="mb-5">
                <x-mary-choices label="Categorie" wire:model.live="category_searchable_id" icon="o-tag"
                    :options="$categoriesSearchable" single searchable class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
            </div>

            <div class="mb-5">
                <x-mary-choices label="Marque" wire:model.live="brand_searchable_id" icon="o-rectangle-stack"
                    :options="$brandsSearchable" single searchable class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
            </div>

            <div class="mb-5">
                <x-mary-choices label="Unité de mesure" wire:model.live="unit_searchable_id" icon="o-calculator"
                    :options="$unitsSearchable" single searchable class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
            </div>
        </div>
        <x-mary-button label="Effacer les filtres" class="float-right text-white btn-error" type="submit"
            spinner="save" />
    </x-mary-drawer>
</div>
