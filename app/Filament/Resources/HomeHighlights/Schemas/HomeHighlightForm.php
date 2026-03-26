<?php

namespace App\Filament\Resources\HomeHighlights\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeHighlightForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Home Highlight')
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                            ])
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('128')
                            ->imageResizeTargetHeight('128')
                            ->imageResizeUpscale(false)
                            ->helperText('Upload PNG/SVG/JPG icon image. Best result around 96 x 96 px to 128 x 128 px. If emptied, the image will not be shown.')
                            ->disk('public')
                            ->directory('home-highlights')
                            ->visibility('public')
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
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
                        Toggle::make('is_active')
                            ->label('Active')
                            ->inline(false)
                            ->default(true)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 7,
                            ]),
                    ]),
            ]);
    }
}
