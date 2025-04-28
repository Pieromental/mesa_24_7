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

/**
 * @OA\Tag(
 *     name="Comensales",
 *     description="Operaciones relacionadas a comensales"
 * )
 */
class ComensalController extends Controller
{
    use FindsModelOrFail;

    /**
     * @OA\Get(
     *     path="/api/comensales",
     *     summary="Listar comensales",
     *     tags={"Comensales"},
     *     security={{"sanctum":{}}},  
     * 
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Filtrar por el nombre del comensal",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     * 
     *     @OA\Parameter(
     *         name="correo",
     *         in="query",
     *         description="Filtrar por el correo del comensal",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="telefono",
     *         in="query",
     *         description="Filtrar por el telefono del comensal",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *         name="direccion",
     *         in="query",
     *         description="Filtrar por la direccion del comensal",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *    
     *     @OA\Response(
     *         response=200,
     *         description="Lista de comensales",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="correo", type="string"),
     *                 @OA\Property(property="telefono", type="string", nullable=true),
     *                 @OA\Property(property="direccion", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * 
     * )
     */

    public function index(Request $request)
    {
        try {

            $filtered = DynamicQueryFilter::apply($request, Comensal::class);

            $data =  $filtered->toArray();

            return Response::response(code: 200, title: 'Listado de Comensales', data: $data,);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/comensales",
     *     summary="Registrar un nuevo comensal",
     *     tags={"Comensales"},
     *     security={{"sanctum":{}}}, 
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del comensal a registrar",
     *         @OA\JsonContent(
     *             required={"nombre", "correo"},
     *             @OA\Property(property="nombre", type="string", example="Fabricio Salazar"),
     *             @OA\Property(property="correo", type="string", format="email", example="fabricio@email.com"),
     *             @OA\Property(property="telefono", type="string", example="987654321", nullable=true),
     *             @OA\Property(property="direccion", type="string", example="Av. Perú 123", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comensal registrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="title", type="string", example="Comensal registrado"),
     *             @OA\Property(property="message", type="string", example="Se creó correctamente el comensal"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="correo", type="string"),
     *                 @OA\Property(property="telefono", type="string", nullable=true),
     *                 @OA\Property(property="direccion", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     * )
     */


    public function store(StoreComensalRequest $request)
    {
        try {
            $data = $request->validated();

            $comensal = Comensal::create($data);

            return Response::response(code: 201, title: 'Comensal registrado', message: 'Se creó correctamente el comensal', data: $comensal->toArray());
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/comensales/{id}",
     *     summary="Obtener detalle de un comensal",
     *     tags={"Comensales"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del comensal (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="34a3f892-8fa1-4d8b-9094-71a7e94e9c2c")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comensal encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Detalle del comensal"),
     *             @OA\Property(property="message", type="string", example="Datos obtenidos correctamente."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="correo", type="string"),
     *                 @OA\Property(property="telefono", type="string", nullable=true),
     *                 @OA\Property(property="direccion", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el comensal",
     *     ),
     * )
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
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/comensales/{id}",
     *     summary="Actualizar un comensal",
     *     tags={"Comensales"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del comensal a actualizar (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="a01f8f16-5481-4c95-89be-59e58e44fa7f")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizables del comensal",
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Fabricio Salazar"),
     *             @OA\Property(property="correo", type="string", format="email", example="fabricio@email.com"),
     *             @OA\Property(property="telefono", type="string", example="987654321", nullable=true),
     *             @OA\Property(property="direccion", type="string", example="Av. Lima 456", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comensal actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Comensal actualizado"),
     *             @OA\Property(property="message", type="string", example="Datos actualizados correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="correo", type="string"),
     *                 @OA\Property(property="telefono", type="string", nullable=true),
     *                 @OA\Property(property="direccion", type="string", nullable=true),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comensal no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado"
     *     )
     * )
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
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/comensales/{id}",
     *     summary="Eliminar (soft delete) un comensal",
     *     tags={"Comensales"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del comensal a eliminar (UUID)",
     *         @OA\Schema(type="string", format="uuid", example="e4d1dcd3-13c4-4cb2-a78d-efbba321812f")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comensal marcado como eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="title", type="string", example="Comensal eliminado"),
     *             @OA\Property(property="message", type="string", example="El comensal ha sido marcado como eliminado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comensal no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado"
     *     )
     * )
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
            return Response::response(code: $e->getCode(), message: $e->getMessage(), functionName: __FUNCTION__);
        }
    }
}
