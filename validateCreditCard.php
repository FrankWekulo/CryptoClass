<?php
function validateCreditCard($number) {
    // Remove any non-digit characters
    $number = preg_replace('/\D/', '', $number);

    // Check length
    if (strlen($number) < 13 || strlen($number) > 19) {
        return false;
    }

    // Luhn algorithm
    $sum = 0;
    $alt = false;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $n = $number[$i];
        if ($alt) {
            $n *= 2;
            if ($n > 9) {
                $n -= 9;
            }
        }
        $sum += $n;
        $alt = !$alt;
    }

    return ($sum % 10 == 0);
}
?>
