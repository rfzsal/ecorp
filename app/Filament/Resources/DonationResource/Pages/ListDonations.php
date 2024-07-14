<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListDonations extends ListRecords
{
    protected static string $resource = DonationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('member-donation')
                ->label('Donasi Anggota')
                ->button()
                ->url(fn (): string => DonationResource::getUrl('create-member-donation')),
            Action::make('membergroup-donation')
                ->label('Donasi Kelompok Anggota')
                ->button()
                ->url(fn (): string => DonationResource::getUrl('create-membergroup-donation')),
        ];
    }
}
