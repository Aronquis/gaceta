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
class CrudJurisprudenciasComentadas
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
    * Crear jurisprudencias comentadas
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
                $db_name="civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
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
        $recup_editor=DB::table('emisores')->where('emisorId', $args['emisorId'])->first();
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'descripcion_corta'=>$args['descripcion_corta'],
            'descripcion_larga'=>$args['descripcion_larga'],
            'keyword'=>$args['keyword'],
            'imagen_principal'=>$args['imagen_principal'],
            'imagen_rectangular'=>$args['imagen_rectangular'],
            'fecha'=>$args['fecha'],
            'categoria_id'=>$args['categoria_id'],
            'textoAudio'=>$args['textoAudio'],
            'slugEmisor'=>$recup_editor->slugEmisor,
            'emisorId'=>$args['emisorId'],
            'nroResolucion'=>@$args['nroResolucion'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
            
        ]);
        // recuperar datos guardados
        $jurisprudencias=DB::table($db_name)->get()->last();
        if(isset($args['imagen_principal'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$jurisprudencias->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$jurisprudencias->imagen_principal=@$imagen;
        }
        if(isset($args['imagen_rectangular'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$jurisprudencias->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$jurisprudencias->imagen_rectangular=@$imagen;
        }
        return $jurisprudencias;
    }
    /*
    * Funcion para actualizar datos de jurisprudencias comentadas
    */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                break;
        }
        // actulizar imagene principal
        if(isset($args['imagen_principal'])==true){
            DB::table($db_name)->where('id',$args['id'])->update([
                'imagen_principal'=>$args['imagen_principal'],
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }
        // actualizar iamgenes rectangular
        if(isset($args['imagen_rectangular'])==true){
            DB::table($db_name)->where('id',$args['id'])->update([
                'imagen_rectangular'=>$args['imagen_rectangular'],
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
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
        $recup_editor=DB::table('emisores')->where('emisorId', $args['emisorId'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'descripcion_corta'=>$args['descripcion_corta'],
            'descripcion_larga'=>$args['descripcion_larga'],
            'keyword'=>$args['keyword'],
            'textoAudio'=>$args['textoAudio'],
            'slugEmisor'=>$recup_editor->slugEmisor,
            'nroResolucion'=>@$args['nroResolucion'],
            'emisorId'=>$args['emisorId'],
            'categoria_id'=>$args['categoria_id'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardados
        $jurisprudencias=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$jurisprudencias->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($jurisprudencias->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$jurisprudencias->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$jurisprudencias->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($jurisprudencias->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$jurisprudencias->imagen_rectangular=@$imagen;
        }
        return $jurisprudencias;
    }
    /*
    * Funcion para eliminar jurisprudencias comentadas
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                break;
        }
        // eliminar jursiprudencia comentada
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Funcion para agregar numero de vistas en jurisprudencias comentadas
    */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                break;
        }
        // guardar datos
        $jurisprudencias=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$jurisprudencias->nroVistas+1,
            
        ]);
        // recuperar datos guardados
        $jurisprudencias=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$jurisprudencias->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($jurisprudencias->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$jurisprudencias->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$jurisprudencias->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($jurisprudencias->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$jurisprudencias->imagen_rectangular=@$imagen;
        }
        return $jurisprudencias;
    }
}
