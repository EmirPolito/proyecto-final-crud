<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - CRUD HOTEL</title>
    <link rel="stylesheet" href="../css/recuperar-contraseña.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="login-container">
        <h2>Recuperar Contraseña</h2>
        
        <?php
        if(isset($_SESSION['error_recuperar'])){
            echo "<div class='alert alert-error'>" . $_SESSION['error_recuperar'] . "</div>";
            unset($_SESSION['error_recuperar']);
        }
        if(isset($_SESSION['mensaje_recuperar'])){
            echo "<div class='alert alert-success'>" . $_SESSION['mensaje_recuperar'] . "</div>";
            unset($_SESSION['mensaje_recuperar']);
        }
        ?>

        <p style="text-align: center; margin-bottom: 20px; font-size:14px; color:#666;">Ingresa el correo asociado a tu cuenta para recibir un enlace de recuperación.</p>

        <form action="../php/auth/enviar_recuperacion.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Solicitar Enlace</button>
        </form>

        <div class="form-links">
            <a href="login.php">Volver al Login</a>
        </div>
    </div>

</body>
</html>
