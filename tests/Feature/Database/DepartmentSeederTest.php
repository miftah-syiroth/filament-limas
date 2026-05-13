<?php

use App\Models\Department;
use App\Models\Location;
use App\Models\Room;
use Database\Seeders\DepartmentSeeder;

it('seeds departments and matching rooms per location', function () {
    $this->seed(DepartmentSeeder::class);

    $kampus1 = Location::query()->where('name', 'Kampus 1')->first();
    $kampus2 = Location::query()->where('name', 'Kampus 2')->first();

    expect($kampus1)->not->toBeNull()
        ->and($kampus2)->not->toBeNull();

    $kampus1Names = ['BAAUK', 'KKAP'];
    $kampus2Names = ['DTSI', 'SDM', 'LPPM'];

    foreach ($kampus1Names as $name) {
        $department = Department::query()
            ->where('name', $name)
            ->where('location_id', $kampus1->id)
            ->first();

        $room = Room::query()
            ->where('name', $name)
            ->where('location_id', $kampus1->id)
            ->first();

        expect($department)->not->toBeNull()
            ->and($room)->not->toBeNull();
    }

    foreach ($kampus2Names as $name) {
        $department = Department::query()
            ->where('name', $name)
            ->where('location_id', $kampus2->id)
            ->first();

        $room = Room::query()
            ->where('name', $name)
            ->where('location_id', $kampus2->id)
            ->first();

        expect($department)->not->toBeNull()
            ->and($room)->not->toBeNull();
    }

    expect(Room::query()->count())->toBe(5);
});
