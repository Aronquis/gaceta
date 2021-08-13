<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class FavoritosArticulosJuridicos
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
     * Funcion para retornar todos los favoritos de articulos juridicos
     */
    public function GetAllFavoritos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        $db_name_categoria=""; 
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_articulos_juridicos";
                $db_name_2="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                break;
            case 2:
                $db_name="favoritos_penal_articulos_juridicos";
                $db_name_2="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                break;
            case 3:
                $db_name="favoritos_constitucional_articulos_juridicos";
                $db_name_2="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                break;
            case 4:
                $db_name="favoritos_juridica_articulos_juridicos";
                $db_name_2="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_articulos_juridicos";
                $db_name_2="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                break;
            case 6:
                $db_name="favoritos_solucion_articulos_juridicos";
                $db_name_2="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
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
        
        // generar rutas para las imagenes
        foreach($favoritos as $favorito){
            
            $favorito->ArticulosJuridicos=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
            $favorito->ArticulosJuridicos=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
            $favorito->ArticulosJuridicos->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$favorito->ArticulosJuridicos->categoria_id)
                                    ->first();

            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->ArticulosJuridicos->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->ArticulosJuridicos)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->ArticulosJuridicos->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->ArticulosJuridicos->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->ArticulosJuridicos->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->ArticulosJuridicos->imagen_rectangular=@$imagen;
            }
        }
        return ['nroTotal_items'=>$favoritos->total(),'data'=>$favoritos];
    }
    public function GetBusquedaFavoritosArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $db_name="";
        $db_name_2="";
        $db_name_categoria=""; 
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_articulos_juridicos";
                $db_name_2="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                break;
            case 2:
                $db_name="favoritos_penal_articulos_juridicos";
                $db_name_2="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                break;
            case 3:
                $db_name="favoritos_constitucional_articulos_juridicos";
                $db_name_2="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                break;
            case 4:
                $db_name="favoritos_juridica_articulos_juridicos";
                $db_name_2="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_articulos_juridicos";
                $db_name_2="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                break;
            case 6:
                $db_name="favoritos_solucion_articulos_juridicos";
                $db_name_2="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
                break;
        }
        // hacer consulta ala base de datos
        $favoritos=DB::table($db_name)
            ->leftjoin($db_name_2,$db_name_2.'.id','=',$db_name.'.producto_id')
            ->where($db_name_2.'.keyword','Like','%'.$args['palabra'].'%')
            ->select($db_name.'.*')
            ->orderBy($db_name.'.created_at', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        // generar rutas para las imagenes
        foreach($favoritos as $favorito){
            $favorito->ArticulosJuridicos=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
            $favorito->ArticulosJuridicos->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$favorito->ArticulosJuridicos->categoria_id)
                                    ->first();
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->ArticulosJuridicos->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->ArticulosJuridicos)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->ArticulosJuridicos->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->ArticulosJuridicos->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->ArticulosJuridicos->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->ArticulosJuridicos->imagen_rectangular=@$imagen;
            }
        }
        return ['nroTotal_items'=>$favoritos->total(),'data'=>$favoritos];
    }
}
