<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas produk')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_catalog_only')->label('Katalog informasi (belum dijual)')
                            ->helperText('Aktifkan jika harga atau stok belum dikonfirmasi. Tombol beli dan checkout akan dinonaktifkan.')->columnSpanFull(),
                        TextInput::make('source_url')->label('Tautan sumber produk')->url()->maxLength(500)->columnSpanFull(),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('barcode')
                            ->label('Barcode')
                            ->maxLength(100)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('name')
                            ->label('Nama produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 4,
                            ]),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true)
                            ->hidden()
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('short_name')
                            ->label('Nama singkat')
                            ->maxLength(150)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 4,
                            ]),
                        Select::make('brand_id')
                            ->label('Brand')
                            ->options(Brand::query()->orderBy('sort_order')->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        Select::make('product_type_id')
                            ->label('Tipe produk')
                            ->options(ProductType::query()->orderBy('sort_order')->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('product_category_id', null))
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        Select::make('product_category_id')
                            ->label('Kategori')
                            ->options(fn (Get $get): array => ProductCategory::query()
                                ->with('parent')
                                ->when($get('product_type_id'), fn ($query, $productTypeId) => $query->where('product_type_id', $productTypeId))
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn (ProductCategory $category): array => [$category->id => $category->full_name])
                                ->all())
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(fn (Get $get): bool => blank($get('product_type_id')))
                            ->helperText('Pilih tipe produk lebih dulu agar daftar kategori relevan.')
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        Select::make('measurement_unit_id')
                            ->label('Satuan')
                            ->options(MeasurementUnit::query()->orderBy('sort_order')->get()->mapWithKeys(fn (MeasurementUnit $unit): array => [$unit->id => $unit->display_name])->all())
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('unit_value')
                            ->label('Isi per satuan')
                            ->required()
                            ->numeric()
                            ->minValue(0.001)
                            ->default(1)
                            ->helperText('Contoh: 1 pcs, 5 kg, 500 ml.')
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        FileUpload::make('thumbnail_path')
                            ->label('Thumbnail produk')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1', '4:5', '16:9'])
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('800')
                            ->imageResizeUpscale(false)
                            ->helperText(new HtmlString('Ukuran aman: <strong>800 x 800 px</strong>. Sistem akan menyesuaikan agar thumbnail tetap rapi di landing page.'))
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 4,
                            ]),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 8,
                            ]),
                        Textarea::make('description')
                            ->label('Deskripsi lengkap')
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),
                Section::make('Harga dan stok')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga jual')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('partner_discount_price')
                            ->label('Harga khusus mitra')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Harga ini hanya berlaku untuk akun mitra yang sudah memenuhi omzet dan disetujui admin/superadmin.')
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('compare_at_price')
                            ->label('Harga coret')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Isi jika ada diskon atau harga pembanding.')
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('cost_price')
                            ->label('Harga modal')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('stock_quantity')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('min_order_quantity')
                            ->label('Minimum order')
                            ->required()
                            ->numeric()
                            ->minValue(0.001)
                            ->default(1)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                    ]),
                Section::make('Berat dan dimensi')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('weight')
                            ->label('Berat (kg)')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('length')
                            ->label('Panjang (cm)')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('width')
                            ->label('Lebar (cm)')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('height')
                            ->label('Tinggi (cm)')
                            ->numeric()
                            ->minValue(0)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                    ]),
                Section::make('Publikasi')
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        Toggle::make('is_new_arrival')
                            ->label('New Arrival')
                            ->default(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        Toggle::make('is_bestseller')
                            ->label('Bestseller')
                            ->default(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        DateTimePicker::make('published_at')
                            ->label('Waktu publish')
                            ->native(false)
                            ->seconds(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 4,
                            ]),
                    ]),
            ]);
    }
}
