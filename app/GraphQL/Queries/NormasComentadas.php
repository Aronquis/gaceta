<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class NormasComentadas
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
     * Funcion para retornar todas las normas comentadas
     */
    public function GetAllNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                $db_name_categoria="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                $db_name_categoria="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                $db_name_categoria="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                $db_name_categoria="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                $db_name_categoria="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                $db_name_categoria="solucion_categoria_normas_comentadas";
                break;
        }
        //hacer consulta ala base de datos
        $NormasComentadas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);

        foreach(@$NormasComentadas as $normaComentada){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_rectangular=@$imagen;
            }
            $normaComentada->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$normaComentada->categoria_id)
                                    ->first();
        }
        return ['nroTotal_items'=>$NormasComentadas->total(),'data'=>$NormasComentadas];
    }

    public function GetBusquedaAvanzadaNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $db_name="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                $db_name_categoria="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                $db_name_categoria="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                $db_name_categoria="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                $db_name_categoria="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                $db_name_categoria="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                $db_name_categoria="solucion_categoria_normas_comentadas";
                break;
        }
        $palabras_aux = explode (" ", strtoupper($args["palabraClave"]));
        foreach($palabras_aux as $palabra){
            if($palabra=="EL"){
                $key = array_search('EL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="LA"){
                $key = array_search('LA', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="LOS"){
                $key = array_search('LOS', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="A"){
                $key = array_search('A', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="PARA"){
                $key = array_search('PARA', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="DE"){
                $key = array_search('DE', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="DEL"){
                $key = array_search('DEL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="AL"){
                $key = array_search('AL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
          
            if($palabra=="ES"){
                $key = array_search('ES', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="SON"){
                $key = array_search('SON', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="ME"){
                $key = array_search('ME', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="MI"){
                $key = array_search('MI', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="TU"){
                $key = array_search('TU', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="QUE"){
                $key = array_search('QUE', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="POR"){
                $key = array_search('POR', $palabras_aux);
                unset($palabras_aux[$key]);
            }
        }
        $palabras=[];
        foreach($palabras_aux as $palabra){
            array_push($palabras,$palabra);
        }
        $resultados=DB::table($db_name)
                ->select($db_name.'.*')
                ->orwhere($db_name.'.titulo', 'LIKE',"%".$palabras[0]."%")
                ->orwhere($db_name.'.descripcion_corta', 'LIKE',"%".$palabras[0]."%")
                ->orwhere($db_name.'.descripcion_larga', 'LIKE',"%".$palabras[0]."%")
                ->orwhere($db_name.'.keyword', 'LIKE',"%".$palabras[0]."%")
                ->orderBy($db_name.'.id', 'DESC')
                ->get();
        if(\count($palabras)>1){
            $array0=$resultados->pluck('id')->toArray();
            for ($i=1; $i <\count($palabras) ; $i++) { 
                $resultados_aux=DB::table($db_name)
                ->select($db_name.'.*')
                ->orwhere($db_name.'.titulo', 'LIKE',"%".$palabras[$i]."%")
                ->orwhere($db_name.'.descripcion_corta', 'LIKE',"%".$palabras[$i]."%")
                ->orwhere($db_name.'.descripcion_larga', 'LIKE',"%".$palabras[$i]."%")
                ->orwhere($db_name.'.keyword', 'LIKE',"%".$palabras[$i]."%")
                ->orderBy($db_name.'.id', 'DESC')
                ->get();
                if(count($resultados_aux)>0){
                    $array0=array_intersect($array0, $resultados_aux->pluck('id')->toArray());        
                }
            }
            $resultados=$resultados->whereIn('id',array_unique($array0));

            foreach(@$resultados as $normaComentada){
                // generar rutas para las imagenes
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_principal)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($normaComentada->imagen_principal)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$normaComentada->imagen_principal=@$imagen;
                }
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_rectangular)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($normaComentada->imagen_rectangular)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$normaComentada->imagen_rectangular=@$imagen;
                }
                $normaComentada->Categorias=DB::table($db_name_categoria)
                                        ->select($db_name_categoria.'.*')
                                        ->where($db_name_categoria.'.id',@$normaComentada->categoria_id)
                                        ->first();
            }
            
            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];

        }
        else{
            foreach(@$resultados as $normaComentada){
                // generar rutas para las imagenes
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_principal)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($normaComentada->imagen_principal)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$normaComentada->imagen_principal=@$imagen;
                }
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_rectangular)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($normaComentada->imagen_rectangular)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$normaComentada->imagen_rectangular=@$imagen;
                }
                $normaComentada->Categorias=DB::table($db_name_categoria)
                                        ->select($db_name_categoria.'.*')
                                        ->where($db_name_categoria.'.id',@$normaComentada->categoria_id)
                                        ->first();
            }
            
            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];
        }
    }
    /**
     * Funcion para retornar por slug la norma comentada
     */
    public function GetSlugNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                $db_name_categoria="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                $db_name_categoria="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                $db_name_categoria="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                $db_name_categoria="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                $db_name_categoria="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                $db_name_categoria="solucion_categoria_normas_comentadas";
                break;
        }
        // hacer consulta ala base de datos
        $NormasComentadas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        // generar rutas para las imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$NormasComentadas->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($NormasComentadas->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
            @$NormasComentadas->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$NormasComentadas->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($NormasComentadas->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
            @$NormasComentadas->imagen_rectangular=@$imagen;
        }
        $NormasComentadas->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$NormasComentadas->categoria_id)
                                    ->first();
        return $NormasComentadas;
    }
    /**
     * Funcion para retornar por fecha las normas comentadas
     */
    public function GetFechaNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                $db_name_categoria="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                $db_name_categoria="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                $db_name_categoria="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                $db_name_categoria="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                $db_name_categoria="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                $db_name_categoria="solucion_categoria_normas_comentadas";
                break;
        }
        //hacer consulta ala base de datos
        $NormasComentadas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->whereDate($db_name.'.created_at','=',$args['fecha'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);

        foreach(@$NormasComentadas as $normaComentada){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_rectangular=@$imagen;
            }
            $normaComentada->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$normaComentada->categoria_id)
                                    ->first();
        }  
        return ['nroTotal_items'=>$NormasComentadas->total(),'data'=>$NormasComentadas];
    }
    /**
     * Funcion para retornar por categoria las normas comentadas
     */
    public function GetCategoriaNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                $db_name_categoria="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                $db_name_categoria="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                $db_name_categoria="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                $db_name_categoria="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                $db_name_categoria="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                $db_name_categoria="solucion_categoria_normas_comentadas";
                break;
        }
        // hacer consulta ala base de datos
        $NormasComentadas=null;
        if($args['slug']!=""){
            $NormasComentadas=DB::table($db_name)
                ->join($db_name_categoria,$db_name_categoria.'.id','=',$db_name.'.categoria_id')
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name_categoria.'.slug',$args['slug'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        else{
            $NormasComentadas=DB::table($db_name)
                ->join($db_name_categoria,$db_name_categoria.'.id','=',$db_name.'.categoria_id')
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }

        foreach(@$NormasComentadas as $normaComentada){
            // generar las rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_rectangular=@$imagen;
            }
            $normaComentada->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$normaComentada->categoria_id)
                                    ->first();
        }  
        return ['nroTotal_items'=>$NormasComentadas->total(),'data'=>$NormasComentadas];
    }
    public function GetKeyworsNormasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_normas_comentadas";
                $db_name_categoria="civil_categoria_normas_comentadas";
                break;
            case 2:
                $db_name="penal_normas_comentadas";
                $db_name_categoria="penal_categoria_normas_comentadas";
                break;
            case 3:
                $db_name="constitucional_normas_comentadas";
                $db_name_categoria="constitucional_categoria_normas_comentadas";
                break;
            case 4:
                $db_name="juridica_normas_comentadas";
                $db_name_categoria="juridica_categoria_normas_comentadas";
                break;
            case 5:
                $db_name="jurisprudencia_normas_comentadas";
                $db_name_categoria="jurisprudencia_categoria_normas_comentadas";
                break;
            case 6:
                $db_name="solucion_normas_comentadas";
                $db_name_categoria="solucion_categoria_normas_comentadas";
                break;
        }
        //hacer consulta ala base de datos
        $NormasComentadas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.keyword','like','%'.$args['keyword'].'%')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);

        foreach(@$NormasComentadas as $normaComentada){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$normaComentada->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($normaComentada->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$normaComentada->imagen_rectangular=@$imagen;
            }
            $normaComentada->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$normaComentada->categoria_id)
                                    ->first();
        }  
        return ['nroTotal_items'=>$NormasComentadas->total(),'data'=>$NormasComentadas];
    }
    
}
