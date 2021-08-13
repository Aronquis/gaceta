<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CrudFavoritosJurisprudenciasComentadas
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
    /*
    *Funcion para crear favoritos jurisprudencias comentadas
    */
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_juris_comentadas";
                $db_name_2="civil_jurisprudencias";
                break;
            case 2:
                $db_name="favoritos_penal_juris_comentadas";
                $db_name_2="penal_jurisprudencias";
                break;
            case 3:
                $db_name="favoritos_constitucional_juris_comentadas";
                $db_name_2="constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="favoritos_juridica_juris_comentadas";
                $db_name_2="juridica_jurisprudencias";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_juris_comentadas";
                $db_name_2="jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="favoritos_solucion_juris_comentadas";
                $db_name_2="solucion_jurisprudencias";
                break;
        }
        // verificar que el usuario ya tiene guardado ese favorito
        @$favorito=DB::table($db_name)->where('producto_id',$args['producto_id'])->where('id_key',$args['id_key'])->first();
        if(isset($favorito->id)==false){
            // guardar datos
            $id=DB::table($db_name)->insertGetId([
                'id_key'=>$args['id_key'],
                'producto_id'=>$args['producto_id'],
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
            $favorito=DB::table($db_name)->where('id',$id)->first();
        }
        else{
            //si existe modifcar la fecha de creacion
            DB::table($db_name)->where('id',$favorito->id)->update([
                'created_at'=>date("Y-m-d H:i:s"),
            ]);
            $favorito=DB::table($db_name)->where('id',$favorito->id)->first();
        }
        // retornar datos guardados
        $favorito->JurisprudenciasComentadas=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
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
        return $favorito;
    }
    /*
    * Funcion para eliminar favoritos jurisprudencias comentadas
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_juris_comentadas";
                break;
            case 2:
                $db_name="favoritos_penal_juris_comentadas";
                break;
            case 3:
                $db_name="favoritos_constitucional_juris_comentadas";
                break;
            case 4:
                $db_name="favoritos_juridica_juris_comentadas";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_juris_comentadas";
                break;
            case 6:
                $db_name="favoritos_solucion_juris_comentadas";
                break;
        }
        // eliminar jurisprudencias comentadas
        $rec_fav=DB::table($db_name)->where('id',$args['id'])->where('id_key',$args['id_key'])->first();
        if(isset($rec_fav->id)==true){
            DB::table($db_name)->where('id',$args['id'])->delete();
            return "ELIMINADO";
        }
        else{
            return "ERROR";
        }
    }
}
