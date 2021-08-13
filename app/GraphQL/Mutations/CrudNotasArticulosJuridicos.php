<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\User;
use Image;
use App\Services\JWTServices; 
use Firebase\JWT\JWT;
use JWTAuth;
use Illuminate\Support\Str;
class CrudNotasArticulosJuridicos
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
        // TODO implement the resolver
        $db_name="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name2="civil_articulos_juridicos";
                $db_name="notas_civil_articulos_juridicos";
                break;
            case 2:
                $db_name2="penal_articulos_juridicos";
                $db_name="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name2="constitucional_articulos_juridicos";
                $db_name="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name2="juridica_articulos_juridicos";
                $db_name="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name2="jurisprudencia_articulos_juridicos";
                $db_name="notas_juridica_articulos_juridicos";
                break;
        }
        $id=DB::table($db_name)->insertGetId([
            'id_key'=>$args['id_key'],
            'descripcionNota'=>$args['descripcionNota'],
            'tituloNota'=>$args['tituloNota'],
            'pro_id'=>$args['pro_id'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $notas_articulos=DB::table($db_name)->where('id',$id)->first();
        $notas_articulos->ArticulosJuridicos=DB::table($db_name2)->where('id',$notas_articulos->pro_id)->first();
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_articulos->ArticulosJuridicos->imagen_principal)->first();

        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_articulos->ArticulosJuridicos->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_articulos->ArticulosJuridicos->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_articulos->ArticulosJuridicos->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_articulos->ArticulosJuridicos->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_articulos->ArticulosJuridicos->imagen_rectangular=@$imagen;
        }
        return $notas_articulos;
    }
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $db_name="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name2="civil_articulos_juridicos";
                $db_name="notas_civil_articulos_juridicos";
                break;
            case 2:
                $db_name2="penal_articulos_juridicos";
                $db_name="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name2="constitucional_articulos_juridicos";
                $db_name="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name2="juridica_articulos_juridicos";
                $db_name="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name2="jurisprudencia_articulos_juridicos";
                $db_name="notas_jurisprudencia_articulos_juridicos";
                break;
        }
        $id=DB::table($db_name)->where('id',$args['id'])->update([
            'id_key'=>$args['id_key'],
            'descripcionNota'=>$args['descripcionNota'],
            'tituloNota'=>$args['tituloNota'],
            'pro_id'=>$args['pro_id'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $notas_articulos=DB::table($db_name)->where('id',$args['id'])->first();
        $notas_articulos->ArticulosJuridicos=DB::table($db_name2)->where('id',$notas_articulos->pro_id)->first();
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_articulos->ArticulosJuridicos->imagen_principal)->first();

        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_articulos->ArticulosJuridicos->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_articulos->ArticulosJuridicos->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$notas_articulos->ArticulosJuridicos->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($notas_articulos->ArticulosJuridicos->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
            }
            @$notas_articulos->ArticulosJuridicos->imagen_rectangular=@$imagen;
        }
        return $notas_articulos;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $db_name="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name2="civil_articulos_juridicos";
                $db_name="notas_civil_articulos_juridicos";
                break;
            case 2:
                $db_name2="penal_articulos_juridicos";
                $db_name="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name2="constitucional_articulos_juridicos";
                $db_name="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name2="juridica_articulos_juridicos";
                $db_name="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name2="jurisprudencia_articulos_juridicos";
                $db_name="notas_jurisprudencia_articulos_juridicos";
                break;
        }
        $recu=DB::table($db_name)->where('id',$args['id'])->first();
        if(isset($recu->id)==true){
            DB::table($db_name)->where('id',$args['id'])->delete();
        }
        else{
            throw new \Exception('NO_EXISTE');
        }
    }
}
