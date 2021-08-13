<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class SumariosRevistas
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
     * Funcion para retornar todas las sumarios revistas
     */
    public function GetAllSumariosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$SumariosRevistas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$SumariosRevistas as $SumarioRevista){
            // generar las rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$SumarioRevista->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($SumarioRevista->open_graph)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$SumarioRevista->open_graph=@$imagen;
            }
        }
        return ['nroTotal_items'=>$SumariosRevistas->total(),'data'=>$SumariosRevistas];
    }
    /**
     * Funcion para retornar por slug los sumarios revistas
     */
    public function GetSlugSumariosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulata el la base de datos
        @$SumariosRevistas=DB::table($db_name)
                ->select($db_name.'.*')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        // generar rutas para las imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$SumariosRevistas->open_graph)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($SumariosRevistas->open_graph)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$SumariosRevistas->open_graph=@$imagen;
        }
        return $SumariosRevistas;
    }
    /**
     * Funcion para retornar por fecha los sumarios revistas
     */
    public function GetFechaSumariosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$SumariosRevistas=DB::table($db_name)
            ->select($db_name.'.*')
            ->whereDate($db_name.'.created_at','=',$args['fecha'])
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$SumariosRevistas as $SumarioRevista){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$SumarioRevista->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($SumarioRevista->open_graph)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$SumarioRevista->open_graph=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$SumariosRevistas->total(),'data'=>$SumariosRevistas];
    }
    public function GetDescripcionSumariosRevistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$SumariosRevistas=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.descripcion','like','%'.$args['descripcion'].'%')
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$SumariosRevistas as $SumarioRevista){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$SumarioRevista->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($SumarioRevista->open_graph)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$SumarioRevista->open_graph=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$SumariosRevistas->total(),'data'=>$SumariosRevistas];
    }
}
