<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Respuestas\Respuestas;
use App\Models\User;
use App\Models\Perfil;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function verificarContrasena(Request $request)
    {
        // Método para verificar contraseña del usuario

        $validator = Validator::make($request->all(), [
            'contrasena' => 'required|string',
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where('id', $request->id)->get();

        if (Hash::check($request->contrasena, $user[0]['password'])) {
            return response()->json(
                Respuestas::respuesta200(
                    'Consulta exitosa.',
                    ['contrasenaCorrecta' => true]
                )
            );
        } else {
            return response()->json(
                Respuestas::respuesta401(
                    [
                        'contrasenaCorrecta' => false,
                        'Mensaje' => 'No coincide la contraseña.'
                    ]
                )
            );
        }
    }

    public function actualizarContrasena(Request $request)
    {
        // Método para actualizar contraseña del usuario

        $validator = Validator::make($request->all(), [
            'contrasenaNueva' => 'required|string',
            'contrasena' => 'required|string',
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $usuario = User::find($request->id);

        if (Hash::check($request->contrasena, $usuario['password'])) {
            $usuario->password = bcrypt($request->input('contrasenaNueva',  $usuario->password));
            $usuario->save();
            return response()->json(Respuestas::respuesta200NoResultados('Se cambio la contraseña.'));
        } else {
            return response()->json(
                Respuestas::respuesta401(
                    [
                        'contrasenaCorrecta' => false,
                        'Mensaje' => 'No coincide la contraseña.'
                    ]
                )
            );
        }
    }

    public function guardarInformacionUsuario(Request $request)
    {
        // Método para guardar la información de un usuario

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'nombres' => ['nullable', 'string'],
            'apellidoPaterno' =>  ['nullable', 'string'],
            'apellidoMaterno' =>  ['nullable', 'string'],
            'fechaNacimiento' =>  ['nullable', 'string'],
            'genero' =>  ['nullable', 'string'],
            'edad' =>  ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,3})?$/'],
            'curp' =>  ['nullable', 'string'],
            'rfc' =>  ['nullable', 'string'],
            'nss' =>  ['nullable', 'string'],
            'numeroTelefonico' => ['nullable', 'numeric', 'digits:10'],
            'numeroEmpleado' =>  ['nullable', 'string'],
            'salario' =>   ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'email' =>  ['nullable', 'string'],
            'puesto' =>  ['nullable', 'string'],
            'area' =>  ['nullable', 'string'],
            'jefeDirecto' =>  ['nullable', 'string'],
            'regimenFiscal' =>  ['nullable', 'string'],
            'calle' =>  ['nullable', 'string'],
            'numeroExterior' =>  ['nullable', 'string'],
            'numeroInterior' =>  ['nullable', 'string'],
            'colonia' =>  ['nullable', 'string'],
            'municipio' =>  ['nullable', 'string'],
            'estado' =>  ['nullable', 'string'],
            'codigoPostal' =>  ['nullable', 'string'],
            'activo' =>  ['nullable', 'boolean'],
            'imagen' =>  ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $perfil = Perfil::find($request->id);


        if ($perfil === null) {
            // Si el perfil no existe, creamos un nuevo registro
            $perfil = new Perfil();
            $perfil->id_usuario = $request->id;
            $perfil->nombres = '';
            $perfil->apellidoPaterno = '';
            $perfil->apellidoMaterno = '';
            $perfil->fechaNacimiento = '';
            $perfil->genero = '';
            $perfil->edad = 0;
            $perfil->curp = '';
            $perfil->rfc = '';
            $perfil->nss = '';
            $perfil->numeroTelefonico = 0;
            $perfil->numeroEmpleado = '';
            $perfil->salario = 0;
            $perfil->email = '';
            $perfil->puesto = '';
            $perfil->area = '';
            $perfil->jefeDirecto = '';
            $perfil->regimenFiscal = '';
            $perfil->calle = '';
            $perfil->numeroExterior = '';
            $perfil->numeroInterior = '';
            $perfil->colonia = '';
            $perfil->municipio = '';
            $perfil->estado = '';
            $perfil->codigoPostal = '';
            $perfil->activo = true;
        } else {
            // Actualizamos los campos que se hayan enviado en la solicitud
            $perfil->nombres = $request->input('nombres', $perfil->nombres);
            $perfil->apellidoPaterno = $request->input('apellidoPaterno', $perfil->apellidoPaterno);
            $perfil->apellidoMaterno = $request->input('apellidoMaterno', $perfil->apellidoMaterno);
            $perfil->fechaNacimiento = $request->input('fechaNacimiento', $perfil->fechaNacimiento);
            $perfil->genero = $request->input('genero', $perfil->genero);
            $perfil->edad = $request->input('edad', $perfil->edad);
            $perfil->curp = $request->input('curp', $perfil->curp);
            $perfil->rfc = $request->input('rfc', $perfil->rfc);
            $perfil->nss = $request->input('nss', $perfil->nss);
            $perfil->numeroTelefonico = $request->input('numeroTelefonico', $perfil->numeroTelefonico);
            $perfil->numeroEmpleado = $request->input('numeroEmpleado', $perfil->numeroEmpleado);
            $perfil->salario = $request->input('salario', $perfil->salario);
            $perfil->email = $request->input('email', $perfil->email);
            $perfil->puesto = $request->input('puesto', $perfil->puesto);
            $perfil->area = $request->input('area', $perfil->area);
            $perfil->jefeDirecto = $request->input('jefeDirecto', $perfil->jefeDirecto);
            $perfil->regimenFiscal = $request->input('regimenFiscal', $perfil->regimenFiscal);
            $perfil->calle = $request->input('calle', $perfil->calle);
            $perfil->numeroExterior = $request->input('numeroExterior', $perfil->numeroExterior);
            $perfil->numeroInterior = $request->input('numeroInterior', $perfil->numeroInterior);
            $perfil->colonia = $request->input('colonia', $perfil->colonia);
            $perfil->municipio = $request->input('municipio', $perfil->municipio);
            $perfil->estado = $request->input('estado', $perfil->estado);
            $perfil->codigoPostal = $request->input('codigoPostal', $perfil->codigoPostal);
            $perfil->activo = $request->input('activo', $perfil->activo);
            $perfil->imagen = $request->input('imagen', $perfil->imagen);
        }

        $perfil->save();

        return response()->json(Respuestas::respuesta200NoResultados('Información guardada.'));
    }

    public function guardarImagen(Request $request)
    {
        // Método para actualizar contraseña del usuario

        $validator = Validator::make($request->all(), [
            'file0' => 'required',
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $archivo = $request->file('file0');
        $UUID = Str::orderedUuid();
        $extension = $archivo->getClientOriginalExtension();
        $request->merge(['imagen' => $UUID . '.' . $extension]);
        $uuidViejo = Perfil::where('id_usuario', $request->id)->get()[0]->imagen;
        
        if ($uuidViejo)
            Storage::delete('usuarios/' . $request->id . '/' . $uuidViejo);

        $archivo->storeAs(
            "/" . $request->id,
            $UUID . '.' . $extension,
            'usuarios'
        );

        $respuestaActualizar = $this->guardarInformacionUsuario($request)->original;

        if ($respuestaActualizar['codigo'] != 200) {
            return response()->json(
                Respuestas::respuesta400('No se guardo imagen en la base de datos.')
            );
        }

        return response()->json(Respuestas::respuesta200NoResultados('Archivo guardado.'));
    }
}
