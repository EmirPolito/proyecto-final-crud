const conexion = require('../model/database');

exports.obtenerListClientesService = (req, res) => {
    const SQL = "SELECT * FROM `cliente`";
    conexion.query(SQL, (err, results) => {
        if (err) throw err;
        res.json(results);
    });
};

exports.addDataCliente = (req, res) => {
    const { nombre, correo, telefono } = req.body;
    const SQL = "INSERT INTO `cliente` (nombre, correo, telefono) VALUES (?, ?, ?)";
    conexion.query(SQL, [nombre, correo, telefono], (err, results) => {
        if (err) throw err;
        res.json({ message: "Cliente agregado" });
    });
};

exports.updateDataCliente = (req, res) => {
    const id = req.params.id;
    const { nombre, correo, telefono } = req.body;
    const SQL = "UPDATE `cliente` SET nombre=?, correo=?, telefono=? WHERE id=?";
    conexion.query(SQL, [nombre, correo, telefono, id], (err, results) => {
        if (err) throw err;
        res.json({ message: "Cliente actualizado" });
    });
};

exports.deleteDataCliente = (req, res) => {
    const id = req.params.id;
    const SQL = "DELETE FROM `cliente` WHERE id=?";
    conexion.query(SQL, id, (err, results) => {
        if (err) throw err;
        res.json({ message: "Cliente eliminado" });
    });
};
