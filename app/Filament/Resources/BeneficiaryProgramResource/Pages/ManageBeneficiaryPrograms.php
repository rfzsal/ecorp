<?php

namespace App\Filament\Resources\BeneficiaryProgramResource\Pages;

use App\Filament\Resources\BeneficiaryProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBeneficiaryPrograms extends ManageRecords
{
    protected static string $resource = BeneficiaryProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
