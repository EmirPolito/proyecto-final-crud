//Paso 1: Importar los paquetes instalados: express y body.parser
const express = require('express');//
const bodyParser = require('body-parser');//
const clientesRouter = require('./router/clientes.Router');
const habitacionRouter = require('./router/habitaciones.Router');
const reservacionRouter = require('./router/reservaciones.Router');
const cors = require("cors");

//Paso 2: Crear una instancia de EXPRESS
const app = express();//

//Paso 3: Definir el número de puerto
const PORT = 4000;//
//Puertos de servicios: mysql: 3306, http:80,8080, ftp: 21 y 22, etc.

//Paso 4: Configurar el Middleware, que es un intermediario entre las peticiones de las aplicaciones cliente y el servidor.
//Inicio del Middleware
app.use(cors());//
app.use(bodyParser.json());//
app.use(clientesRouter)
app.use(habitacionRouter)
app.use(reservacionRouter)
//Termino del Middleware

//Paso 5: Definir el metodo para ejecutar el servidor.
app.listen(PORT, () => {
    console.log(`Servidor iniciado en el puerto ${PORT}`);
})