<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class FavoritosBoletines
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
     * Funcion para retornar todos los favoritos para jurisprudencias comentadas
     */
    public function GetAllFavoritos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_boletines";
                $db_name_2="civil_boletines";
                break;
            case 2:
                $db_name="favoritos_penal_boletines";
                $db_name_2="penal_boletines";
                break;
            case 3:
                $db_name="favoritos_constitucional_boletines";
                $db_name_2="constitucional_boletines";
                break;
            case 4:
                $db_name="favoritos_juridica_boletines";
                $db_name_2="juridica_boletines";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_boletines";
                $db_name_2="jurisprudencia_boletines";
                break;
        }
        // hacer consulta ala base de datos
        $favoritos=null;
        if($args['id_key']!=""){
            $favoritos=DB::table($db_name)
                    ->where($db_name.'.id_key',@$args['id_key'])
                    ->orderBy($db_name.'.created_at', 'DESC')
                    ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        else{
            $favoritos=DB::table($db_name)
                    ->orderBy($db_name.'.created_at', 'DESC')
                    ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        foreach($favoritos as $favorito){
            $favorito->BoletinesSemanales=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->BoletinesSemanales->open_graph)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->BoletinesSemanales)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->BoletinesSemanales->open_graph=@$imagen;
            }
        }
        return ['nroTotal_items'=>$favoritos->total(),'data'=>$favoritos];
    }
    public function GetBusquedaFavoritosJurisprudenciasJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    { 
        $db_name="";
        $db_name_2="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_juris_comentadas";
                $db_name_categoria="civil_categoria_jurisprudencias";
                $db_name_2="civil_jurisprudencias";
                break;
            case 2:
                $db_name="favoritos_penal_juris_comentadas";
                $db_name_categoria="penal_categoria_jurisprudencias";
                $db_name_2="penal_jurisprudencias";
                break;
            case 3:
                $db_name="favoritos_constitucional_juris_comentadas";
                $db_name_categoria="constitucional_categoria_jurisprudencias";
                $db_name_2="constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="favoritos_juridica_juris_comentadas";
                $db_name_2="juridica_jurisprudencias";
                $db_name_categoria="juridica_categoria_jurisprudencias";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_juris_comentadas";
                $db_name_categoria="jurisprudencia_categoria_jurisprudencias";
                $db_name_2="jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="favoritos_solucion_juris_comentadas";
                $db_name_categoria="solucion_categoria_jurisprudencias";
                $db_name_2="solucion_jurisprudencias";
                break;
        }
        $favoritos=DB::table($db_name)
            ->leftjoin($db_name_2,$db_name_2.'.id','=',$db_name.'.producto_id')
            ->where($db_name_2.'.keyword','Like','%'.$args['palabra'].'%')
            ->select($db_name.'.*')
            ->orderBy($db_name.'.created_at', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach($favoritos as $favorito){
            
            $favorito->JurisprudenciasComentadas=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
            $favorito->JurisprudenciasComentadas->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$favorito->JurisprudenciasComentadas->categoria_id)
                                    ->first();
            //generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->JurisprudenciasComentadas->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->JurisprudenciasComentadas->imagen_principal)==true){
                
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->JurisprudenciasComentadas->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->JurisprudenciasComentadas->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->JurisprudenciasComentadas->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->JurisprudenciasComentadas->imagen_rectangular=@$imagen;
            }
        }
        return ['nroTotal_items'=>$favoritos->total(),'data'=>$favoritos];
    }
}
