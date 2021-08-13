<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CrudFavoritosNoticiasArticulosJuridicos
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
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
         // TODO implement the resolver
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
         @$favoritos=DB::table($db_name)->where('producto_id',$args['producto_id'])->where('user_id',$args['user_id'])->first();
        if(isset($favoritos->id)==false){
            $id=DB::table($db_name)->insertGetId([
                'user_id'=>$args['user_id'],
                'producto_id'=>$args['producto_id'],
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
            $favoritos=DB::table($db_name)->where('id',$id)->first();
        }
        else{
            DB::table($db_name)->where('id',$favoritos->id)->update([
                'created_at'=>date("Y-m-d H:i:s"),
            ]);
            $favoritos=DB::table($db_name)->where('id',$favoritos->id)->first();
        }
         $favoritos->producto_id=$args['producto_id'];
         $favoritos->User=DB::table('users')->where('id',$args['user_id'])->first();
         return $favoritos;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
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
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
}
