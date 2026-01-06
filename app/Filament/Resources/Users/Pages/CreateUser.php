<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $auth = auth()->user();

        // Company Admin: يثبت company_id تلقائيًا
        if ($auth && !$auth->isSuperAdmin()) {
            $data['company_id'] = $auth->company_id;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $role = $this->data['role'] ?? null;
        if ($role) {
            $this->record->syncRoles([$role]);
        }
    }
}
