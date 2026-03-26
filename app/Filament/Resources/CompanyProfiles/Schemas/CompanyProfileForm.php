<?php

namespace App\Filament\Resources\CompanyProfiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'xl' => 12,
            ])
            ->components([
                Section::make('Brand')
                    ->columns(2)
                    ->columnSpan([
                        'default' => 1,
                        'xl' => 6,
                    ])
                    ->schema([
                        TextInput::make('company_name')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('logo_path')
                            ->label('Company logo')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '3:1',
                                '4:1',
                                '5:2',
                            ])
                            ->imageCropAspectRatio('3:1')
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('440')
                            ->imageResizeTargetHeight('140')
                            ->imageResizeUpscale(false)
                            ->helperText('Recommended: transparent PNG/SVG with horizontal ratio. Best result around 440 x 140 px. If empty, the UI will show the company name as text.')
                            ->disk('public')
                            ->directory('company')
                            ->visibility('public')
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(10)
                            ->columnSpanFull(),
                    ]),
                Grid::make(1)
                    ->columnSpan([
                        'default' => 1,
                        'xl' => 6,
                    ])
                    ->columnStart([
                        'xl' => 7,
                    ])
                    ->schema([
                        Section::make('Social Media')
                            ->columns(2)
                            ->schema([
                                TextInput::make('facebook_url')
                                    ->label('Facebook URL')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('x_url')
                                    ->label('X URL')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('youtube_url')
                                    ->label('YouTube URL')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('instagram_url')
                                    ->label('Instagram URL')
                                    ->url()
                                    ->maxLength(255),
                            ]),
                        Section::make('Contact')
                            ->columns(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('address')
                                    ->required()
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
