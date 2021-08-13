<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class HerramientasBusqueda
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
     * Funcion para retornar todas las herramientas de busqueda
     */
    public function GetAllHerramientasBusqueda($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$HerramientasBusqueda=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$HerramientasBusqueda as $HerramientaBusqueda){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$HerramientaBusqueda->imagen)->first();
            // generar rutas para la imagen
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($HerramientaBusqueda->imagen)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$HerramientaBusqueda->imagen=@$imagen;
            }
        }
        return ['nroTotal_items'=>$HerramientasBusqueda->total(),'data'=>$HerramientasBusqueda];
    }
    /**
     * Funcion para retornar port slug la herramienta de bsuqueda
     */
    public function GetSlugHerramientasBusqueda($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        //hacer consulta ala base de datos
        @$HerramientasBusqueda=DB::table($db_name)
                ->select($db_name.'.*')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        //generar ruta para la imagen
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$HerramientasBusqueda->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($HerramientasBusqueda->imagen)==true){
            
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$HerramientasBusqueda->imagen=@$imagen;
        }
        return $HerramientasBusqueda;
    }
    /**
     * Funcion para retornar por fecha la herramienta de busqueda
     */
    public function GetFechaHerramientasBusqueda($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$HerramientasBusqueda=DB::table($db_name)
            ->select($db_name.'.*')
            ->whereDate($db_name.'.created_at','=',$args['fecha'])
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$HerramientasBusqueda as $HerramientaBusqueda){
            // generar ruta para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$HerramientaBusqueda->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($HerramientaBusqueda->imagen)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                
                @$HerramientaBusqueda->imagen=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$HerramientasBusqueda->total(),'data'=>$HerramientasBusqueda];
    }
    public function GetKeywordsHerramientasBusqueda($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$HerramientasBusqueda=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.keywords','like','%'.$args['keyword'].'%')
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$HerramientasBusqueda as $HerramientaBusqueda){
            // generar ruta para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$HerramientaBusqueda->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($HerramientaBusqueda->imagen)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                
                @$HerramientaBusqueda->imagen=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$HerramientasBusqueda->total(),'data'=>$HerramientasBusqueda];
    }
}
