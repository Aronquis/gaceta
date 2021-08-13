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
class CrudNormasComentadas
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
    * Funcion para crear normas comentadas
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
                $db_name="civil_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
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
            'descripcion_corta'=>$args['descripcion_corta'],
            'descripcion_larga'=>$args['descripcion_larga'],
            'keyword'=>$args['keyword'],
            'imagen_principal'=>$args['imagen_principal'],
            'imagen_rectangular'=>$args['imagen_rectangular'],
            'fecha'=>$args['fecha'],
            'categoria_id'=>$args['categoria_id'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
            
        ]);
        // recuperar datos guardados
        $normas_comentadas=DB::table($db_name)->get()->last();
        if(isset($args['imagen_principal'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normas_comentadas->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
              @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;      
            }
            
            @$normas_comentadas->imagen_principal=@$imagen;
        }
        if(isset($args['imagen_rectangular'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normas_comentadas->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;    
            }
            
            @$normas_comentadas->imagen_rectangular=@$imagen;
        }
        return $normas_comentadas;
    }
    /*
    * Funcion para actulizar datos de normas comentadas
    */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                break;
        }
        // actulizar imagen principal
        if(isset($args['imagen_principal'])==true){
            DB::table($db_name)->where('id',$args['id'])->update([
                'imagen_principal'=>$args['imagen_principal'],
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }
        // actulizar imagen rectangular
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
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'descripcion_corta'=>$args['descripcion_corta'],
            'descripcion_larga'=>$args['descripcion_larga'],
            'keyword'=>$args['keyword'],
            'fecha'=>$args['fecha'],
            'categoria_id'=>$args['categoria_id'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos dduardados
        $normas_comentadas=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normas_comentadas->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($normas_comentadas->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
               @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            
            @$normas_comentadas->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normas_comentadas->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($normas_comentadas->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
              @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;      
            }
            
            @$normas_comentadas->imagen_rectangular=@$imagen;
        }
        return $normas_comentadas;
    }
    /*
    * Funcion para eliminar normas comentadas
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                break;
        }
        // elimianr normas comentadas
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Guardar numero de vistas de normas comentadas
    */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                break;
        }
        // guardar datos
        $normas_comentadas=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$normas_comentadas->nroVistas+1
        ]);
        // recuperar datos guardados
        $normas_comentadas=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normas_comentadas->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($normas_comentadas->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
              @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;      
            }
            
            @$normas_comentadas->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normas_comentadas->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($normas_comentadas->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
              @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;      
            }
            @$normas_comentadas->imagen_rectangular=@$imagen;
        }
        return $normas_comentadas;
    }
}
