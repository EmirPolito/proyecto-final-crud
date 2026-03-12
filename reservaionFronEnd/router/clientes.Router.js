const express = require('express');
const service = require('../controllers/clientes.controllers');

const router = express.Router();

router.get('/clientes', service.obtenerListClientesService);
router.post('/clientes', service.addDataCliente);
router.put('/clientes/:id', service.updateDataCliente);
router.delete('/clientes/:id', service.deleteDataCliente);

module.exports = router;
