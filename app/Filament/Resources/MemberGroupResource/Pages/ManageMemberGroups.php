<?php

namespace App\Filament\Resources\MemberGroupResource\Pages;

use App\Filament\Resources\MemberGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMemberGroups extends ManageRecords
{
    protected static string $resource = MemberGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
