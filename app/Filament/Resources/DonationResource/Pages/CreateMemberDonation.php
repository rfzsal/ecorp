<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use App\Models\Member;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberDonation extends CreateRecord
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
                        'Sedekah' => 'Sedekah',
                        'Zakat' => 'Zakat',
                    ])
                    ->required()
                    ->native(false),
                Select::make('member_id')
                    ->label('Nama Anggota')
                    ->options(
                        Member::with('memberGroup')->get()->mapWithKeys(function ($member) {
                            return [$member->id => $member->name . ' - ' . $member->memberGroup->name];
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
