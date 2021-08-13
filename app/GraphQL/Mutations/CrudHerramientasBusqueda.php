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
class CrudHerramientasBusqueda
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
    * Funcion para crear herramientas de busqueda
    */
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // recuperar el token de inicio de sesion
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
                $db_name="civil_herramientas_busqueda";
                break;
            case 2:
                $db_name="penal_herramientas_busqueda";
                break;
            case 3:
                $db_name="constitucional_herramientas_busqueda";
                break;
            case 4:
                $db_name="juridica_herramientas_busqueda";
                break;
            case 5:
                $db_name="jurisprudencia_herramientas_busqueda";
                break;
        }
        //validar slug
        $slug=Str::slug($args['nombres']);
        $j=1;
        $buscar_slug=DB::table($db_name)
                ->where('slug',Str::slug($args['nombres']))->first();
        while(isset($buscar_slug->id)==true){
            $slug=Str::slug($args['nombres']).'-'.$j;
            $buscar_slug=DB::table($db_name)
                            ->where('slug',$slug)->first();
            $j+=1;
        }
        // guardar datos
        DB::table($db_name)->insert([
            'nombres'=>$args['nombres'],
            'imagen'=>$args['imagen'],
            'url'=>$args['url'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'user_id'=>$user_id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardado
        $herramientas_busqueda=DB::table($db_name)->get()->last();
        if(isset($args['imagen'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$herramientas_busqueda->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$herramientas_busqueda->imagen=@$imagen;
        }
        return $herramientas_busqueda;
    }
    /*
    *  Funcion para actulizar herramientas de busqueda
    */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_herramientas_busqueda";
                break;
            case 2:
                $db_name="penal_herramientas_busqueda";
                break;
            case 3:
                $db_name="constitucional_herramientas_busqueda";
                break;
            case 4:
                $db_name="juridica_herramientas_busqueda";
                break;
            case 5:
                $db_name="jurisprudencia_herramientas_busqueda";
                break;
        }
        // actulizar imagen
        if(isset($args['imagen'])==true){
            DB::table($db_name)->where('id',$args['id'])->update([
                'imagen'=>$args['imagen'],
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }
        // validar slug
        $slug=Str::slug($args['nombres']);
        $j=1;
        $buscar_slug=DB::table($db_name)
                ->where('slug',Str::slug($args['nombres']))->first();
        while(isset($buscar_slug->id)==true){
            $slug=Str::slug($args['nombres']).'-'.$j;
            $buscar_slug=DB::table($db_name)
                            ->where('slug',$slug)->first();
            $j+=1;
        }
        // guardar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'nombres'=>$args['nombres'],
            'slug'=>$slug,
            'url'=>$args['url'],
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardados
        $herramientas_busqueda=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$herramientas_busqueda->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($herramientas_busqueda->imagen)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$herramientas_busqueda->imagen=@$imagen;
        }

        return $herramientas_busqueda;
    }
    /*
    * Funcion para eliminar herramienta de busqueda
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_herramientas_busqueda";
                break;
            case 2:
                $db_name="penal_herramientas_busqueda";
                break;
            case 3:
                $db_name="constitucional_herramientas_busqueda";
                break;
            case 4:
                $db_name="juridica_herramientas_busqueda";
                break;
            case 5:
                $db_name="jurisprudencia_herramientas_busqueda";
                break;
        }
        // eliminar herramienta de busqueda
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Funcion para guardar el numero de vistas para herramientas de busqueda
    */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_herramientas_busqueda";
                break;
            case 2:
                $db_name="penal_herramientas_busqueda";
                break;
            case 3:
                $db_name="constitucional_herramientas_busqueda";
                break;
            case 4:
                $db_name="juridica_herramientas_busqueda";
                break;
            case 5:
                $db_name="jurisprudencia_herramientas_busqueda";
                break;
        }
        // guardar datos
        $herramientas_busqueda=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$herramientas_busqueda->nroVistas+1,
        ]);
        // recuperar datos guardados
        $herramientas_busqueda=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$herramientas_busqueda->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($herramientas_busqueda->imagen)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$herramientas_busqueda->imagen=@$imagen;
        }

        return $herramientas_busqueda;
    }
}
