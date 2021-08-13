<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\User;
use Image;
use App\Services\JWTServices; 
use Firebase\JWT\JWT;
use JWTAuth;
use Illuminate\Support\Str;
class CrudLibrosDigitales
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
    * Funcion para crear libros digitales
    */
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // recuperar token de inicio de sesion
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $email=$payload->iss;
        $typeUser=$payload->iat;
        $user_id=$payload->nbf;
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_libros_digital";
                break;
            case 2:
                $db_name="penal_libros_digital";
                break;
            case 3:
                $db_name="constitucional_libros_digital";
                break;
            case 4:
                $db_name="juridica_libros_digital";
                break;
            case 5:
                $db_name="jurisprudencia_libros_digital";
                break;
            case 6:
                $db_name="solucion_libros_digital";
                break;
            case 7:
                $db_name="normas_libros_digital";
                break;
            case 8:
                $db_name="gestion_libros_digital";
                break;
            case 9:
                $db_name="contadores_libros_digital";
                break;
        }
       
        // guardar datos
        DB::table($db_name)->insert([
            'nombres'=>$args['nombres'],
            'imagen'=>$args['imagen'],
            'url'=>$args['url'],
            'tomo_libro_digital'=>@$args['tomo_libro_digital'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardados
        $libros_digitales=DB::table($db_name)->get()->last();
        if(isset($args['imagen'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$libros_digitales->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
               @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            
            @$libros_digitales->imagen=@$imagen;
        }
        return $libros_digitales;
    }
    /*
    * Funcion para actulizar libros digitales
    */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_libros_digital";
                break;
            case 2:
                $db_name="penal_libros_digital";
                break;
            case 3:
                $db_name="constitucional_libros_digital";
                break;
            case 4:
                $db_name="juridica_libros_digital";
                break;
            case 5:
                $db_name="jurisprudencia_libros_digital";
                break;
            case 6:
                $db_name="solucion_libros_digital";
                break;
            case 7:
                $db_name="normas_libros_digital";
                break;
            case 8:
                $db_name="gestion_libros_digital";
                break;
            case 9:
                $db_name="contadores_libros_digital";
                break;
        }
        // actulizar imagen
        if(isset($args['imagen'])==true){
            DB::table($db_name)->where('id',$args['id'])->update([
                'imagen'=>$args['imagen'],
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }
        // guardar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'nombres'=>$args['nombres'],
            'url'=>$args['url'],
            'tomo_libro_digital'=>@$args['tomo_libro_digital'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardados
        $libros_digitales=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$libros_digitales->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($libros_digitales->imagen)==true){
            if(@$imagen->tipo_imagen==0){
               @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            
            @$libros_digitales->imagen=@$imagen;
        }
        return $libros_digitales;
    }
    /*
    * Funcion para eliminar libros digitales
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_libros_digital";
                break;
            case 2:
                $db_name="penal_libros_digital";
                break;
            case 3:
                $db_name="constitucional_libros_digital";
                break;
            case 4:
                $db_name="juridica_libros_digital";
                break;
            case 5:
                $db_name="jurisprudencia_libros_digital";
                break;
            case 6:
                $db_name="solucion_libros_digital";
                break;
            case 7:
                $db_name="normas_libros_digital";
                break;
            case 8:
                $db_name="gestion_libros_digital";
                break;
            case 9:
                $db_name="contadores_libros_digital";
                break;
        }
        // eliminar libros digitales
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Funcion para agregar numero de vistas a libros digitales
    */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_libros_digital";
                break;
            case 2:
                $db_name="penal_libros_digital";
                break;
            case 3:
                $db_name="constitucional_libros_digital";
                break;
            case 4:
                $db_name="juridica_libros_digital";
                break;
            case 5:
                $db_name="jurisprudencia_libros_digital";
                break;
            case 6:
                $db_name="solucion_libros_digital";
                break;
            case 7:
                $db_name="normas_libros_digital";
                break;
            case 8:
                $db_name="gestion_libros_digital";
                break;
            case 9:
                $db_name="contadores_libros_digital";
                break;
        }
        // guardar datos
        $libros_digitales=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$libros_digitales->nroVistas+1
        ]);
        // recuperar datos guardados
        $libros_digitales=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$libros_digitales->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($libros_digitales->imagen)==true){
            if(@$imagen->tipo_imagen==0){
               @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            
            @$libros_digitales->imagen=@$imagen;
        }
        return $libros_digitales;
    }
}
