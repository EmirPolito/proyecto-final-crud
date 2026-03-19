<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: panel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRUD HOTEL</title>
    <link rel="stylesheet" href="../css/login.css?v=<?php echo time(); ?>">
</head>

<body>

    <!-- FONDO -->
    <div class="login-bg"></div>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php
if (isset($_SESSION['error_login'])) {
    echo "<div class='alert alert-error'>" . $_SESSION['error_login'] . "</div>";
    unset($_SESSION['error_login']);
}
if (isset($_SESSION['mensaje_exito'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['mensaje_exito'] . "</div>";
    unset($_SESSION['mensaje_exito']);
}
?>

        <form id="formLogin" action="../php/auth/validar_login.php" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn-primary login-btn">Entrar</button>
            <div id="mensajeJS" class="alert alert-error hidden" style="margin-top: 15px;"></div>
        </form>
        <div class="form-links">
            <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <script src="../js/main.js"></script>
</body>

</html>