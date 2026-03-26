<?php

namespace App\Filament\Resources\AboutValues\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AboutValueInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Our Value')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('icon')
                            ->label('Icon'),
                        TextEntry::make('description')
                            ->columnSpanFull(),
                        TextEntry::make('sort_order')
                            ->label('Sort order'),
                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                    ]),
                Section::make('Metadata')
                    ->columns(2)
                    ->compact()
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),
            ]);
    }
}
