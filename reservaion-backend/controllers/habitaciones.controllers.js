const conexion = require('../model/database');

exports.obtenerListHabitacionesService = (req, res) => {
    const SQL = "SELECT * FROM `habitaciones`";
    conexion.query(SQL, (err, results) => {
        if (err) throw err;
        res.json(results);
    });
};

exports.addDataHabitaciones = (req, res) => {
    const { nombre, tipo, precio } = req.body;
    const SQL = "INSERT INTO `habitaciones` (nombre, tipo, precio) VALUES (?, ?, ?)";
    conexion.query(SQL, [nombre, tipo, precio], (err, results) => {
        if (err) throw err;
        res.json({ message: "hanitacion agregado" });
    });
};

exports.updateDataHabitaciones = (req, res) => {
    const id = req.params.id;
    const { nombre, tipo, precio  } = req.body;
    const SQL = "UPDATE `habitaciones` SET nombre=?, tipo=?, precio=? WHERE id=?";
    conexion.query(SQL, [nombre, tipo, precio, id], (err, results) => {
        if (err) throw err;
        res.json({ message: "habitacion actualizado" });
    });
};

exports.deleteDataHabitaciones = (req, res) => {
    const id = req.params.id;
    const SQL = "DELETE FROM `habitaciones` WHERE id=?";
    conexion.query(SQL, id, (err, results) => {
        if (err) throw err;
        res.json({ message: "habitacion eliminado" });
    });
};
