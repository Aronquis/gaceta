<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class FavoritosNormasComentadas
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
     * Funcion para retornar todos los favoritos para normas comentadas
     */
    public function GetAllFavoritos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_normas_comentadas";
                $db_name_2="civil_normas_comentadas";
                $db_name_categoria="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="favoritos_penal_normas_comentadas";
                $db_name_2="penal_normas_comentadas";
                $db_name_categoria="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="favoritos_constitucional_normas_comentadas";
                $db_name_2="constitucional_normas_comentadas";
                $db_name_categoria="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="favoritos_juridica_normas_comentadas";
                $db_name_2="juridica_normas_comentadas";
                $db_name_categoria="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_normas_comentadas";
                $db_name_2="jurisprudencia_normas_comentadas";
                $db_name_categoria="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="favoritos_solucion_normas_comentadas";
                $db_name_2="solucion_normas_comentadas";
                $db_name_categoria="solucion_categoria_normas_comentadas";
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
            
            $favorito->NormasComentadas=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
            $favorito->NormasComentadas->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$favorito->NormasComentadas->categoria_id)
                                    ->first();

            $favorito->NormasComentadas->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$favorito->NormasComentadas->categoria_id)
                                    ->first();
            //generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->NormasComentadas->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->NormasComentadas->imagen_principal)==true){
                
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->NormasComentadas->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$favorito->NormasComentadas->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($favorito->NormasComentadas->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                @$favorito->NormasComentadas->imagen_rectangular=@$imagen;
            }
        }
        return ['nroTotal_items'=>$favoritos->total(),'data'=>$favoritos];
    }
} 
