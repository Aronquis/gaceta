<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class Usuarios
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
     * Funcion para retornar todos los usuarios de la gaceta
     */
    public function GetAllUsers($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $usuarios=DB::table('users')->get();
        foreach($usuarios as $usuario){
            @$year=date("Y",strtotime($usuario->created_at));
            @$mes=date("m", strtotime($usuario->created_at));
            if($usuario->photo!=""){
                @$usuario->photo=env('APP_URL').'UserImagenes/'.$year.'-'.$mes.'/'.$usuario->photo;
            }
        }
        return $usuarios;
    }
    /**
     * Funcion para retornar todos los usuarios suscritos
     */
    public function GetAllUsersSuscritos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $usuarios=DB::table('usuarios_intranet')->get();
        return $usuarios;
    }
}
