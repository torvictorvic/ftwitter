<?php

test('backend test suite boots successfully', function () {
    expect(app())->not->toBeNull();
});
