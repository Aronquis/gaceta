<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class NotasNoticiasInformesOpiniones
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
        $db_name="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="notas_civil_noticias_informes_opiniones";
                $db_name2="civil_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="notas_penal_noticias_informes_opiniones";
                $db_name2="penal_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="notas_constitucional_noticias_informes_opiniones";
                $db_name2="constitucional_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="notas_juridica_noticias_informes_opiniones";
                $db_name2="juridica_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="notas_jurisprudencia_noticias_informes_opiniones";
                $db_name2="jurisprudencia_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="notas_solucion_noticias_informes_opiniones";
                $db_name2="solucion_noticias_informes_opiniones";
                break;
        }
        $notas_noticia=DB::table($db_name)
                ->where('pro_id', $args['producto_id'])
                ->orderBy('id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach($notas_noticia as $notas_noticias){
            $notas_noticias->NoticiasInformesOpiniones=DB::table($db_name2)->where('id',$notas_noticias->pro_id)->first();
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_noticias->NoticiasInformesOpiniones->imagen_principal)->first();

            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($notas_noticias->NoticiasInformesOpiniones->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$notas_noticias->NoticiasInformesOpiniones->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_noticias->NoticiasInformesOpiniones->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($notas_noticias->NoticiasInformesOpiniones->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$notas_noticias->NoticiasInformesOpiniones->imagen_rectangular=@$imagen;
            }
        }
        return ['nroTotal_items'=>$notas_noticia->total(),'data'=>$notas_noticia];
    }
    public function GetIdNotas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $db_name="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="notas_civil_noticias_informes_opiniones";
                $db_name2="civil_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="notas_penal_noticias_informes_opiniones";
                $db_name2="penal_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="notas_constitucional_noticias_informes_opiniones";
                $db_name2="constitucional_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="notas_juridica_noticias_informes_opiniones";
                $db_name2="juridica_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="notas_jurisprudencia_noticias_informes_opiniones";
                $db_name2="jurisprudencia_noticias_informes_opiniones";
                break;
        }
        $notas_noticias=DB::table($db_name)->where('id',$args['id'])->first();
        $notas_noticias->NoticiasInformesOpiniones=DB::table($db_name2)->where('id',$notas_noticias->pro_id)->first();
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_noticias->NoticiasInformesOpiniones->imagen_principal)->first();

        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_noticias->NoticiasInformesOpiniones->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_noticias->NoticiasInformesOpiniones->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_noticias->NoticiasInformesOpiniones->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_noticias->NoticiasInformesOpiniones->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_noticias->NoticiasInformesOpiniones->imagen_rectangular=@$imagen;
        }
        return $notas_noticias;
    }
}
