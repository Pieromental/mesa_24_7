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

/**
 * @OA\Tag(
 *     name="Mesas",
 *     description="Operaciones relacionadas a mesas"
 * )
 */
class MesaController extends Controller
{
    use FindsModelOrFail;


    /**
     * @OA\Get(
     *     path="/api/mesas",
     *     summary="Listar todas las mesas",
     *     tags={"Mesas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="numero_mesa",
     *         in="query",
     *         description="Filtrar por número de mesa",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="capacidad",
     *         in="query",
     *         description="Filtrar por capacidad exacta",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="ubicacion",
     *         in="query",
     *         description="Filtrar por ubicacin",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de mesas exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Listado de Mesas"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string", example="uuid-1234"),
     *                 @OA\Property(property="numero_mesa", type="string", example="M01"),
     *                 @OA\Property(property="capacidad", type="integer", example=4),
     *                 @OA\Property(property="ubicacion", type="string", example="Terraza"),
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
            $query = Mesa::query();

            $filtered = DynamicQueryFilter::apply($request, $query);

            $data =  $filtered->get()->toArray();

            return Response::response(code: 200, title: 'Listado de Mesas', data: $data,);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/mesas",
     *     summary="Registrar una nueva mesa",
     *     tags={"Mesas"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear una mesa",
     *         @OA\JsonContent(
     *             required={"numero_mesa", "capacidad"},
     *             @OA\Property(property="numero_mesa", type="string", example="M01"),
     *             @OA\Property(property="capacidad", type="integer", example=4),
     *             @OA\Property(property="ubicacion", type="string", example="Interior")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Mesa creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="title", type="string", example="Mesa registrada"),
     *             @OA\Property(property="message", type="string", example="Se creó correctamente la mesa"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", example="uuid-1234"),
     *                 @OA\Property(property="numero_mesa", type="string", example="M01"),
     *                 @OA\Property(property="capacidad", type="integer", example=4),
     *                 @OA\Property(property="ubicacion", type="string", example="Interior"),
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
    public function store(StoreMesaRequest $request)
    {
        try {
            $data = $request->validated();

            $mesa = Mesa::create($data);

            return Response::response(code: 201, title: 'Mesa registrada', message: 'Se creó correctamente la mesa', data: $mesa->toArray());
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/mesas/{id}",
     *     summary="Obtener el detalle de una mesa",
     *     tags={"Mesas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID único de la mesa (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="c26b63f5-39b2-41e8-a6cb-49ef4bfa8e84")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mesa encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Detalle de la mesa"),
     *             @OA\Property(property="message", type="string", example="Datos obtenidos correctamente."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="numero_mesa", type="string", example="M01"),
     *                 @OA\Property(property="capacidad", type="integer", example=4),
     *                 @OA\Property(property="ubicacion", type="string", example="Terraza"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mesa no encontrada"
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
            $mesa = $this->findModelOrFail(Mesa::class, $id);

            return Response::response(
                code: 200,
                title: 'Detalle de la mesa',
                message: 'Datos obtenidos correctamente.',
                data: $mesa->toArray()
            );
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/mesas/{id}",
     *     summary="Actualizar los datos de una mesa",
     *     tags={"Mesas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mesa a actualizar (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="e7cf2d8f-1f9f-4bb0-a0ec-45e9f0dcf63c")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizables de la mesa",
     *         @OA\JsonContent(
     *             @OA\Property(property="numero_mesa", type="string", example="M03"),
     *             @OA\Property(property="capacidad", type="integer", example=6),
     *             @OA\Property(property="ubicacion", type="string", example="Zona VIP")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mesa actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Mesa actualizada"),
     *             @OA\Property(property="message", type="string", example="Datos actualizados correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="numero_mesa", type="string", example="M03"),
     *                 @OA\Property(property="capacidad", type="integer", example=6),
     *                 @OA\Property(property="ubicacion", type="string", example="Zona VIP"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mesa no encontrada"
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

    public function update(UpdateMesaRequest $request, string $id)
    {
        try {
            $mesa = $this->findModelOrFail(Mesa::class, $id);

            $data = $request->validated();

            $mesa->update($data);

            return Response::response(
                code: 200,
                title: 'Mesa actualizada',
                message: 'Datos actualizados correctamente',
                data: $mesa->toArray()
            );
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/mesas/{id}",
     *     summary="Eliminar (soft delete) una mesa",
     *     tags={"Mesas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mesa a eliminar (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="b7421ab0-7a5b-4ccf-8c2d-99398779e3c5")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mesa eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Mesa eliminada"),
     *             @OA\Property(property="message", type="string", example="La mesa ha sido marcada como eliminada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mesa no encontrada"
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
            $mesa = $this->findModelOrFail(Mesa::class, $id);

            $mesa->delete();

            return Response::response(
                code: 200,
                title: 'Mesa eliminada',
                message: 'La mesa ha sido marcada como eliminada.'
            );
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }
}
