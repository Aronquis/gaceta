<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class NormasLegales
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
     * Funcion para retornar todas las normas legales
     */
    public function GetAllNormasLegales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // hacer consulta ala base de datos
        @$NormasLegales=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$NormasLegales as $HerramientaBusqueda){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$HerramientaBusqueda->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($HerramientaBusqueda->open_graph)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                
                @$HerramientaBusqueda->open_graph=@$imagen;
            }
        }
        return ['nroTotal_items'=>$NormasLegales->total(),'data'=>$NormasLegales];
    }
    /**
     * Funcion para retornar por slug las normas legales
     */
    public function GetSlugNormasLegales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // hacer consulta ala base de datos
        @$NormasLegales=DB::table($db_name)
                ->select($db_name.'.*')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        //generar las rutas para las imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$NormasLegales->open_graph)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($NormasLegales->open_graph)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            
            @$NormasLegales->open_graph=@$imagen;
        }
        return $NormasLegales;
    }
    /**
     * Funcion para retornar por fecha las normas legales
     */
    public function GetFechaNormasLegales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // hacer consulta ala base de datos
        @$NormasLegales=DB::table($db_name)
            ->select($db_name.'.*')
            ->whereDate($db_name.'.fecha','>=', $args['fechaInicial'])
            ->whereDate($db_name.'.fecha','<=', $args['fechaFinal'])
            ->orderBy($db_name.'.id', 'DESC')

            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$NormasLegales as $HerramientaBusqueda){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$HerramientaBusqueda->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($HerramientaBusqueda->open_graph)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                
                
                @$HerramientaBusqueda->open_graph=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$NormasLegales->total(),'data'=>$NormasLegales];
    }
    public function GetKeywordsNormasLegales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // hacer consulta ala base de datos
        @$NormasLegales=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.keywords','like','%'.$args['keyword'].'%')
            ->orderBy($db_name.'.id', 'DESC')

            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$NormasLegales as $HerramientaBusqueda){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$HerramientaBusqueda->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($HerramientaBusqueda->open_graph)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                
                
                @$HerramientaBusqueda->open_graph=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$NormasLegales->total(),'data'=>$NormasLegales];
    }
}
