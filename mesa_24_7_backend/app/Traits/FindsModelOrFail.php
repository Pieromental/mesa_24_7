<?php

namespace App\Traits;

use App\Exceptions\GeneralException;
use Illuminate\Database\Eloquent\Model;


trait FindsModelOrFail
{
    /**
     * Busca un modelo por su clase e ID. Lanza excepción si no existe.
     *
     * @param class-string<Model> $modelClass
     * @param mixed $id
     * @return Model
     *
     * @throws \GeneralException
     */
    protected function findModelOrFail(string $modelClass, mixed $id): Model
    {
        $model = $modelClass::find($id);

        if (!$model) {
            throw new GeneralException("No se encontró " . class_basename($modelClass), 404);
        }

        return $model;
    }
}