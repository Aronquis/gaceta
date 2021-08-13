<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class Categorias
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
      * Funcion para retornar todas las categorias de noticias,informes y opiniones
      */
    public function GetAllCategoriasNoticias($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_noticias_informes_opiniones";
                $db_name_2="civil_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="penal_categoria_noticias_informes_opiniones";
                $db_name_2="penal_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="constitucional_categoria_noticias_informes_opiniones";
                $db_name_2="constitucional_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="juridica_categoria_noticias_informes_opiniones";
                $db_name_2="juridica_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_noticias_informes_opiniones";
                $db_name_2="jurisprudencia_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="solucion_categoria_noticias_informes_opiniones";
                $db_name_2="solucion_noticias_informes_opiniones";
                break;
        }
        // hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->leftjoin($db_name_2,$db_name_2.'.categoria_id','=',$db_name.'.id')
            ->select($db_name.'.id',$db_name.'.titulo',$db_name.'.slug',$db_name.'.keywords',$db_name.'.descripcion',$db_name.'.created_at',$db_name.'.updated_at',DB::raw("COUNT(".$db_name_2.".id) as numero"))
            ->groupBy($db_name.'.id')
            ->groupBy($db_name.'.titulo')
            ->groupBy($db_name.'.slug')
            ->groupBy($db_name.'.keywords')
            ->groupBy($db_name.'.descripcion')
            ->groupBy($db_name.'.created_at')
            ->groupBy($db_name.'.updated_at')
            ->orderBy($db_name.'.id', 'DESC')
            ->get();
        return $categoria;
    }
    /**
     * Funcion para retornar una categoria por slug  de moticios,informes y oponiones 
     */
    public function GetSlugCategoriasNoticias($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.slug',$args['slug'])
            ->first();
        return $categoria;
    }
    /**
     * Funcion para retornar categorias de articulos juridicos
     */
    public function GetAllCategoriasArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_articulos_juridicos";
                $db_name_2="civil_articulos_juridicos";
                break;
            case 2:
                $db_name="penal_categoria_articulos_juridicos";
                $db_name_2="penal_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_categoria_articulos_juridicos";
                $db_name_2="constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_categoria_articulos_juridicos";
                $db_name_2="juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_articulos_juridicos";
                $db_name_2="jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_categoria_articulos_juridicos";
                $db_name_2="solucion_articulos_juridicos";
                break;
        }
        // hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->leftjoin($db_name_2,$db_name_2.'.categoria_id','=',$db_name.'.id')
            ->select($db_name.'.id',$db_name.'.titulo',$db_name.'.slug',$db_name.'.keywords',$db_name.'.descripcion',$db_name.'.created_at',$db_name.'.updated_at',DB::raw("COUNT(".$db_name_2.".id) as numero"))
            ->groupBy($db_name.'.id')
            ->groupBy($db_name.'.titulo')
            ->groupBy($db_name.'.slug')
            ->groupBy($db_name.'.keywords')
            ->groupBy($db_name.'.descripcion')
            ->groupBy($db_name.'.created_at')
            ->groupBy($db_name.'.updated_at')
            ->orderBy($db_name.'.id', 'DESC')
            ->get();
        return $categoria;
    }
    /**
     * Funcion para retornar por slug la categoria de articulos juridicos
     */
    public function GetSlugCategoriasArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.slug',$args['slug'])
            ->first();
        return $categoria;
    }
    /**
     * Funcion para retornar todas las categorias de normas comentadas
     */
    public function GetAllCategoriasNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_normas_comentadas";
                $db_name_2="civil_normas_comentadas";
                break;
            case 2:
                $db_name="penal_categoria_normas_comentadas";
                $db_name_2="penal_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_categoria_normas_comentadas";
                $db_name_2="constitucional_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_categoria_normas_comentadas";
                $db_name_2="juridica_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_normas_comentadas";
                $db_name_2="jurisprudencia_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_categoria_normas_comentadas";
                $db_name_2="solucion_normas_comentadas";
                break;
        }
        // hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->leftjoin($db_name_2,$db_name_2.'.categoria_id','=',$db_name.'.id')
            ->select($db_name.'.id',$db_name.'.titulo',$db_name.'.slug',$db_name.'.keywords',$db_name.'.descripcion',$db_name.'.created_at',$db_name.'.updated_at',DB::raw("COUNT(".$db_name_2.".id) as numero"))
            ->groupBy($db_name.'.id')
            ->groupBy($db_name.'.titulo')
            ->groupBy($db_name.'.slug')
            ->groupBy($db_name.'.keywords')
            ->groupBy($db_name.'.descripcion')
            ->groupBy($db_name.'.created_at')
            ->groupBy($db_name.'.updated_at')
            ->orderBy($db_name.'.id', 'DESC')
            ->get();
        return $categoria;
    }
    /**
     * Fucion para retornar por slug la categoria de normas comentadas
     */
    public function GetSlugCategoriasNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.slug',$args['slug'])
            ->first();
        return $categoria;
    }
    /**
     * Funcion para retornar todas las categorias de jurisprudencias comentadas
     */
    public function GetAllCategoriasJurisprudenciaComentada($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        
        $db_name="";
        $db_name_2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_categoria_jurisprudencias";
                $db_name_2="civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_categoria_jurisprudencias";
                $db_name_2="penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_categoria_jurisprudencias";
                $db_name_2="constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_categoria_jurisprudencias";
                $db_name_2="juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_categoria_jurisprudencias";
                $db_name_2="jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_categoria_jurisprudencias";
                $db_name_2="solucion_jurisprudencias";
                break;
        }
        //  hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->leftjoin($db_name_2,$db_name_2.'.categoria_id','=',$db_name.'.id')
            ->select($db_name.'.id',$db_name.'.titulo',$db_name.'.slug',$db_name.'.keywords',$db_name.'.descripcion',$db_name.'.created_at',$db_name.'.updated_at',DB::raw("COUNT(".$db_name_2.".id) as numero"))
            ->groupBy($db_name.'.id')
            ->groupBy($db_name.'.titulo')
            ->groupBy($db_name.'.slug')
            ->groupBy($db_name.'.keywords')
            ->groupBy($db_name.'.descripcion')
            ->groupBy($db_name.'.created_at')
            ->groupBy($db_name.'.updated_at')
            ->orderBy($db_name.'.id', 'DESC')
            ->get();
        return $categoria;
    }
    /**
     * Funcion para retornar por slug la categoria de jurisprudencias comentadas
     */
    public function GetSlugCategoriasJurisprudenciaComentada($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        // hacer consulta ala base de datos
        @$categoria=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.slug',$args['slug'])
            ->first();
        return $categoria;
    } 
}
