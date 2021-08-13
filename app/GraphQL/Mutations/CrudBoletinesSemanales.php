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
class CrudBoletinesSemanales
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
    * Funcion para crear Boletines Semanales
    */
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Recupear token de inicio de sesion
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
                $db_name="civil_boletines";
                break;
            case 2:
                $db_name="penal_boletines";
                break;
            case 3:
                $db_name="constitucional_boletines";
                break;
            case 4:
                $db_name="juridica_boletines";
                break;
            case 5:
                $db_name="jurisprudencia_boletines";
                break;
            case 6:
                $db_name="solucion_boletines";
                break;
        }
        //Validar el slug repetido
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
        //guardar datos en la db
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'descripcion_corta'=>$args['descripcion_corta'],
            'descripcion_larga'=>$args['descripcion_larga'],
            'keyword'=>$args['keyword'],
            'fecha_inicial'=>$args['fecha_inicial'],
            'fecha_final'=>$args['fecha_final'],
            'imagen_principal'=>$args['imagen_principal'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
            
        ]);
        //recuperar datos guardados
        $boletines_comentados=DB::table($db_name)->get()->last();
        //recueperar imagenes de la galeria
        if(isset($args['imagen_principal'])==true){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletines_comentados->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$boletines_comentados->imagen_principal=@$imagen;
        }
        //retornar datos
        return $boletines_comentados;
    }
    /*
    *Funcion para actualizar datos de boletines semanales
    */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_boletines";
                break;
            case 2:
                $db_name="penal_boletines";
                break;
            case 3:
                $db_name="constitucional_boletines";
                break;
            case 4:
                $db_name="juridica_boletines";
                break;
            case 5:
                $db_name="jurisprudencia_boletines";
                break;
            case 6:
                $db_name="solucion_boletines";
                break;
        }
        // artulizar imagen del open graph
        if(isset($args['imagen_principal'])==true){
            DB::table($db_name)->where('id',$args['id'])->update([
                'imagen_principal'=>$args['imagen_principal'],
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }
        // validar slug repetido
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
        // actualizar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'fecha_inicial'=>$args['fecha_inicial'],
            'fecha_final'=>$args['fecha_final'],
            'descripcion_corta'=>$args['descripcion_corta'],
            'descripcion_larga'=>$args['descripcion_larga'],
            'keyword'=>$args['keyword'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        //recuperar datos
        $boletines_comentados=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletines_comentados->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($boletines_comentados->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$boletines_comentados->imagen_principal=@$imagen;
        }
        //retornar datos
        return $boletines_comentados;
    }
    /*
    * Funcion para eliminar un boletin semanal
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_boletines";
                break;
            case 2:
                $db_name="penal_boletines";
                break;
            case 3:
                $db_name="constitucional_boletines";
                break;
            case 4:
                $db_name="juridica_boletines";
                break;
            case 5:
                $db_name="jurisprudencia_boletines";
                break;
            case 6:
                $db_name="solucion_boletines";
                break;
        }
        // Eliminar boletin semanal de acuerdo al id
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Funcion para guardar numero de vistas en bloetines semanales
    */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_boletines";
                break;
            case 2:
                $db_name="penal_boletines";
                break;
            case 3:
                $db_name="constitucional_boletines";
                break;
            case 4:
                $db_name="juridica_boletines";
                break;
            case 5:
                $db_name="jurisprudencia_boletines";
                break;
            case 6:
                $db_name="solucion_boletines";
                break;
        }
        //Guardar el nro de vistas
        $boletines_comentados=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$boletines_comentados->nroVistas+1
        ]);
        //recuperar datos guardados
        $boletines_comentados=DB::table($db_name)->where('id',$args['id'])->first();

        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletines_comentados->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($boletines_comentados->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$boletines_comentados->imagen_principal=@$imagen;
        }
        //retornar datos
        return $boletines_comentados;
    }
}
