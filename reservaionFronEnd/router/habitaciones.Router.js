const express = require('express');
const service = require('../controllers/habitaciones.controllers');

const router = express.Router();

router.get('/habitaciones', service.obtenerListHabitacionesService);
router.post('/habitaciones', service.addDataHabitaciones);
router.put('/habitaciones/:id', service.updateDataHabitaciones);
router.delete('/habitaciones/:id', service.deleteDataHabitaciones);

module.exports = router;
