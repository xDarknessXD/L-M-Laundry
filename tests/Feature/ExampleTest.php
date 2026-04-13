<?php

test('returns a successful response', function () {
    $response = $this->get('/'); // Use root path instead of named route

    $response->assertOk();
});
