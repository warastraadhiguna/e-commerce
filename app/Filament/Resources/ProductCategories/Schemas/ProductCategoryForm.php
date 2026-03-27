<?php

namespace App\Filament\Resources\ProductCategories\Schemas;

use App\Models\ProductCategory;
use App\Models\ProductType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi kategori')
                    ->columns(2)
                    ->schema([
                        Select::make('product_type_id')
                            ->label('Tipe produk')
                            ->options(ProductType::query()->orderBy('sort_order')->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('parent_id', null)),
                        Select::make('parent_id')
                            ->label('Kategori induk')
                            ->options(fn (Get $get, ?ProductCategory $record): array => ProductCategory::query()
                                ->with('parent')
                                ->when($get('product_type_id'), fn ($query, $productTypeId) => $query->where('product_type_id', $productTypeId))
                                ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn (ProductCategory $category): array => [$category->id => $category->full_name])
                                ->all())
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(fn (Get $get): bool => blank($get('product_type_id')))
                            ->helperText('Kosongkan jika ini kategori level utama.'),
                        TextInput::make('name')
                            ->label('Nama kategori')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(140)
                            ->unique(ignoreRecord: true),
                        FileUpload::make('image_path')
                            ->label('Gambar kategori')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1', '4:3', '16:9'])
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('800')
                            ->imageResizeUpscale(false)
                            ->disk('public')
                            ->directory('product-categories')
                            ->visibility('public'),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
