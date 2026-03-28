<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;

test('application uses immutable dates', function () {
    expect(Date::now())->toBeInstanceOf(CarbonImmutable::class);
});
