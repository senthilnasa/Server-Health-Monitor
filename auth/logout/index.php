<?php

session_name('senthilnasa');
session_start();
session_destroy();

header('Location: /auth', true);
