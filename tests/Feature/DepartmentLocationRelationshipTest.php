<?php

use App\Models\Department;
use App\Models\Location;
use App\Models\Organization;

it('loads department locations from the department_locations pivot table', function () {
    $organization = Organization::query()->create([
        'name' => 'Test Organization',
    ]);

    $location = Location::query()->create([
        'name' => 'Kampus 1',
        'organization_id' => $organization->id,
    ]);

    $department = Department::query()->create([
        'name' => 'BAAUK',
        'organization_id' => $organization->id,
    ]);

    $department->locations()->attach($location);

    $department->load('locations');

    expect($department->locations)->toHaveCount(1)
        ->and($department->locations->first()->is($location))->toBeTrue();
});
