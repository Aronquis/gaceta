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
class CrudSumariosRevistas
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
    * Funcion para crear sumarios revistas
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
                $db_name="civil_sumarios_revistas";
                break;
            case 2:
                $db_name="penal_sumarios_revistas";
                break;
            case 3:
                $db_name="constitucional_sumarios_revistas";
                break;
            case 4:
                $db_name="juridica_sumarios_revistas";
                break;
            case 5:
                $db_name="jurisprudencia_sumarios_revistas";
                break;
            case 6:
                $db_name="solucion_sumarios_revistas";
                break;
        }
        // validar slug
        $slug=Str::slug($args['titulo']);
        $j=1;
        $buscar_slug=DB::table($db_name)
                ->where('slug',Str::slug($args['titulo']))->first();
        while(isset($buscar_slug->id)==true){
            $slug=Str::slug($args['titulo']).'-'.$j;
            $buscar_slug=DB::table($db_name)
                            ->where('slug',$slug)->first();
            $j+=1;
        }
        // guardar datos
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'fecha'=>$args['fecha'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // retornar datos guardados
        $sumarios_revistas=DB::table($db_name)->get()->last();
        if(isset($args['open_graph'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$sumarios_revistas->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
               @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            
            @$sumarios_revistas->open_graph=@$imagen;
        }
        return $sumarios_revistas;

    }
    /*
     * Funcion para actulizar sumarios recistas 
     */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_sumarios_revistas";
                break;
            case 2:
                $db_name="penal_sumarios_revistas";
                break;
            case 3:
                $db_name="constitucional_sumarios_revistas";
                break;
            case 4:
                $db_name="juridica_sumarios_revistas";
                break;
            case 5:
                $db_name="jurisprudencia_sumarios_revistas";
                break;
            case 6:
                $db_name="solucion_sumarios_revistas";
                break;
        }
        
        // validar slug
        $slug=Str::slug($args['titulo']);
        $j=1;
        $buscar_slug=DB::table($db_name)
                ->where('slug',Str::slug($args['titulo']))->first();
        while(isset($buscar_slug->id)==true){
            $slug=Str::slug($args['titulo']).'-'.$j;
            $buscar_slug=DB::table($db_name)
                            ->where('slug',$slug)->first();
            $j+=1;
        }
        // guardar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'fecha'=>$args['fecha'],
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardados
        $sumarios_revistas=DB::table($db_name)->where('id',$args['id'])->first();
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$sumarios_revistas->open_graph)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($sumarios_revistas->open_graph)==true){
            if(@$imagen->tipo_imagen==0){
               @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            
            @$sumarios_revistas->open_graph=@$imagen;
        }
        return $sumarios_revistas;
    }
    /*
     * Funcion para eliminar sumarios recistas 
     */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_sumarios_revistas";
                break;
            case 2:
                $db_name="penal_sumarios_revistas";
                break;
            case 3:
                $db_name="constitucional_sumarios_revistas";
                break;
            case 4:
                $db_name="juridica_sumarios_revistas";
                break;
            case 5:
                $db_name="jurisprudencia_sumarios_revistas";
                break;
            case 6:
                $db_name="solucion_sumarios_revistas";
                break;
        }
        // eliminar sumarios revistas
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Funcion para agregar numero de vistas en sumarios revistas
     */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_sumarios_revistas";
                break;
            case 2:
                $db_name="penal_sumarios_revistas";
                break;
            case 3:
                $db_name="constitucional_sumarios_revistas";
                break;
            case 4:
                $db_name="juridica_sumarios_revistas";
                break;
            case 5:
                $db_name="jurisprudencia_sumarios_revistas";
                break;
            case 6:
                $db_name="solucion_sumarios_revistas";
                break;
        }
        // guardar datos
        $sumarios_revistas=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$sumarios_revistas->nroVistas+1
        ]);
        // recuperar datos guardados
        $sumarios_revistas=DB::table($db_name)->where('id',$args['id'])->first();
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$sumarios_revistas->open_graph)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($sumarios_revistas->open_graph)==true){
            if(@$imagen->tipo_imagen==0){
               @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$sumarios_revistas->open_graph=@$imagen;
        }
        return $sumarios_revistas;
    }
}
