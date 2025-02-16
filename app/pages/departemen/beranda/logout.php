<?php

require_once ('./../../../functions/init-session.php');

session_destroy();

header("Location: /pages/auth/sign-in");
