<?php
session_start();
session_destroy();
header("Location: /"); // or wherever your login page is
