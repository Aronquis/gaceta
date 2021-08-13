<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class LibrosDigitales
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
    /**
     * Funcion para retornar todas los libros revistas
     */
    public function GetAllLibrosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        $libros_digitales=DB::table($db_name)
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);

        foreach(@$libros_digitales as $libros_digitale){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$libros_digitale->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($libros_digitale->imagen)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$libros_digitale->imagen=@$imagen;
            }
        }
        return ['nroTotal_items'=>$libros_digitales->total(),'data'=>$libros_digitales];
    }
    /**
     * Funcion para retornar por slug libros revistas
     */
    public function GetSlugLibrosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$libros_digitales=DB::table($db_name)
                ->select($db_name.'.*')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        //generar rutas para las imagenes
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
    /**
     * Funcion para retornar por fechas libros revistas
     */
    public function GetFechaLibrosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        //Hacer consulta ala base de datos
        @$libros_digitales=DB::table($db_name)
            ->select($db_name.'.*')
            ->whereDate($db_name.'.created_at','=',$args['fecha'])
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$libros_digitales as $libros_digitale){
            //generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$libros_digitale->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($libros_digitale->imagen)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                
                @$libros_digitale->imagen=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$libros_digitales->total(),'data'=>$libros_digitales];
    }
    public function GetKeywordLibrosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        //Hacer consulta ala base de datos
        @$libros_digitales=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.keywords','like','%'.$args['keyword'].'%')
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$libros_digitales as $libros_digitale){
            //generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$libros_digitale->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($libros_digitale->imagen)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                
                @$libros_digitale->imagen=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$libros_digitales->total(),'data'=>$libros_digitales];
    }
}
