<?php
session_start();        // Inicia la sesión
session_unset();        // Elimina todas las variables de sesión
session_destroy();      // Destruye la sesión

// Redirige al usuario al login
header("Location: login.html");
exit();
?>