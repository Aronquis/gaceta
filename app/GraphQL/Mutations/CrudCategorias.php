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
class CrudCategorias
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
    * Funcion para crear categoria para noticias,informes y opiniones
    */
    public function CreateCategoriaNoticiasInformesOpiniones($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="penal_categoria_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="constitucional_categoria_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="juridica_categoria_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="solucion_categoria_noticias_informes_opiniones";
                break;
        }
        //validar el slug
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
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        //recuperar datos
        $categoria=DB::table($db_name)->get()->last();
        return $categoria;
    }
    /*
    * Funcion para actualizar categoria para noticias,informes y opiniones
    */
    public function UpdateCategoriaNoticiasInformesOpiniones($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="penal_categoria_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="constitucional_categoria_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="juridica_categoria_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="solucion_categoria_noticias_informes_opiniones";
                break;
        }
        //validar el slug
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
        //guardar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuepar datos
        $categoria=DB::table($db_name)
                        ->where('id',$args['id'])
                        ->first();
        return $categoria;
    }
    /*
    *Funcion para eliminar categoria de noticias,informes y opiniones
    */
    public function DeleteCategoriaNoticiasInformesOpiniones($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="penal_categoria_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="constitucional_categoria_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="juridica_categoria_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="solucion_categoria_noticias_informes_opiniones";
                break;
        }
        //Eliminar categoria
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Funcion para crear categoria para articulos juridicos
    */
    public function CreateCategoriaArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_articulos_juridicos";
                break;
            case 2:
                $db_name="penal_categoria_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_categoria_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_categoria_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_categoria_articulos_juridicos";
                break;
        }
        //validar slug
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
        //guardar datos
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $categoria=DB::table($db_name)->get()->last();
        return $categoria;
    }
    /*
    * Funcion para actulizar categorias de articulos juridicos
    */
    public function UpdateCategoriaArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_articulos_juridicos";
                break;
            case 2:
                $db_name="penal_categoria_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_categoria_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_categoria_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_categoria_articulos_juridicos";
                break;
        }
        //validar el slug
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
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        //recuperar datos guardados
        $categoria=DB::table($db_name)
                        ->where('id',$args['id'])
                        ->first();
        return $categoria;
    }
    /*
    * Funcion para eliminar una categoria de articulos juridicos
    */
    public function DeleteCategoriaArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_articulos_juridicos";
                break;
            case 2:
                $db_name="penal_categoria_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_categoria_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_categoria_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_categoria_articulos_juridicos";
                break;
        }
        // eliminar categoria
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    * Funcion para crear categoria para normas comentadas
    */
    public function CreateCategoriaNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_categoria_normas_comentadas";
                break;
        }
        //validar slug
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
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        //recuperar datos guardados
        $categoria=DB::table($db_name)->get()->last();
        return $categoria;
    }
    /*
    *Funcion para actulizar categoria de normas comentadas
    */
    public function UpdateCategoriaNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_categoria_normas_comentadas";
                break;
        }
        //validar el slug
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
        //guardar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        //recuperar datos
        $categoria=DB::table($db_name)
                        ->where('id',$args['id'])
                        ->first();
        return $categoria;
    }
    /*
    * Funcion para eliminar categoria de normas comentadas
    */
    public function DeleteCategoriaNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /// Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_categoria_normas_comentadas";
                break;
        }
        // eliminar categoria
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
    /*
    *Funcion para crear categoria para jurisprudencias comentadas
    */
    public function CreateCategoriaJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_jurisprudencias";
                break;
            case 2:
                $db_name="penal_categoria_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_categoria_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_categoria_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_categoria_jurisprudencias";
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
        // guardar datos
        DB::table($db_name)->insert([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        // recuperar datos
        $categoria=DB::table($db_name)->get()->last();
        return $categoria;
    }
    /*
    *  Funcion para actulizar categoria de jurisprudencias comentadas
    */
    public function UpdateCategoriaJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_jurisprudencias";
                break;
            case 2:
                $db_name="penal_categoria_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_categoria_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_categoria_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_categoria_jurisprudencias";
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
        //Guardar datos
        DB::table($db_name)->where('id',$args['id'])->update([
            'titulo'=>$args['titulo'],
            'slug'=>$slug,
            'keywords'=>$args['keywords'],
            'descripcion'=>$args['descripcion'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $categoria=DB::table($db_name)
                        ->where('id',$args['id'])
                        ->first();
        return $categoria;
    }
    /*
    *   Funcion para eliminar categoria de jurisprudencias comentadas
    */
    public function DeleteCategoriaJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_jurisprudencias";
                break;
            case 2:
                $db_name="penal_categoria_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_categoria_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_categoria_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_categoria_jurisprudencias";
                break;
        }
        // eliminar categoria
        DB::table($db_name)->where('id',$args['id'])->delete();
        return "Exito";
    }
}
