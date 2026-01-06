<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Company;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    /**
     * ✅ Tenant Scoping: يعرض فقط شركة المستخدم (إلا Super Admin)
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $auth = auth()->user();
        if (!$auth) return $query;

        if ($auth->isSuperAdmin()) {
            return $query;
        }

        // Company Admin: يشوف شركته فقط
        return $query->where('company_id', $auth->company_id);
    }

    public static function form(Form $form): Form
    {
        $auth = auth()->user();

        return $form->schema([
            // Company selector: Super Admin فقط
            Forms\Components\Select::make('company_id')
                ->label('Company')
                ->options(fn () => Company::query()->pluck('name', 'id'))
                ->searchable()
                ->visible(fn () => $auth?->isSuperAdmin() === true)
                ->required(fn () => $auth?->isSuperAdmin() === true),

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            // Role: Super Admin يختار أي Role، Company Admin يختار Agent فقط
            Forms\Components\Select::make('role')
                ->label('Role')
                ->options(function () use ($auth) {
                    if ($auth?->isSuperAdmin()) {
                        return [
                            'Super Admin' => 'Super Admin',
                            'Company Admin' => 'Company Admin',
                            'Agent' => 'Agent',
                            'Customer' => 'Customer',
                        ];
                    }

                    // Company Admin
                    return [
                        'Agent' => 'Agent',
                    ];
                })
                ->required()
                ->dehydrated(false), // مش عمود في db

            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->required(fn (string $context) => $context === 'create')
                ->visible(fn () => $auth?->isSuperAdmin() === true || $auth?->isCompanyAdmin() === true),

        ]);
    }

    public static function table(Table $table): Table
    {
        $auth = auth()->user();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->visible(fn () => $auth?->isSuperAdmin() === true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                // فلتر للشركات: Super Admin فقط
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Company')
                    ->options(fn () => Company::query()->pluck('name', 'id'))
                    ->visible(fn () => $auth?->isSuperAdmin() === true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
