<?php
session_start();
session_destroy();
header("Location: ../TheProperty/index.php");
exit();
?>
