<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class BoletinesSemanales
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
     * Funcion para retornar todos los boletines semanales
     */
    public function GetAllBoletinesSemanales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        $BoletinesSemanales=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        // asignar rutas alas imagenes
        foreach(@$BoletinesSemanales as $boletinSemanal){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletinSemanal->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($boletinSemanal->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                  @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;   
                }
                @$boletinSemanal->imagen_principal=@$imagen;
            }
        }
        return ['nroTotal_items'=>$BoletinesSemanales->total(),'data'=>$BoletinesSemanales];
    }
    /**
     * Funcion para retornar por slug los boletines semanales
     */
    public function GetSlugBoletinesSemanales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        $BoletinesSemanales=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        // asignar rutas alas imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$BoletinesSemanales->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($BoletinesSemanales->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                  @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;   
                }
            @$BoletinesSemanales->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$BoletinesSemanales->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($BoletinesSemanales->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                  @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;   
                }
            @$BoletinesSemanales->imagen_rectangular=@$imagen;
        }
        return $BoletinesSemanales;
    }
    /**
     * Funcion para retornar por fecha los boletines semanales
     */
    public function GetFechaBoletinesSemanales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        $BoletinesSemanales=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->whereDate($db_name.'.fecha_inicial','=', $args['fecha'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        // asignar rutas alas imagenes
        foreach(@$BoletinesSemanales as $boletinSemanal){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletinSemanal->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($boletinSemanal->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                  @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;   
                }
                @$boletinSemanal->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletinSemanal->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($boletinSemanal->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                  @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;   
                }
                @$boletinSemanal->imagen_rectangular=@$imagen;
            }
        }  
        return ['nroTotal_items'=>$BoletinesSemanales->total(),'data'=>$BoletinesSemanales];
    }
    public function GetKeywordsBoletinesSemanales($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
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
        $BoletinesSemanales=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.keyword','like','%'.$args['keyword'].'%')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        
       
        // asignar rutas alas imagenes
        foreach(@$BoletinesSemanales as $boletinSemanal){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletinSemanal->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($boletinSemanal->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                  @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;   
                }
                @$boletinSemanal->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$boletinSemanal->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($boletinSemanal->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                  @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;   
                }
                @$boletinSemanal->imagen_rectangular=@$imagen;
            }
        }  
        return ['nroTotal_items'=>$BoletinesSemanales->total(),'data'=>$BoletinesSemanales];
    
    }
}
