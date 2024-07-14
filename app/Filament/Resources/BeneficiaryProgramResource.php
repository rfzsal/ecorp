<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeneficiaryProgramResource\Pages;
use App\Filament\Resources\BeneficiaryProgramResource\RelationManagers;
use App\Models\BeneficiaryProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeneficiaryProgramResource extends Resource
{
    protected static ?string $modelLabel = 'Program Bantuan';
    protected static ?string $navigationLabel = 'Program Bantuan';
    protected static ?string $navigationGroup = 'Bantuan';

    protected static ?string $model = BeneficiaryProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Program')
                    ->placeholder('Pinjaman Modal Usaha Mikro')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Pinjaman modal UMKM warga RW 06 Kuta Baru Kecamatan Pasar Kemis Kabupaten Tangerang')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('category')
                    ->label('Kategori')
                    ->placeholder('Pinjaman Modal')
                    ->required()
                    ->maxLength(255),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Program')
                    ->description(fn (BeneficiaryProgram $record) => $record->description)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->alignCenter()
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Waktu Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBeneficiaryPrograms::route('/'),
        ];
    }
}
