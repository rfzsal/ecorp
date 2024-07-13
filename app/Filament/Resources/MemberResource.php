<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use App\Models\MemberGroup;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class MemberResource extends Resource
{
    protected static ?string $modelLabel = 'Anggota';
    protected static ?string $navigationLabel = 'Anggota';
    protected static ?string $navigationGroup = 'Anggota';

    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->placeholder('Muhammad Faizal Fazri')
                    ->required()
                    ->autocomplete(false)
                    ->maxLength(255),
                PhoneInput::make('phone')
                    ->label('Nomor Telepon')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->i18n([
                        "searchPlaceholder" => 'Cari kode negara..',
                    ]),
                Select::make('member_group_id')
                    ->label('Kelompok Anggota')
                    ->required()
                    ->options(MemberGroup::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('user_id')
                    ->label('Akun Pengguna')
                    ->unique(ignoreRecord: true)
                    ->options(User::all()->pluck('email', 'id'))
                    ->searchable(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->description(static function (Member $record): string {
                        $memberGroup = MemberGroup::find($record->member_group_id);
                        if (!$memberGroup) {
                            return '';
                        }

                        return $memberGroup->name;
                    })
                    ->searchable(),
                PhoneColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->copyable()
                    ->copyMessage('Nomor telepon tersalin')
                    ->copyMessageDuration(1500)
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After)
                    ->alignCenter()
                    ->displayFormat(PhoneInputNumberType::INTERNATIONAL),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Akun Pengguna')
                    ->state(static function (Member $record): string {
                        $user = User::find($record->user_id);
                        if (!$user) {
                            return '';
                        }

                        return $user->email;
                    })
                    ->copyable()
                    ->copyMessage('Alamat email tersalin')
                    ->copyMessageDuration(1500)
                    ->icon('heroicon-o-clipboard')
                    ->iconPosition(IconPosition::After)
                    ->placeholder('Tidak ada akun')
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
                Filter::make('user_id')
                    ->label('Memiliki Akun')
                    ->query(static function (Builder $query): Builder {
                        return $query->whereNotNull('user_id');
                    })
                    ->toggle(),
                SelectFilter::make('memberGroup')
                    ->label('Kelompok Anggota')
                    ->relationship('memberGroup', 'name')
                    ->searchable()
                    ->preload()
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMembers::route('/'),
        ];
    }
}
