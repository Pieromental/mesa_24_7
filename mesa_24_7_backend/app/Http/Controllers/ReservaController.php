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

/**
 * @OA\Tag(
 *     name="Reservas",
 *     description="Operaciones relacionadas a reservas"
 * )
 */
class ReservaController extends Controller
{
    use FindsModelOrFail;

    public function __construct(
        protected ReservaService $reservaService,
    ) {}


    /**
     * @OA\Get(
     *     path="/api/reservas",
     *     summary="Listar todas las reservas",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         description="Filtrar por fecha de reserva (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-04-20")
     *     ),
     *     @OA\Parameter(
     *         name="comensal_id",
     *         in="query",
     *         description="Filtrar por ID del comensal",
     *         required=false,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="mesa_id",
     *         in="query",
     *         description="Filtrar por ID de la mesa",
     *         required=false,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de reservas exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Listado de Reservas"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="fecha", type="string", format="date", example="2024-04-20"),
     *                 @OA\Property(property="hora", type="string", format="time", example="19:30"),
     *                 @OA\Property(property="numero_de_personas", type="integer", example=4),
     *                 @OA\Property(property="comensal_id", type="string", format="uuid"),
     *                 @OA\Property(property="mesa_id", type="string", format="uuid"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Reserva::query();

            $filtered = DynamicQueryFilter::apply($request, $query);

            $data =  $filtered->get()->toArray();

            return Response::response(code: 200, title: 'Listado de Reservas', data: $data,);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reservas",
     *     summary="Registrar una nueva reserva",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear una reserva",
     *         @OA\JsonContent(
     *             required={"fecha", "hora", "numero_de_personas", "mesa_id", "comensal_id"},
     *             @OA\Property(property="fecha", type="string", format="date", example="2024-04-20"),
     *             @OA\Property(property="hora", type="string", format="time", example="19:30"),
     *             @OA\Property(property="numero_de_personas", type="integer", example=4),
     *             @OA\Property(property="mesa_id", type="string", format="uuid", example="c26b63f5-39b2-41e8-a6cb-49ef4bfa8e84"),
     *             @OA\Property(property="comensal_id", type="string", format="uuid", example="7f3c9a4a-bcd7-4b1a-996f-87c9b2aaf876")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="title", type="string", example="Reserva registrada"),
     *             @OA\Property(property="message", type="string", example="Se creó correctamente el comensal"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="fecha", type="string", format="date"),
     *                 @OA\Property(property="hora", type="string", format="time"),
     *                 @OA\Property(property="numero_de_personas", type="integer"),
     *                 @OA\Property(property="mesa_id", type="string", format="uuid"),
     *                 @OA\Property(property="comensal_id", type="string", format="uuid"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */

    public function store(StoreReservaRequest $request)
    {
        try {
            $data = $request->validated();

            $this->reservaService->validarParaCrear($data);

            $reserva = Reserva::create($data);

            return Response::response(code: 201, title: 'Reserva registrada', message: 'Se creó correctamente el comensal', data: $reserva->toArray());
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reservas/{id}",
     *     summary="Obtener el detalle de una reserva",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="aa7c219b-bce5-44d7-89d7-b758028dd891")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Detalle de la reserva"),
     *             @OA\Property(property="message", type="string", example="Datos obtenidos correctamente."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="fecha", type="string", format="date"),
     *                 @OA\Property(property="hora", type="string", format="time"),
     *                 @OA\Property(property="numero_de_personas", type="integer"),
     *                 @OA\Property(property="mesa_id", type="string", format="uuid"),
     *                 @OA\Property(property="comensal_id", type="string", format="uuid"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
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
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/reservas/{id}",
     *     summary="Actualizar una reserva existente",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva a actualizar (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="c26b63f5-39b2-41e8-a6cb-49ef4bfa8e84")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizables de la reserva",
     *         @OA\JsonContent(
     *             @OA\Property(property="fecha", type="string", format="date", example="2024-04-25"),
     *             @OA\Property(property="hora", type="string", format="time", example="18:30"),
     *             @OA\Property(property="numero_de_personas", type="integer", example=2),
     *             @OA\Property(property="mesa_id", type="string", format="uuid", example="b93d92de-d9e7-4aab-9b36-505d65c1a411"),
     *             @OA\Property(property="comensal_id", type="string", format="uuid", example="ea7e94dc-8b2c-4653-ae4f-6f61d8b1f840")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Reserva actualizada"),
     *             @OA\Property(property="message", type="string", example="Datos actualizados correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="fecha", type="string", format="date"),
     *                 @OA\Property(property="hora", type="string", format="time"),
     *                 @OA\Property(property="numero_de_personas", type="integer"),
     *                 @OA\Property(property="mesa_id", type="string", format="uuid"),
     *                 @OA\Property(property="comensal_id", type="string", format="uuid"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
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
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/reservas/{id}",
     *     summary="Eliminar (soft delete) una reserva",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva a eliminar (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="d2a3f19e-43f9-4e89-b3e0-2a678f03d456")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Reserva eliminada"),
     *             @OA\Property(property="message", type="string", example="La reserva ha sido marcada como eliminada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado del servidor"
     *     )
     * )
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
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }
}
