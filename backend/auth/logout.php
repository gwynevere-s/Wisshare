<?php
session_start();
session_destroy();
header('Location: ../../frontend/auth/index.html');
exit;
?>
