<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CrudFavoritosArticulosJuridicos
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
    * Funcion para crear favoritos de articulos juridicos
    */
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
         // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="favoritos_civil_articulos_juridicos";
                $db_name_2="civil_articulos_juridicos";
                break;
            case 2:
                $db_name="favoritos_penal_articulos_juridicos";
                $db_name_2="penal_articulos_juridicos";
                break;
            case 3:
                $db_name="favoritos_constitucional_articulos_juridicos";
                $db_name_2="constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="favoritos_juridica_articulos_juridicos";
                $db_name_2="juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="favoritos_jurisprudencia_articulos_juridicos";
                $db_name_2="jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="favoritos_solucion_articulos_juridicos";
                $db_name_2="solucion_articulos_juridicos";
                break;
        }
         // verificar si el usuario ya tiene guardado ese favorito
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
            // si el favorito ya fue asignado actulizar fecha de creacion
            DB::table($db_name)->where('id',$favorito->id)->update([
                'created_at'=>date("Y-m-d H:i:s"),
            ]);
            $favorito=DB::table($db_name)->where('id',$favorito->id)->first();
        }
        //retornar datos
        $favorito->ArticulosJuridicos=DB::table($db_name_2)->where('id',$favorito->producto_id)->first();
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
        return $favorito;
    }
    /*
    *Funcion para eliminar favorito de articulo juridico
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // verificar si el usuario ya tiene guardado ese favorito
        $db_name="";
         switch ($args['tipo']) {
             case 1:
                 $db_name="favoritos_civil_articulos_juridicos";
                 break;
             case 2:
                 $db_name="favoritos_penal_articulos_juridicos";
                 break;
             case 3:
                 $db_name="favoritos_constitucional_articulos_juridicos";
                 break;
             case 4:
                 $db_name="favoritos_juridica_articulos_juridicos";
                 break;
             case 5:
                 $db_name="favoritos_jurisprudencia_articulos_juridicos";
                 break;
             case 6:
                $db_name="favoritos_solucion_articulos_juridicos";
               
                break;
         }
        // eliminar articulo juridico
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
