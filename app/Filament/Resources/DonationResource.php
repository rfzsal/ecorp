<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use App\Models\Member;
use App\Models\MemberGroup;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationResource extends Resource
{
    protected static ?string $modelLabel = 'Donasi';
    protected static ?string $navigationLabel = 'Donasi';
    protected static ?string $navigationGroup = 'Donasi';

    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal')
                    ->placeholder(25000)
                    ->required()
                    ->autocomplete(false)
                    ->numeric(),
                Select::make('type')
                    ->label('Jenis Donasi')
                    ->options([
                        'Infaq' => 'Infaq',
                        'Sedekah' => 'Sedekah',
                        'Zakat' => 'Zakat',
                    ])
                    ->disabled()
                    ->required()
                    ->native(false),
                Select::make('member_id')
                    ->label('Nama Anggota')
                    ->options(
                        Member::with('memberGroup')->get()->mapWithKeys(function ($member) {
                            return [$member->id => $member->name . ' - ' . $member->memberGroup->name];
                        })
                    )
                    ->hidden(static function (Donation $record): bool {
                        if (isset($record->member_id)) {
                            return false;
                        }

                        return true;
                    })
                    ->required()
                    ->searchable(),
                Select::make('member_group_id')
                    ->label('Nama Kelompok Anggota')
                    ->options(
                        MemberGroup::all()->mapWithKeys(function ($memberGroup) {
                            return [$memberGroup->id => $memberGroup->name];
                        })
                    )
                    ->hidden(static function (Donation $record): bool {
                        if (isset($record->member_group_id)) {
                            return false;
                        }

                        return true;
                    })
                    ->required()
                    ->searchable(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donation')
                    ->label('Sumber Donasi')
                    ->state(static function (Donation $record): string {
                        $member = Member::find($record->member_id);
                        $memberGroup = MemberGroup::find($record->member_group_id);

                        if ($member) {
                            return $member->name;
                        }

                        if ($memberGroup) {
                            return $memberGroup->name;
                        }

                        return '';
                    })
                    ->description(fn (Donation $record) => $record->type),
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Nama Pemberi Donasi')
                    ->placeholder('Tidak ada nama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('memberGroup.name')
                    ->label('Kelompok Pemberi Donasi')
                    ->placeholder('Tidak ada kelompok')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->alignCenter(),
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
                Filter::make('member_id')
                    ->label('Donasi Anggota')
                    ->query(static function (Builder $query): Builder {
                        return $query->whereNotNull('member_id');
                    })
                    ->toggle(),
                SelectFilter::make('memberGroup')
                    ->label('Kelompok Anggota')
                    ->multiple()
                    ->relationship('memberGroup', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('type')
                    ->label('Jenis Donasi')
                    ->multiple()
                    ->options([
                        'Infaq' => 'Infaq',
                        'Sedekah' => 'Sedekah',
                        'Zakat' => 'Zakat',
                    ])
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            // 'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
            'create-member-donation' => Pages\CreateMemberDonation::route('/create-member-donation'),
            'create-membergroup-donation' => Pages\CreateMemberGroupDonation::route('/create-membergroup-donation'),
        ];
    }
}
