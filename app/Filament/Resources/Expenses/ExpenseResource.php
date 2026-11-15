<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Concerns\RestrictsDeleteToSuperAdmin;
use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Expenses\Pages\EditExpense;
use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Models\Expense;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ExpenseResource extends Resource
{
    use RestrictsDeleteToSuperAdmin;

    protected static ?string $model = Expense::class;

    protected static ?string $modelLabel = 'Pengeluaran';

    protected static ?string $pluralModelLabel = 'Pengeluaran';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTrendingDown;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('reference')->label('Referensi')->default(fn () => 'EXP-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)))
                ->required()->maxLength(255)->unique(ignoreRecord: true),
            DatePicker::make('expense_date')->label('Tanggal pengeluaran')->default(now())->required()->maxDate(today()),
            TextInput::make('description')->label('Keterangan')->required()->maxLength(255),
            Select::make('category')->label('Kategori')->options(Expense::categories())->required()->native(false),
            TextInput::make('amount')->label('Nominal')->prefix('Rp')->numeric()->minValue(0.01)->maxValue(9999999999999999.99)->step(0.01)->required(),
            Textarea::make('notes')->label('Catatan / bukti transaksi')->rows(3)->maxLength(5000)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('expense_date', 'desc')->columns([
            TextColumn::make('reference')->label('Referensi')->searchable()->copyable(),
            TextColumn::make('expense_date')->label('Tanggal')->date('d M Y')->sortable(),
            TextColumn::make('description')->label('Keterangan')->searchable()->wrap(),
            TextColumn::make('category')->label('Kategori')->badge()->formatStateUsing(fn (string $state): string => Expense::categories()[$state] ?? $state),
            TextColumn::make('amount')->label('Nominal')->money('IDR')->sortable(),
        ])->filters([SelectFilter::make('category')->label('Kategori')->options(Expense::categories())])
            ->recordActions([EditAction::make()])->emptyStateHeading('Belum ada pengeluaran')->emptyStateDescription('Catat biaya operasional, pengiriman, atau pembelian stok.');
    }

    public static function getPages(): array
    {
        return ['index' => ListExpenses::route('/'), 'create' => CreateExpense::route('/create'), 'edit' => EditExpense::route('/{record}/edit')];
    }
}
