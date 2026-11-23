<?php

namespace App\Filament\Resources\CompanyProfiles\Schemas;

use App\Support\BrandTheme;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
                            ->label('Nama toko / merek')
                            ->maxLength(255)
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('logo_path')
                            ->label('Company logo')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '1:1',
                                '3:1',
                                '4:1',
                                '5:2',
                            ])
                            ->helperText('Gunakan logo asli, PNG transparan disarankan. Logo persegi maupun horizontal didukung. Maksimal 2 MB.')
                            ->disk('public')
                            ->directory('company')
                            ->visibility('public')
                            ->columnSpanFull(),
                        FileUpload::make('favicon_path')->label('Ikon browser (favicon)')
                            ->image()->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'])
                            ->maxSize(1024)->disk('public')->directory('company')->visibility('public')
                            ->helperText('Gambar persegi. Jika kosong, memakai logo toko.')->columnSpanFull(),
                        Textarea::make('short_description')
                            ->label('Short description')
                            ->rows(2)
                            ->helperText('Dipakai untuk ringkasan singkat di home, contact, dan area umum lainnya.')
                            ->columnSpanFull(),
                        Textarea::make('long_description')
                            ->label('Long description')
                            ->rows(4)
                            ->helperText('Dipakai untuk halaman About Us dengan isi yang lebih lengkap.')
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
                        Section::make('Contact')
                            ->columns(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->dehydrateStateUsing(fn (?string $state): string => $state ?? '')
                                    ->maxLength(50),
                                TextInput::make('email')
                                    ->email()
                                    ->dehydrateStateUsing(fn (?string $state): string => $state ?? '')
                                    ->maxLength(255),
                                Textarea::make('address')
                                    ->dehydrateStateUsing(fn (?string $state): string => $state ?? '')
                                    ->rows(1)
                                    ->columnSpanFull(),
                                Textarea::make('map_embed_url')
                                    ->label('Location iframe URL')
                                    ->rows(1)
                                    ->helperText('Paste the Google Maps embed URL for the contact page iframe.')
                                    ->columnSpanFull(),
                            ]),
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
                    ]),
                Section::make('Tema toko')
                    ->description('Berlaku untuk toko, login, checkout, invoice, dan warna utama panel admin. Hanya Developer yang dapat mengubah pengaturan ini.')
                    ->columns(2)->columnSpan(['default' => 1, 'xl' => 12])
                    ->schema([
                        ColorPicker::make('theme_primary_color')->label('Warna utama')->default('#fed700')->required()->regex('/^#[0-9a-fA-F]{6}$/'),
                        ColorPicker::make('theme_secondary_color')->label('Warna pendamping')->default('#0363cd')->required()->regex('/^#[0-9a-fA-F]{6}$/'),
                        ColorPicker::make('theme_background_color')->label('Warna latar toko')->default('#ffffff')->required()->regex('/^#[0-9a-fA-F]{6}$/'),
                        Select::make('theme_font')->label('Gaya huruf')->options(BrandTheme::fonts())->default('default')->required()->native(false),
                        FileUpload::make('theme_pattern_path')->label('Motif latar toko')
                            ->image()->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])->maxSize(2048)
                            ->disk('public')->directory('company/patterns')->visibility('public')->columnSpanFull()
                            ->helperText('PNG/JPG/WebP maksimal 2 MB. Gunakan motif halus yang dapat diulang. Kosongkan untuk latar polos.'),
                    ]),
            ]);
    }
}
