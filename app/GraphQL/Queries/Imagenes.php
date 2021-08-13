<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class Imagenes
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
     * Funcion para retornar todas la imagenes de la galeria
     */
    public function GetImagenes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // hacer consulta ala base de datos
        $files = DB::table('imagenes')->orderBy('id', 'DESC')->get();
        foreach($files as $file){
            if($file->tipo_imagen==0){
                $year=date("Y",strtotime($file->created_at));
                $mes=date("m", strtotime($file->created_at));
                $nombre=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$file->url;
                $file->url=$nombre;
            }
           
            
        }
        @sort($files);
        return $files;
    }
    
}
