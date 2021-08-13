<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class NotasJurisprudenciasComentadas
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
    public function GetAllNotas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $db_name2="";
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name2="civil_jurisprudencias";
                $db_name="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name2="penal_jurisprudencias";
                $db_name="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name2="constitucional_jurisprudencias";
                $db_name="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name2="juridica_jurisprudencias";
                $db_name="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name2="jurisprudencia_jurisprudencias";
                $db_name="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name2="solucion_jurisprudencias";
                $db_name="notas_solucion_jurisprudencias";
                break;
        }
        $notas_jurisprudencia=DB::table($db_name)
                ->where('pro_id', $args['producto_id'])
                ->orderBy('id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach($notas_jurisprudencia as $notas_jurisprudencias){
            $notas_jurisprudencias->JurisprudenciasComentadas=DB::table($db_name2)->where('id',$notas_jurisprudencias->pro_id)->first();
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_jurisprudencias->JurisprudenciasComentadas->imagen_principal)->first();

            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($notas_jurisprudencias->JurisprudenciasComentadas->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$notas_jurisprudencias->JurisprudenciasComentadas->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_jurisprudencias->JurisprudenciasComentadas->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($notas_jurisprudencias->JurisprudenciasComentadas->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$notas_jurisprudencias->JurisprudenciasComentadas->imagen_rectangular=@$imagen;
            }
        }
        return ['nroTotal_items'=>$notas_jurisprudencia->total(),'data'=>$notas_jurisprudencia];
    }
    public function GetIdNotas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $db_name2="";
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name2="civil_jurisprudencias";
                $db_name="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name2="penal_jurisprudencias";
                $db_name="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name2="constitucional_jurisprudencias";
                $db_name="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name2="juridica_jurisprudencias";
                $db_name="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name2="jurisprudencia_jurisprudencias";
                $db_name="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name2="solucion_jurisprudencias";
                $db_name="notas_solucion_jurisprudencias";
                break;
        }
        $notas_jurisprudencias=DB::table($db_name)->where('id',$args['id'])->first();
        $notas_jurisprudencias->JurisprudenciasComentadas=DB::table($db_name2)->where('id',$notas_jurisprudencias->pro_id)->first();
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_jurisprudencias->JurisprudenciasComentadas->imagen_principal)->first();

        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_jurisprudencias->JurisprudenciasComentadas->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_jurisprudencias->JurisprudenciasComentadas->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_jurisprudencias->JurisprudenciasComentadas->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_jurisprudencias->JurisprudenciasComentadas->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_jurisprudencias->JurisprudenciasComentadas->imagen_rectangular=@$imagen;
        }
        return $notas_jurisprudencias;
    }
}
