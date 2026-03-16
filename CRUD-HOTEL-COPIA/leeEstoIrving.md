# Forma correcta de importar archivos css para ver cambios rapidos al recargar

# Correcta
# 1
# <!--  <link rel="stylesheet" href="../css/archivo.css?v=<?php echo time(); ?>">  -->

# 2
# <!--  <link rel="stylesheet" href="../../css/archivo.css?v=<?php echo time(); ?>">  -->


# Incorrecta 
# <!-- <link rel="stylesheet" href="../../css/usuarios.css"> -->
