<?php
session_start();
$_SESSION['test'] = 'Working';
echo json_encode(['session_id' => session_id(), 'test' => $_SESSION['test']]);
?>