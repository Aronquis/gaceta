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
class CrudNormasLegales
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
    * Funcion para crear normas legales
    */
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // recuperar token de inicio de sesion
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $email=$payload->iss;
        $typeUser=$payload->iat;
        $user_id=$payload->nbf;
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // validar slug
        $slug=Str::slug($args['titulo']);
        $j=1;
        $buscar_slug=DB::table($db_name)
                ->where('slug',Str::slug($args['titulo']))->first();
        while(isset($buscar_slug->id)==true){
            $slug=Str::slug($args['titulo']).'-'.$j;
            $buscar_slug=DB::table($db_name)
                            ->where('slug',$slug)->first();
            $j+=1;
        }
        // guardar pdf en el storage y asignar ruta
        $nameCreate_final="";
        if(isset($args['pdf'])==true){
            $image = $args['pdf'];
            $arrNameFile = explode(".", $args['pdf']->getClientOriginalName());
            $extension = $arrNameFile[sizeof($arrNameFile) - 1];
            $fileName = str_replace(' ', '',$arrNameFile[0]);
            $nameCreate =$fileName;
            ///validar nombre del pdf
            $nuevo_nameCreate="";
            $j=1;
            $bucar_pdf=DB::table($db_name)
                    ->where('pdf',env('APP_URL').'PdfNomasLegales/'.date("Y").'-'.date("m").'/'.$nameCreate)->first();
            while(isset($bucar_pdf->id)==true){
                $nuevo_nameCreate=$nameCreate.'('.$j.')';
                $bucar_pdf=DB::table($db_name)
                                ->where('pdf',env('APP_URL').'PdfNomasLegales/'.date("Y").'-'.date("m").'/'.$nuevo_nameCreate)->first();
                $j+=1;
            }
            if($nuevo_nameCreate!=""){
                $nameCreate=$nuevo_nameCreate;
            }
            // guardar imagen en el storage y asignar ruta
            $image->storeAs('PdfNomasLegales/'.date("Y").'-'.date("m"), $nameCreate.'.'.$extension, 'local');
            $nameCreate_final=env('APP_URL').'PdfNomasLegales/'.date("Y").'-'.date("m").'/'.$nameCreate.'.'.$extension; 
        }
        // guardar datos
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'fecha'=>$args['fecha'],
            'pdf'=>@$nameCreate_final,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardados
        $normas_legales=DB::table($db_name)->get()->last();
        
        return $normas_legales;

    }
    /*
    * Funcion para actulizar normas legales
    */
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // actulizar pdf
        if(isset($args['pdf'])==true){
            $image = $args['pdf'];
            $arrNameFile = explode(".", $args['pdf']->getClientOriginalName());
            $extension = $arrNameFile[sizeof($arrNameFile) - 1];
            $fileName = str_replace(' ', '',$arrNameFile[0]);
            $nameCreate =$fileName;
            ///validar nombre del pdf
            $normas_legales=DB::table($db_name)->where('id',$args['id'])->first();
            @$year=date("Y",strtotime($normas_legales->fecha));
            @$mes=date("m", strtotime($normas_legales->fecha));

            $nuevo_nameCreate="";
            $j=1;
            $bucar_pdf=DB::table($db_name)
                    ->where('pdf',env('APP_URL').'PdfNomasLegales/'.@$year.'-'.@$mes.'/'.$nameCreate)->first();
            while(isset($bucar_pdf->id)==true){
                $nuevo_nameCreate=$nameCreate.'('.$j.')';
                $bucar_pdf=DB::table($db_name)
                                ->where('pdf',env('APP_URL').'PdfNomasLegales/'.@$year.'-'.@$mes.'/'.$nuevo_nameCreate)->first();
                $j+=1;
            }
            if($nuevo_nameCreate!=""){
                $nameCreate=$nuevo_nameCreate;
            }
            // guardar pdf en el storage y asignar ruta
            $image->storeAs('PdfNomasLegales/'.date("Y").'-'.date("m"), $nameCreate. '.' .$extension, 'local');
            $nameCreate_final=env('APP_URL').'PdfNomasLegales/'.@$year.'-'.@$mes.'/'.$nameCreate.'.'.$extension; 
            //guardar datos
            DB::table($db_name)->where('id',$args['id'])->update([
                'pdf'=>$nameCreate_final,
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        }
        
        // validar slug
        $slug=Str::slug($args['titulo']);
        $j=1;
        $buscar_slug=DB::table($db_name)
                ->where('slug',Str::slug($args['titulo']))->first();
        while(isset($buscar_slug->id)==true){
            $slug=Str::slug($args['titulo']).'-'.$j;
            $buscar_slug=DB::table($db_name)
                            ->where('slug',$slug)->first();
            $j+=1;
        }
        // guardar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'fecha'=>$args['fecha'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos guardados
        $normas_legales=DB::table($db_name)->where('id',$args['id'])->first();

        return $normas_legales;
    }
    /*
    * Funcion para eliminar normas legales
    */
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // eliminar normas legales
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Guardar numero de vistas para normas legales
    */
    public function UpdateNroVistas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_legales";
                break;
            case 2:
                $db_name="penal_normas_legales";
                break;
            case 3:
                $db_name="constitucional_normas_legales";
                break;
            case 4:
                $db_name="juridica_normas_legales";
                break;
            case 5:
                $db_name="jurisprudencia_normas_legales";
                break;
            case 6:
                $db_name="solucion_normas_legales";
                break;
        }
        // guardar datos
        $normas_legales=DB::table($db_name)->where('id',$args['id'])->first();
        DB::table($db_name)->where('id',$args['id'])->update([
            'nroVistas'=>(Int)$normas_legales->nroVistas+1,
        ]);
        // recuperar datos guardados
        $normas_legales=DB::table($db_name)->where('id',$args['id'])->first();

        
        return $normas_legales;
    }
}
