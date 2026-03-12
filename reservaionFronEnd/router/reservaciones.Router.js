const express = require('express');
const service = require('../controllers/reservaciones.controllers');

const router = express.Router();

router.get('/reservaciones', service.obtenerListReservacionesService);
router.post('/reservaciones', service.addDataReservaciones);
router.put('/reservaciones/:id', service.updateDataReservaciones);
router.delete('/reservaciones/:id', service.deleteDataReservaciones);

module.exports = router;
