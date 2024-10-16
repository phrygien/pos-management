<?php

use App\Models\Category;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Product;
use App\Models\Unit;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Collection;

new class extends Component {
    use Toast, WithFileUploads;

    #[Rule('required')]
    public ?int $category_searchable_id = null;

    #[Rule('required')]
    public ?int $brand_searchable_id = null;

    #[Rule('required')]
    public ?int $unit_searchable_id = null;

    #[Rule('required')]
    public ?int $store_searchable_id = null;

    #[Rule('nullable|image|max:1024')]
    public $photo;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public string $barcode = '';

    #[Rule('required')]
    public string $price = '';

    // Options list
    public Collection $categoriesSearchable;

    //Brand Options list
    public Collection $brandsSearchable;

    //Brand Options list
    public Collection $unitsSearchable;

    //Brand Options list
    public Collection $storesSearchable;

    public function mount(): void
    {
        // Fill options when component first renders
        $this->search();
        $this->searchBrand();
        $this->searchUnit();
        $this->searchStore();
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

    // Also called as you type
    public function searchStore(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Store::where('id', $this->store_searchable_id)->get();

        $this->storesSearchable = Store::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption); // <-- Adds selected option
    }

    public function updatedBarcode($barcode)
    {
        $this->toast("Code-barre scanné avec succès: $barcode", 'success');
    }

    public function save()
    {
        $this->validate();

        if ($this->photo) {
            // Enregistrement de l'image dans le dossier 'photos' dans le répertoire 'storage/app/public/photos'
            $photoPath = $this->photo->store('photos', 'public');
            $photoUrl = "/storage/$photoPath"; // Générer l'URL complète
        }

        $product = Product::create([
            'category_id' => $this->category_searchable_id,
            'brand_id' => $this->brand_searchable_id,
            'unit_id' => $this->unit_searchable_id,
            'store_id' => $this->store_searchable_id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'price' => $this->price,
            'image' => isset($photoUrl) ? $photoUrl : null,
        ]);

        $this->success("Produit creé avec succès: $product->name", redirectTo: '/catalogues/products/create');
        $this->reset();
    }
}; ?>

<div>
    <!-- Grid stuff from Tailwind -->
    <div class="grid gap-5 lg:grid-cols-2">
        <x-mary-form wire:submit="save">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="m-5">
                    <x-mary-choices label="Point de vente" wire:model.live="store_searchable_id" icon="o-tag"
                        :options="$storesSearchable" single searchable
                        class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
                </div>

                <div class="m-5">
                    <x-mary-file label="Photo de l'article " wire:model="photo" accept="image/png, image/jpeg"
                        hint="Click to change" crop-after-change>
                        <img src="{{ $user->avatar ?? 'https://flow.mary-ui.com/images/empty-product.png' }}"
                            class="h-40 mt-3 rounded-lg" />
                    </x-mary-file>
                </div>

                <div class="m-5">
                    <x-mary-input label="Libelle" placeholder="Sair le libelle" icon="o-cube" hint=""
                        class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" wire:model.live='name' />
                </div>

                <div class="m-5">
                    <x-mary-input label="Code-barre" placeholder="Scanner le code-barre" icon="o-qr-code" hint=""
                        class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" wire:model.lazy="barcode" />
                    {{-- <button type="button" onclick="startBarcodeScanner()" class="mt-3 btn btn-primary">Scanner le
                        code-barre</button>
                    <div id="scanner-container" style="display: none;">
                        <video id="barcode-scanner"></video>
                    </div> --}}
                </div>


                <div class="m-5">
                    <x-mary-input label="Prix unitaire" placeholder="Sair le prix unitaire" icon="o-banknotes"
                        hint="" class="border-0 rounded-lg ring-1 ring-inset ring-gray-200"
                        wire:model.live="price" />
                </div>

                <div class="m-5">
                    <x-mary-choices label="Categorie" wire:model.live="category_searchable_id" icon="o-tag"
                        :options="$categoriesSearchable" single searchable
                        class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
                </div>

                <div class="m-5">
                    <x-mary-choices label="Marque" wire:model.live="brand_searchable_id" icon="o-rectangle-stack"
                        :options="$brandsSearchable" single searchable
                        class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
                </div>

                <div class="m-5">
                    <x-mary-choices label="Unité de mesure" wire:model.live="unit_searchable_id" icon="o-calculator"
                        :options="$unitsSearchable" single searchable
                        class="border-0 rounded-lg ring-1 ring-inset ring-gray-200" />
                </div>

            </div>
            <x-slot:actions>
                <x-mary-button label="Annuler" link="/catalogues/products" />
                <x-mary-button label="Enregistrer le produit" class="btn-primary" type="submit" spinner="save"
                    icon="o-paper-airplane" />
            </x-slot:actions>
        </x-mary-form>

        <div>

            {{-- Get a nice picture from `StorySet` web site --}}
            <img src="https://flow.mary-ui.com/images/edit-form.png" width="300" class="mx-auto" />
        </div>
    </div>
</div>

{{-- <script>
    function startBarcodeScanner() {
        document.getElementById('scanner-container').style.display = 'block'; // Show the scanner container

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#barcode-scanner') // The video element
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader", "upc_e_reader"]
            }
        }, function(err) {
            if (err) {
                console.log(err);
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
            // Set the barcode value in Livewire
            @this.set('barcode', code);
            Quagga.stop();
            document.getElementById('scanner-container').style.display = 'none'; // Hide scanner
        });
    }
</script> --}}
