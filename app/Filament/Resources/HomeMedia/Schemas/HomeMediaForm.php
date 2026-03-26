<?php

namespace App\Filament\Resources\HomeMedia\Schemas;

use App\Models\HomeMedia;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class HomeMediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Home Media')
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        TextInput::make('label')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('section')
                            ->formatStateUsing(fn (string $state): string => HomeMedia::sectionLabels()[$state] ?? $state)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 3,
                            ]),
                        TextInput::make('sort_order')
                            ->label('Sort order')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 2,
                            ]),
                        TextInput::make('link_url')
                            ->label('Link URL')
                            ->maxLength(255)
                            ->columnSpan([
                                'default' => 12,
                                'xl' => 4,
                            ]),
                        FileUpload::make('image_path')
                            ->label('Image')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(fn (?HomeMedia $record): array => array_values(array_unique([
                                $record?->slotConfiguration()['aspect_ratio'] ?? '16:9',
                                '16:9',
                                '4:3',
                                '1:1',
                                '3:1',
                            ])))
                            ->imageCropAspectRatio(fn (?HomeMedia $record): string => $record?->slotConfiguration()['aspect_ratio'] ?? '16:9')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth(fn (?HomeMedia $record): int => $record?->slotConfiguration()['width'] ?? 1200)
                            ->imageResizeTargetHeight(fn (?HomeMedia $record): int => $record?->slotConfiguration()['height'] ?? 675)
                            ->imageResizeUpscale(false)
                            ->helperText(function (?HomeMedia $record): HtmlString {
                                $slot = $record?->slotConfiguration();

                                if (! $slot) {
                                    return new HtmlString('Ganti gambar sesuai slot yang sedang diedit. File akan disimpan di storage publik.');
                                }

                                return new HtmlString("Ukuran terbaik untuk <strong>{$slot['name']}</strong>: <strong>{$slot['size']}</strong>. Sistem akan menyesuaikan crop/resize otomatis agar tetap fit di landing page.");
                            })
                            ->disk('public')
                            ->directory('home-media')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
