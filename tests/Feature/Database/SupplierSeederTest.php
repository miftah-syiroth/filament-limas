<?php

use App\Models\Supplier;
use Database\Seeders\SupplierSeeder;

it('seeds three Purwokerto-area suppliers aligned with model catalog', function () {
    $this->seed(SupplierSeeder::class);

    expect(Supplier::query()->count())->toBe(3);

    $panglima = Supplier::query()->where('name', 'Panglima Besar Stationery')->first();
    expect($panglima)->not->toBeNull()
        ->and($panglima->city)->toBe('ID3302')
        ->and($panglima->state)->toBe('ID33')
        ->and($panglima->country)->toBe('ID')
        ->and($panglima->zip)->toBe('53127')
        ->and($panglima->phone)->toBe('087778888727');

    $duta = Supplier::query()->where('name', 'Duta Mode Purwokerto')->first();
    expect($duta)->not->toBeNull()
        ->and($duta->city)->toBe('ID3302')
        ->and($duta->state)->toBe('ID33')
        ->and($duta->country)->toBe('ID')
        ->and($duta->zip)->toBe('53114')
        ->and($duta->phone)->toBe('081388130164');

    $els = Supplier::query()->where('name', 'ELS Computer Purwokerto')->first();
    expect($els)->not->toBeNull()
        ->and($els->city)->toBe('ID3302')
        ->and($els->state)->toBe('ID33')
        ->and($els->country)->toBe('ID')
        ->and($els->zip)->toBe('53114')
        ->and($els->phone)->toBe('08989203040')
        ->and($els->url)->toBe('https://www.els.id/');
});
