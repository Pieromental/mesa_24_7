<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use App\Services\ReservaService;
use App\Traits\FindsModelOrFail;
use App\Utils\DynamicQueryFilter;
use App\Utils\Response;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    use FindsModelOrFail;
    
    public function __construct(
        protected ReservaService $reservaService,
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Reserva::query();

            $filtered = DynamicQueryFilter::apply($request, $query);

            $data =  $filtered->get()->toArray();

            return Response::response(code: 200, title: 'Listado de Reservas', data: $data,);
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservaRequest $request)
    {
        try {
            $data = $request->validated();

            $this->reservaService->validarParaCrear($data);

            $reserva = Reserva::create($data);

            return Response::response(code: 201, title: 'Reserva registrada', message: 'Se creó correctamente el comensal', data: $reserva->toArray());
        
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
            $reserva = $this->findModelOrFail(Reserva::class, $id);

            return Response::response(
                code: 200,
                title: 'Detalle de la reserva',
                message: 'Datos obtenidos correctamente.',
                data: $reserva->toArray()
            );
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservaRequest $request, string $id)
    {
        try {
            $reserva = $this->findModelOrFail(Reserva::class, $id);

            $data = $request->validated();

            $this->reservaService->validarParaActualizar($id, $data);

            $reserva->update($data);

            return Response::response(
                code: 200,
                title: 'Reserva actualizada',
                message: 'Datos actualizados correctamente',
                data: $reserva->toArray()
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
            $comensal = $this->findModelOrFail(Reserva::class, $id);

            $comensal->delete();

            return Response::response(
                code: 200,
                title: 'Reserva eliminada',
                message: 'La reserva ha sido marcada como eliminada.'
            );
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }
}
