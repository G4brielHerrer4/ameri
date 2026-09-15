<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\TipoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request),
            $this->messages()
        );

        if ($validator->fails()) {
            return redirect()
                ->route('admin.suministros.index')
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'productos')
                ->with('open_modal', 'crear-producto');
        }

        $tipo = TipoProducto::findOrFail($request->tipo_producto_id);

        Producto::create([
            'tipo_producto_id' => $tipo->id,
            'nombre'           => $request->nombre,
            'marca'            => $request->marca,
            'modelo'           => $request->modelo,
            'medida'           => $request->medida,
            'piezas_por_caja'  => $request->piezas_por_caja,
            'm2_por_caja'      => $request->m2_por_caja,
            'peso'             => $request->peso,
            'color'            => $request->color,
            'acabado'          => $request->acabado,
            'presentacion'     => $request->presentacion,
        ]);

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Producto creado correctamente.')
            ->with('active_tab', 'productos');
    }


    public function update(Request $request, Producto $producto)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request),
            $this->messages()
        );

        if ($validator->fails()) {
            return redirect()
                ->route('admin.suministros.index')
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'productos')
                ->with('open_modal', 'editar-producto')
                ->with('edit_id', $producto->id);
        }

        $tipo = TipoProducto::findOrFail($request->tipo_producto_id);

        $producto->update([
            'tipo_producto_id' => $tipo->id,
            'nombre'           => $request->nombre,
            'marca'            => $request->marca,
            'modelo'           => $request->modelo,
            'medida'           => $request->medida,
            'piezas_por_caja'  => $request->piezas_por_caja,
            'm2_por_caja'      => $request->m2_por_caja,
            'peso'             => $request->peso,
            'color'            => $request->color,
            'acabado'          => $request->acabado,
            'presentacion'     => $request->presentacion,
        ]);

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Producto actualizado correctamente.')
            ->with('active_tab', 'productos');
    }


    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()
            ->route('admin.suministros.index')
            ->with('success', 'Producto eliminado correctamente.')
            ->with('active_tab', 'productos');
    }


    private function rules(Request $request): array
    {
        $tipo = TipoProducto::find($request->tipo_producto_id);

        $nombreTipo = $tipo
            ? strtolower($tipo->nombre)
            : '';


        /*
        |--------------------------------------------------------------------------
        | Cerámica y Porcelanato
        |--------------------------------------------------------------------------
        */

        if (
            in_array($nombreTipo, [
                'cerámica',
                'porcelanato',
            ])
        ) {
            return [
                'tipo_producto_id' => [
                    'required',
                    'integer',
                    'exists:tipos_productos,id',
                ],

                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                ],

                'marca' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                ],

                'modelo' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'medida' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'piezas_por_caja' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'm2_por_caja' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'peso' => [
                    'nullable',
                    'numeric',
                    'min:0.01',
                ],

                'color' => [
                    'nullable',
                    'string',
                    'max:80',
                ],

                'acabado' => [
                    'nullable',
                    'string',
                    'max:80',
                ],

                'presentacion' => [
                    'required',
                    'string',
                    'max:120',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Cemento Cola
        |--------------------------------------------------------------------------
        */

        if ($nombreTipo === 'cemento cola') {
            return [
                'tipo_producto_id' => [
                    'required',
                    'integer',
                    'exists:tipos_productos,id',
                ],

                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                ],

                'marca' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'color' => [
                    'nullable',
                    'string',
                    'max:80',
                ],

                'peso' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'presentacion' => [
                    'required',
                    'string',
                    'max:120',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Listelo / Randa decorativa / Pastinas
        |--------------------------------------------------------------------------
        */

        if (
            in_array($nombreTipo, [
                'listelo',
                'randa decorativa',
                'pastinas',
            ])
        ) {
            return [
                'tipo_producto_id' => [
                    'required',
                    'integer',
                    'exists:tipos_productos,id',
                ],

                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                ],

                'modelo' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'medida' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'piezas_por_caja' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'color' => [
                    'nullable',
                    'string',
                    'max:80',
                ],

                'presentacion' => [
                    'required',
                    'string',
                    'max:120',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Esquineros de aluminio y goma
        |--------------------------------------------------------------------------
        */

        if (
            in_array($nombreTipo, [
                'esquinero de aluminio',
                'esquinero de goma',
            ])
        ) {
            return [
                'tipo_producto_id' => [
                    'required',
                    'integer',
                    'exists:tipos_productos,id',
                ],

                'nombre' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                ],

                'color' => [
                    'required',
                    'string',
                    'max:80',
                ],

                'medida' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'piezas_por_caja' => [
                    'required',
                    'integer',
                    'min:1',
                ],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Regla general
        |--------------------------------------------------------------------------
        */

        return [
            'tipo_producto_id' => [
                'required',
                'integer',
                'exists:tipos_productos,id',
            ],

            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
        ];
    }


    private function messages(): array
    {
        return [
            'tipo_producto_id.required' =>
                'El tipo de producto es obligatorio.',

            'tipo_producto_id.exists' =>
                'El tipo de producto seleccionado no es válido.',

            'nombre.required' =>
                'El nombre del producto es obligatorio.',

            'nombre.string' =>
                'El nombre debe ser texto.',

            'nombre.min' =>
                'El nombre debe tener al menos 2 caracteres.',

            'nombre.max' =>
                'El nombre no puede superar los 255 caracteres.',

            'marca.required' =>
                'La marca es obligatoria.',

            'marca.string' =>
                'La marca debe ser texto.',

            'marca.min' =>
                'La marca debe tener al menos 2 caracteres.',

            'marca.max' =>
                'La marca no puede superar los 100 caracteres.',

            'modelo.string' =>
                'El modelo debe ser texto.',

            'modelo.max' =>
                'El modelo no puede superar los 100 caracteres.',

            'medida.required' =>
                'La medida es obligatoria.',

            'medida.string' =>
                'La medida debe ser texto.',

            'medida.max' =>
                'La medida no puede superar los 100 caracteres.',

            'piezas_por_caja.required' =>
                'Las piezas por caja son obligatorias.',

            'piezas_por_caja.integer' =>
                'Las piezas por caja deben ser un número entero.',

            'piezas_por_caja.min' =>
                'Las piezas por caja debe ser mayor a 0.',

            'm2_por_caja.required' =>
                'Los m² por caja son obligatorios.',

            'm2_por_caja.numeric' =>
                'Los m² por caja deben ser un número.',

            'm2_por_caja.min' =>
                'Los m² por caja debe ser mayor a 0.',

            'peso.required' =>
                'El peso es obligatorio.',

            'peso.numeric' =>
                'El peso debe ser un número.',

            'peso.min' =>
                'El peso debe ser mayor a 0.',

            'color.required' =>
                'El color es obligatorio.',

            'color.string' =>
                'El color debe ser texto.',

            'color.max' =>
                'El color no puede superar los 80 caracteres.',

            'acabado.string' =>
                'El acabado debe ser texto.',

            'acabado.max' =>
                'El acabado no puede superar los 80 caracteres.',

            'presentacion.required' =>
                'La presentación es obligatoria.',

            'presentacion.string' =>
                'La presentación debe ser texto.',

            'presentacion.max' =>
                'La presentación no puede superar los 120 caracteres.',
        ];
    }
}