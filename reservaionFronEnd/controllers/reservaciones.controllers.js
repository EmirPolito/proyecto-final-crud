const conexion = require('../model/database');

// Obtener lista de reservaciones
exports.obtenerListReservacionesService = (req, res) => {
    const SQL = "SELECT * FROM `reservaciones`";
    conexion.query(SQL, (err, results) => {
        if (err) throw err;
        res.json(results);
    });
};

// Agregar una nueva reservación
exports.addDataReservaciones = (req, res) => {
    const { folio, nombrecliente } = req.body;
    const SQL = "INSERT INTO `reservaciones` (folio, nombrecliente) VALUES (?, ?)";
    conexion.query(SQL, [folio, nombrecliente], (err, results) => {
        if (err) {
            console.error(err);
            res.status(500).json({ message: "Error al guardar reservación" });
        } else {
            res.json({ message: "Reservación agregada correctamente" });
        }
    });
};


// Actualizar una reservación existente
exports.updateDataReservaciones = (req, res) => {
    const id = req.params.id;
    const { folio, nombrecliente } = req.body; // Ajustar al esquema de la tabla
    const SQL = "UPDATE `reservaciones` SET folio=?, nombrecliente=? WHERE id=?";
    conexion.query(SQL, [folio, nombrecliente, id], (err, results) => {
        if (err) throw err;
        if (results.affectedRows === 0) {
            res.status(404).json({ message: "Reservación no encontrada" });
        } else {
            res.json({ message: "Reservación actualizada correctamente" });
        }
    });
};

// Eliminar una reservación
exports.deleteDataReservaciones = (req, res) => {
    const id = req.params.id; // Usar `id` como clave primaria
    const SQL = "DELETE FROM `reservaciones` WHERE id=?";
    conexion.query(SQL, id, (err, results) => {
        if (err) throw err;
        if (results.affectedRows === 0) {
            res.status(404).json({ message: "Reservación no encontrada" });
        } else {
            res.json({ message: "Reservación eliminada correctamente" });
        }
    });
};
