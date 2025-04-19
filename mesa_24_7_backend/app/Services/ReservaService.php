<?php

namespace App\Services;

use App\Models\Reserva;
use Exception;

class ReservaService
{
    public function validarParaCrear(array $data): void
    {
        if ($this->mesaOcupada($data)) {
            throw new Exception('La mesa ya está reservada para esa fecha y hora', 422);
        }

        if ($this->comensalOcupado($data)) {
            throw new Exception('El comensal ya tiene una reserva en ese horario', 422);
        }
    }

    public function validarParaActualizar(string $id, array $data): void
    {
        if ($this->mesaOcupada($data, $id)) {
            throw new Exception('La mesa ya está reservada para esa fecha y hora', 422);
        }

        if ($this->comensalOcupado($data, $id)) {
            throw new Exception('El comensal ya tiene una reserva en ese horario', 422);
        }
    }

    protected function mesaOcupada(array $data, ?string $excluirId = null): bool
    {
        $query = Reserva::where('mesa_id', $data['mesa_id'])
            ->where('fecha', $data['fecha'])
            ->where('hora', $data['hora']);

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }

    protected function comensalOcupado(array $data, ?string $excluirId = null): bool
    {
        $query = Reserva::where('comensal_id', $data['comensal_id'])
            ->where('fecha', $data['fecha'])
            ->where('hora', $data['hora']);

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }
}
