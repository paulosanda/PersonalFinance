<?php

it('convert cents with comma to dot', function () {
    $value = '12,56';
    $inputFormat = 12.56;

    expect(formatMoneyToInput($value))->toBe($inputFormat);

    assert(is_float($inputFormat), 'value is not a float');
});
