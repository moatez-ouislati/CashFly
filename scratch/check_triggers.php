<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=cashflydb', 'root', '');
$triggers = $pdo->query('SHOW TRIGGERS')->fetchAll(PDO::FETCH_ASSOC);
print_r($triggers);
