<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CrudImagenes
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
    public function create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $nameCreate="";
        $tipo_imagen=0;
        if(isset($args['imagen'])==true){
            $image = $args['imagen'];
            $arrNameFile = explode(".", $image->getClientOriginalName());
            $extension = $arrNameFile[sizeof($arrNameFile) - 1];
            $fileName = str_replace(["(",")",' '],'',$arrNameFile[0]);
            //////////////////////////////7777
            //////////////validar nonbres
            $nombre=DB::table('imagenes')->where('url',$fileName. '.' .$extension)->first();
            $fileNameNuevo=$fileName;
            $i=1;
            while (isset($nombre->id)==true) {
                $nombre=DB::table('imagenes')->where('url',$fileName.'-'.$i.'.'.$extension)->first();
                $fileNameNuevo=$fileName.'-'.$i;
                $i+=1;
                
            }
            /////////////////////////////////
        
            $nameCreate =$fileNameNuevo. '.' .$extension;
            $image->storeAs('imagenesGenerales/'.date("Y").'-'.date("m"), $nameCreate, 'local');
            $tipo_imagen=0;
        }
        else{
            //cloudinary
            @$nameCreate=@$args['url'];
            $tipo_imagen=1;
        }
        $id=DB::table('imagenes')->insertGetId([
            'url'=>@$nameCreate,
            'tipo_imagen'=>@$tipo_imagen,
            'nombre'=>@$args['nombre'],
            'created_at'=>date("Y").'-'.date("m").'-'.date("d")
        ]);
        $imagen=DB::table('imagenes')->where('id',$id)->first();
        return $imagen;
    }
    public function update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        DB::table('imagenes')->where('id',$args['id'])->update([
            'nombre'=>@$args['nombre'],
        ]);
        $imagen=DB::table('imagenes')->where('id',$args['id'])->first();
        return $imagen;
    }
    public function delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $imagen=DB::table('imagenes')->where('id',$args['id'])->first();
        $year=date("Y",strtotime($imagen->created_at));
        $mes=date("m", strtotime($imagen->created_at));
        @unlink(storage_path().'/app/imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url);

        DB::table('imagenes')->where('id',$args['id'])->delete();
        return "se elimino correctamente";
    }
}
