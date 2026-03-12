//Paso 1: Crear el objeto "mysql2"
const mysql = require('mysql2');

//Paso 2: Crear el objeto de conexion.
const conexion = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    // port: 3306 PUERTO RESTABLECIDO EN EL 4000
    password: '1234',
    database: 'BDReservacion'
})


//Paso 3: Establecer la conexiona a la base de datos.
conexion.connect((err) =>{
    if (err) throw err;
    console.log("Conexion exitosa a la base de datos")
})

//Paso 4: Exportar el objeto: db, para ser usado en otros archivos.
module.exports = conexion;
