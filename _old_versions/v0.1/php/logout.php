<?php
session_start();
session_destroy();
header("Location: /index.html"); // or wherever your login page is
