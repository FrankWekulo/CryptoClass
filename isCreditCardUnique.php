<?php
function isCreditCardUnique($number, $conn, $encryption_key) {
    $sql = "SELECT COUNT(*) FROM CreditCards WHERE AES_DECRYPT(Token, ?) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $encryption_key, $number);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count == 0;
}
?>
