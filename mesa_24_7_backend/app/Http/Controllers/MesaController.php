<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use App\Http\Requests\StoreMesaRequest;
use App\Http\Requests\UpdateMesaRequest;
use App\Models\Mesa;
use App\Utils\DynamicQueryFilter;
use App\Utils\Response;
use Illuminate\Http\Request;
use App\Traits\FindsModelOrFail;
class MesaController extends Controller
{
    use FindsModelOrFail;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Mesa::query();

            $filtered = DynamicQueryFilter::apply($request, $query);

            $data =  $filtered->get()->toArray();

            return Response::response(code: 200, title: 'Listado de Mesas', data: $data,);
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMesaRequest $request)
    {
        try {
            $data = $request->validated();

            $mesa = Mesa::create($data);

            return Response::response(code: 201, title: 'Mesa registrada', message: 'Se creó correctamente la mesa', data: [$mesa->toArray()]);
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
            $mesa = $this->findModelOrFail(Mesa::class, $id);

            return Response::response(
                code: 200,
                title: 'Detalle de la mesa',
                message: 'Datos obtenidos correctamente.',
                data: [$mesa]
            );
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMesaRequest $request, string $id)
    {
        try {
            $comensal = $this->findModelOrFail(Mesa::class, $id);

            $data = $request->validated();

            $comensal->update($data);

            return Response::response(
                code: 200,
                title: 'Mesa actualizada',
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
            $comensal = $this->findModelOrFail(Mesa::class, $id);

            $comensal->delete();

            return Response::response(
                code: 200,
                title: 'Mesa eliminada',
                message: 'La mesa ha sido marcada como eliminada.'
            );
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }
}
