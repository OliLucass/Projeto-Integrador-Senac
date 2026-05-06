<?php
session_start();
session_destroy();

header("Location: ../Index/index.php");
exit;