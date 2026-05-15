<?php

use App\Filament\Resources\Models\Schemas\ModelForm;
use App\Models\Depreciation;
use Illuminate\Database\Eloquent\Builder;

uses(Tests\TestCase::class);

afterEach(function (): void {
    Mockery::close();
});

it('syncs end_of_life from depreciation months when depreciation_id is set', function (): void {
    $depreciationId = 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa';
    $depreciation = (object) ['months' => 36];

    $builder = Mockery::mock(Builder::class);
    $builder->shouldReceive('find')
        ->once()
        ->with($depreciationId)
        ->andReturn($depreciation);

    $depreciationModel = Mockery::mock('alias:'.Depreciation::class);
    $depreciationModel->shouldReceive('query')
        ->once()
        ->andReturn($builder);

    $data = ModelForm::syncEndOfLifeFromDepreciation([
        'depreciation_id' => $depreciationId,
        'end_of_life' => 60,
    ]);

    expect($data['end_of_life'])->toBe(36);
});

it('leaves end_of_life unchanged when depreciation_id is empty', function (): void {
    $data = ModelForm::syncEndOfLifeFromDepreciation([
        'depreciation_id' => null,
        'end_of_life' => 24,
    ]);

    expect($data['end_of_life'])->toBe(24);
});

it('leaves end_of_life unchanged when depreciation is not found', function (): void {
    $depreciationId = 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb';

    $builder = Mockery::mock(Builder::class);
    $builder->shouldReceive('find')
        ->once()
        ->with($depreciationId)
        ->andReturn(null);

    $depreciationModel = Mockery::mock('alias:'.Depreciation::class);
    $depreciationModel->shouldReceive('query')
        ->once()
        ->andReturn($builder);

    $data = ModelForm::syncEndOfLifeFromDepreciation([
        'depreciation_id' => $depreciationId,
        'end_of_life' => 48,
    ]);

    expect($data['end_of_life'])->toBe(48);
});
