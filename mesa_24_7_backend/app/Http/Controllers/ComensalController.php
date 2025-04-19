<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\DynamicQueryFilter;
use App\Models\Comensal;
use App\Utils\Response;
use App\Exceptions\GeneralException;
use App\Http\Requests\StoreComensalRequest;
use App\Http\Requests\UpdateComensalRequest;
use App\Traits\FindsModelOrFail;

class ComensalController extends Controller
{
    use FindsModelOrFail;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Comensal::query();

            $filtered = DynamicQueryFilter::apply($request, $query);

            $data =  $filtered->get()->toArray();

            return Response::response(code: 200, title: 'Listado de Comensales', data: $data,);
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComensalRequest $request)
    {
        try {
            $data = $request->validated();

            $comensal = Comensal::create($data);

            return Response::response(code: 201, title: 'Comensal registrado', message: 'Se creó correctamente el comensal', data: $comensal->toArray());
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $comensal = $this->findModelOrFail(Comensal::class, $id);

            return Response::response(
                code: 200,
                title: 'Detalle del comensal',
                message: 'Datos obtenidos correctamente.',
                data: $comensal->toArray()
            );
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComensalRequest $request, string $id)
    {
        try {
            $comensal = $this->findModelOrFail(Comensal::class, $id);

            $data = $request->validated();

            $comensal->update($data);

            return Response::response(
                code: 200,
                title: 'Comensal actualizado',
                message: 'Datos actualizados correctamente',
                data: $comensal->toArray()
            );
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $comensal = $this->findModelOrFail(Comensal::class, $id);

            $comensal->delete();

            return Response::response(
                code: 200,
                title: 'Comensal eliminado',
                message: 'El comensal ha sido marcado como eliminado.'
            );
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }
}
