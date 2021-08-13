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
class CrudCodigosLegislacion
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
    *   Funcion para crear codigos de legislacion
    */
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //Recuperar token de inicio de sesion
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
                $db_name="civil_codigos_lesgilacion";
                break;
            case 2:
                $db_name="penal_codigos_lesgilacion";
                break;
            case 3:
                $db_name="constitucional_codigos_lesgilacion";
                break;
            case 4:
                $db_name="juridica_codigos_lesgilacion";
                break;
            case 5:
                $db_name="jurisprudencia_codigos_lesgilacion";
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
            'slug'=>$slug,
            'imagen'=>$args['imagen'],
            'url'=>$args['url'],
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'user_id'=>$user_id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar dtos
        $coddigos_legislacion=DB::table($db_name)->get()->last();
        if(isset($args['imagen'])==true){
            
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$coddigos_legislacion->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$coddigos_legislacion->imagen=@$imagen;
        }
        return $coddigos_legislacion;
    }
    /*
    *   Funcion para actulizar codigos de legislacion
    */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_codigos_lesgilacion";
                break;
            case 2:
                $db_name="penal_codigos_lesgilacion";
                break;
            case 3:
                $db_name="constitucional_codigos_lesgilacion";
                break;
            case 4:
                $db_name="juridica_codigos_lesgilacion";
                break;
            case 5:
                $db_name="jurisprudencia_codigos_lesgilacion";
                break;
        }
        //actulizar imagen
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
        $coddigos_legislacion=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$coddigos_legislacion->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($coddigos_legislacion->imagen)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$coddigos_legislacion->imagen=@$imagen;
        }
        return $coddigos_legislacion;
    }
    /*
    * Funcion para eliminar codigos de legislacion
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_codigos_lesgilacion";
                break;
            case 2:
                $db_name="penal_codigos_lesgilacion";
                break;
            case 3:
                $db_name="constitucional_codigos_lesgilacion";
                break;
            case 4:
                $db_name="juridica_codigos_lesgilacion";
                break;
            case 5:
                $db_name="jurisprudencia_codigos_lesgilacion";
                break;
        }
        // elminar codigo de legislacion
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    *   Guardar numero de vistas para codigos de legislacion
    */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_codigos_lesgilacion";
                break;
            case 2:
                $db_name="penal_codigos_lesgilacion";
                break;
            case 3:
                $db_name="constitucional_codigos_lesgilacion";
                break;
            case 4:
                $db_name="juridica_codigos_lesgilacion";
                break;
            case 5:
                $db_name="jurisprudencia_codigos_lesgilacion";
                break;
        }
        // guardar datos
        $coddigos_legislacion=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$coddigos_legislacion->nroVistas+1,
        ]);
        // recuperar datos guardados
        $coddigos_legislacion=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$coddigos_legislacion->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($coddigos_legislacion->imagen)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            } 
            @$coddigos_legislacion->imagen=@$imagen;
        }
        return $coddigos_legislacion;
    }
}
