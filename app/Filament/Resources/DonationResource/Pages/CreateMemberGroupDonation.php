<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use App\Models\MemberGroup;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberGroupDonation extends CreateRecord
{
    protected static string $resource = DonationResource::class;

    public function form(Form $form): Form
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
                    ])
                    ->required()
                    ->native(false),
                Select::make('member_group_id')
                    ->label('Nama Kelompok Anggota')
                    ->options(
                        MemberGroup::all()->mapWithKeys(function ($memberGroup) {
                            return [$memberGroup->id => $memberGroup->name];
                        })
                    )
                    ->required()
                    ->searchable(),
            ])
            ->columns(1);
    }

    protected function getFormActions(): array
    {
        return [
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->action('createAnother')
            ->keyBindings(['mod+s']);
    }
}
