<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\User;
use Image;
use Hash;
use App\Services\JWTServices;
use Firebase\JWT\JWT;
use JWTAuth;
class AuthMutator
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    /*
    * Funcion para iniciar sesion el usuario suscrito
    */
    public function LoginUserSuscrito($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //Codigo
        $codigo="";
        switch ($args['ip_producto']) {
            //Tipos de usuarios
            case '1':
                $codigo="Admpubus3";
                break;
            case '2':
                $codigo="Easylawpenal";
                break;
            case '3':
                $codigo="GaceConsti3";
                break;
            case '4':
                $codigo="uGajJuridica";
                break;
            case '5':
                $codigo="usrELJuris";
                break;
            
        }
        // buscar en la tabla
        $user_input=DB::table('usuarios_intranet')->where('PIN',$args['PIN'])->where('TipoUsuario',$codigo)->first();
        if(isset($user_input->ID)==true){
            //Generar Token de sesion
            $payload = array(
                "iss" => $args['PIN'],
                "aud" => $codigo,
                "iat" => $user_input->username,
                "nbf" => $user_input->ID
            );
            $token = JWT::encode($payload, '');
            @$user_input->mykey=$token;
            //retornar el user
            return @$user_input;
        }
        else{
            //retornar vacio por algun algun error
            return [
                'PIN'=>"ERROR",
                'email'=>"ERROR",
                'TipoUsuario'=>"ERROR"
            ];
        }
    }
    /*
    * Funcion para generar el inicio de sesion
    */
    public function Login($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //recuperar credenciales de email y password
        @$user=User::where('email',$args['email'])->first();
        if(isset($user->id)==true){
            $credentials = Arr::only($args, ['email', 'password']);
            
            if (Auth::once($credentials)) {
                ////
                $user_aux=$user;
                $payload = array(
                    "iss" => $args['email'],
                    "aud" => $args['password'],
                    "iat" => $user_aux->typeUser,
                    "nbf" => $user_aux->id
                );
                $token = JWT::encode($payload, '');
                $usuario = auth()->user();
                $usuario->api_token = $token;
                $usuario->save();

                @$year=date("Y",strtotime($usuario->created_at));
                @$mes=date("m", strtotime($usuario->created_at));
                if($usuario->photo!=""){
                    @$usuario->photo=env('APP_URL').'UserImagenes/'.$year.'-'.$mes.'/'.$usuario->photo;
                }
                return $usuario;
            }
            else{
                throw new \Exception('CONTRASEÑA_INCORRECTA');
            }
            
        }
        else{
            throw new \Exception('NO_EXISTE');
        }
    }
    /*
    * Funcion para crear usuarios para la gaceto segun los productos disponibles
    */
    public function CrearUsuario($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //validar que email no sea repetido
        @$user_aux=User::where('email',$args['email'])->first();
        if(isset($user_aux->id)==false){
            //guardar imagen y recuperar nombre de la imagen
            $nameCreate="";
            if (isset($args['photo'])) { 
                $image = $args['photo'];
                $arrNameFile = explode(".", $args['photo']->getClientOriginalName());
                $extension = $arrNameFile[sizeof($arrNameFile) - 1];
                $fileName = str_replace(["(",")",' '],'',$arrNameFile[0]);
               
                ///
                //////////////validar nonbres
                $nombre=DB::table('users')->where('photo',$fileName. '.' .$extension)->first();
                $fileNameNuevo=$fileName;
                $i=1;
                
                while (isset($nombre->id)==true) {
                    $nombre=DB::table('users')->where('photo',$fileName.'-'.$i.'.'.$extension)->first();
                    $fileNameNuevo=$fileName.'-'.$i;
                    
                    $i+=1;
                    
                }
                /////////////////////////////////
                //
                $nameCreate =$fileNameNuevo. '.' .$extension;
                $image->storeAs('UserImagenes/'.date("Y").'-'.date("m"), $nameCreate, 'local'); 
            }   
            //guarddar datos del usuario
            $id_user=DB::table('users')->insertGetId([
                'typeUser'=>@$args['typeUser'],
                'tipoGaceta'=>@$args['tipoGaceta'],
                'typeDocument'=>@$args['typeDocument'],
                'numberDocument'=>@$args['numberDocument'],
                'name'=>@$args['name'],
                'surnames'=>@$args['surnames'],
                'fecha_nacimiento'=>@$args['fecha_nacimiento'],
                'email'=>@$args['email'],
                'photo'=>$nameCreate,
                'password'=>Hash::make($args['password']),
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
            $payload = array(
                "iss" => $args['email'],
                "aud" => $args['password'],
                "iat" => @$args['typeUser'],
                "nbf" => $id_user
            );
            $token = JWT::encode($payload, '');
            User::where('id',$id_user)->update(['api_token' => $token]);
            $usuario=DB::table('users')->where('id',$id_user)->first();
            @$year=date("Y",strtotime($usuario->created_at));
            @$mes=date("m", strtotime($usuario->created_at));
            if($usuario->photo!=""){
                @$usuario->photo=env('APP_URL').'UserImagenes/'.$year.'-'.$mes.'/'.$usuario->photo;
            }
            return $usuario;
        }
        else{
            throw new \Exception('USUARIO_YA_EXISTE');
        }
    }
    /*
    * Funcion para actualizar datos del ususario
    */
    public function UpdateUsuario($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //actualizar datos del usuario
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $email=$payload->iss;
        $typeUser=$payload->iat;
        $user_id=$payload->nbf;
        @$user=User::where('email',$email)->first();
        //actualizar imagen del usuario
        if(isset($user->id)==true){
            if (isset($args['photo'])) { 
                $image = $args['photo'];
                $arrNameFile = explode(".", $args['photo']->getClientOriginalName());
                $extension = $arrNameFile[sizeof($arrNameFile) - 1];
                $fileName = str_replace(["(",")",' '],'',$arrNameFile[0]);
                
                 //////
                $usuario=DB::table('users')->where('id',$user->id)->first();
                @$year=date("Y",strtotime($usuario->created_at));
                @$mes=date("m", strtotime($usuario->created_at));
                //////////////validar nonbres
                $nombre=DB::table('users')->where('photo',$fileName. '.' .$extension)->first();
                $fileNameNuevo=$fileName;
                $i=1;
                
                while (isset($nombre->id)==true) {
                    $nombre=DB::table('users')->where('photo',$fileName.'-'.$i.'.'.$extension)->first();
                    $fileNameNuevo=$fileName.'-'.$i;
                    $i+=1;
                    

                }
                
                /////////////////////////////////
                $nameCreate =$fileNameNuevo. '.' .$extension;
                $image->storeAs('UserImagenes/'.$year.'-'.$mes, $nameCreate, 'local');
                
                DB::table('users')->where('id',$user->id)->update([
                    'photo'=>$nameCreate,
                    'updated_at'=>date("Y-m-d H:i:s")
                ]);
            }
            else{
                //actualizar datos del usuario
                if(isset($args['password'])==true){
                    DB::table('users')->where('id',$user->id)->update([
                        'typeDocument'=>@$args['typeDocument'],
                        'numberDocument'=>@$args['numberDocument'],
                        'name'=>@$args['name'],
                        'surnames'=>@$args['surnames'],
                        'fecha_nacimiento'=>@$args['fecha_nacimiento'],
                        'password'=>Hash::make($args['password']),
                        'updated_at'=>date("Y-m-d H:i:s")
                    ]);
                }
                else{
                    DB::table('users')->where('id',$user->id)->update([
                        'typeDocument'=>@$args['typeDocument'],
                        'numberDocument'=>@$args['numberDocument'],
                        'name'=>@$args['name'],
                        'surnames'=>@$args['surnames'],
                        'fecha_nacimiento'=>@$args['fecha_nacimiento'],
                        'updated_at'=>date("Y-m-d H:i:s")
                    ]);
                }
                
                $usuario=DB::table('users')->where('id',$user->id)->first();
                @$year=date("Y",strtotime($usuario->created_at));
                @$mes=date("m", strtotime($usuario->created_at));
                if($usuario->photo!=""){
                    @$usuario->photo=env('APP_URL').'UserImagenes/'.$year.'-'.$mes.'/'.$usuario->photo;
                }
                return $usuario;
            }
        }
        
        else{
            throw new \Exception('NO_EXISTE');
        }
    }
    public function CambiarContrasena($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $args['email']=$payload->iss;
        $typeUser=$payload->iat;
        $user_id=$payload->nbf;

        @$user=User::where('email',$args['email'])->first();
        $args['password']=$args['password_antiguo'];
        
        $credentials = Arr::only($args, ['email', 'password']);
        if (Auth::once($credentials)) {
            
            DB::table('users')->where('id',$user->id)->update([
                'password'=>Hash::make($args['password_nuevo']),
            ]);
            $payload = array(
                "iss" => $args['email'],
                "aud" => $args['password_nuevo'],
                "iat" => $user->typeUser,
                "nbf" => $user->id
            );
            $token = JWT::encode($payload, '');
            $usuario = auth()->user();
            $usuario->api_token = $token;
            $usuario->save();

            $usuario=DB::table('users')->where('id',$user->id)->first();
            @$year=date("Y",strtotime($usuario->created_at));
            @$mes=date("m", strtotime($usuario->created_at));
            if($usuario->photo!=""){
                @$usuario->photo=env('APP_URL').'UserImagenes/'.$year.'-'.$mes.'/'.$usuario->photo;
            }
            return $usuario;
        }
        else{
            throw new \Exception('CONTRASEÑA_INCORRECTA');  
        }
        
    }
    /*
    * Funcion para eliminar usuarios
    */
    public function RecuperarContrasena($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-)(.:,;";
        $su = strlen($an) - 1;
        $password=substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1);

        $user=DB::table('users')->where('email',$args['email'])->first();
        if(isset($user->id)==true){
            
            DB::table('users')->where('email',$args['email'])->update([
                'password'=>Hash::make($password),
            ]);
            $user=DB::table('users')->where('email',$args['email'])->first();
            $email=$args['email'];
            /*
            Mail::send('Users.RecuperarContraseña',['user'=>$user,'password'=>$password], function($message) use ($email) {
                $message->to($email)->subject
                   ('Recuperar Password');
                $message->from('info@kirasportswear.com');
            });*/
            return $user;
        }
        else{
            throw new \Exception('NO_EXISTE');
        }
    }
    public function DeleteUsuario($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        DB::table('users')->where('id',$args['id'])->delete();
        return "Exito";
    }
}
