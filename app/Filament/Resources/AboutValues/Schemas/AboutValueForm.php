<?php

namespace App\Filament\Resources\AboutValues\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class AboutValueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Our Value')
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(100)
                            ->autofocus()
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('icon')
                            ->required()
                            ->maxLength(50)
                            ->rule('regex:/^[a-z0-9-]+$/')
                            ->helperText(new HtmlString('Isi nama icon Font Awesome tanpa prefix <code>fa-</code>. Contoh: <code>bolt</code>, <code>shield</code>, <code>line-chart</code>, <code>users</code>. Lihat daftar icon: <a href="https://fontawesome.com/v4.7/icons/" target="_blank" rel="noopener noreferrer">Font Awesome 4.7 Icons</a>.'))
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('sort_order')
                            ->label('Sort order')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 4,
                            ]),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->inline(false)
                            ->default(true)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                    ]),
            ]);
    }
}
