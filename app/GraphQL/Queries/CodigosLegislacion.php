<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CodigosLegislacion
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
     * Funcion para retornar todos codigos de legislacion
     */
    public function GetAllCodigosLegislacion($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$CodigosLegislacion=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        // generar ruta para la imagen
        foreach(@$CodigosLegislacion as $CodigoLegislacion){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$CodigoLegislacion->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($CodigoLegislacion->imagen)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$CodigoLegislacion->imagen=@$imagen;
            }
        }
        return ['nroTotal_items'=>$CodigosLegislacion->total(),'data'=>$CodigosLegislacion];
    }
    /**
     * Funcion parar retornar por slug los codigos de legislacion
     */
    public function GetSlugCodigosLegislacion($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$CodigosLegislacion=DB::table($db_name)
                ->select($db_name.'.*')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        // generar rutas para las imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$CodigosLegislacion->imagen)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($CodigosLegislacion->imagen)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            
            @$CodigosLegislacion->imagen=@$imagen;
        }
        return $CodigosLegislacion;
    }
    /**
     *  Funcion para retornar por fecha los codigos de legislacion
     */
    public function GetFechaCodigosLegislacion($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // Hacer consulta ala base de datos
        @$CodigosLegislacion=DB::table($db_name)
            ->select($db_name.'.*')
            ->whereDate($db_name.'.created_at','=',$args['fecha'])
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$CodigosLegislacion as $CodigoLegislacion){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$CodigoLegislacion->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($CodigoLegislacion->imagen)==true){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                @$CodigoLegislacion->imagen=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$CodigosLegislacion->total(),'data'=>$CodigosLegislacion];
    }
    public function GetKeywordsCodigosLegislacion($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // Hacer consulta ala base de datos
        @$CodigosLegislacion=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.keywords','like','%'.$args['fecha'].'%')
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$CodigosLegislacion as $CodigoLegislacion){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$CodigoLegislacion->imagen)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($CodigoLegislacion->imagen)==true){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                @$CodigoLegislacion->imagen=@$imagen;
            }
        }    
        return ['nroTotal_items'=>$CodigosLegislacion->total(),'data'=>$CodigosLegislacion];
    }
}
