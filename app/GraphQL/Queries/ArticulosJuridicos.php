<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class ArticulosJuridicos
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
     * Funcion para retornar todos los articulos juridicos
     */
    public function GetAllArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto

        
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                $db_name2="notas_civil_articulos_juridicos";
                break;
            case 2:
                $db_name="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                $db_name2="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                $db_name2="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                $db_name2="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                $db_name2="notas_jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
                $db_name2="notas_solucion_articulos_juridicos";
                break;
        }
        // hacer la consulta ala base de datos
        $ArticulosJuridicos=null;
        if($args['slugAutor']!=""){
            $ArticulosJuridicos=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.slugAutor',$args['slugAutor'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        else{
            $ArticulosJuridicos=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        
        //asignar rutas alas imagenes de la galeria
        foreach(@$ArticulosJuridicos as $articuloJuridico){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                   @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                }
                
                @$articuloJuridico->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                
                @$articuloJuridico->imagen_rectangular=@$imagen;
            }
            $articuloJuridico->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$articuloJuridico->categoria_id)
                                    ->first();
            @$articuloJuridico->Autores=DB::table('autores')->where('autorId', $articuloJuridico->autorId)->first();
            $articuloJuridico->NotasArticulosJuridicos=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }
        // retornar datos
        return ['nroTotal_items'=>$ArticulosJuridicos->total(),'data'=>$ArticulosJuridicos];
    }
    public function GetBusquedaAvanzadaArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                $db_name2="notas_civil_articulos_juridicos";
                break;
            case 2:
                $db_name="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                $db_name2="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                $db_name2="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                $db_name2="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                $db_name2="notas_jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
                $db_name2="notas_solucion_articulos_juridicos";
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
                ->orwhere($db_name.'.textoAudio', 'LIKE',"%".$palabras[0]."%")
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
                ->orwhere($db_name.'.textoAudio', 'LIKE',"%".$palabras[$i]."%")
                ->orderBy($db_name.'.id', 'DESC')
                ->get();
                if(count($resultados_aux)>0){
                    $array0=array_intersect($array0, $resultados_aux->pluck('id')->toArray());        
                }
            }
            $resultados=$resultados->whereIn('id',array_unique($array0));

            foreach(@$resultados as $articuloJuridico){
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_principal)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($articuloJuridico->imagen_principal)==true){
                    if(@$imagen->tipo_imagen==0){
                       @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                    }
                    
                    @$articuloJuridico->imagen_principal=@$imagen;
                }
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_rectangular)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($articuloJuridico->imagen_rectangular)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                    }
                    
                    @$articuloJuridico->imagen_rectangular=@$imagen;
                }
                $articuloJuridico->Categorias=DB::table($db_name_categoria)
                                        ->select($db_name_categoria.'.*')
                                        ->where($db_name_categoria.'.id',@$articuloJuridico->categoria_id)
                                        ->first();
                @$articuloJuridico->Autores=DB::table('autores')->where('autorId', $articuloJuridico->autorId)->first();
                $articuloJuridico->NotasArticulosJuridicos=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
            }


            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];

        }
        else{
            foreach(@$resultados as $articuloJuridico){
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_principal)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($articuloJuridico->imagen_principal)==true){
                    if(@$imagen->tipo_imagen==0){
                       @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                    }
                    
                    @$articuloJuridico->imagen_principal=@$imagen;
                }
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_rectangular)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($articuloJuridico->imagen_rectangular)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                    }
                    
                    @$articuloJuridico->imagen_rectangular=@$imagen;
                }
                $articuloJuridico->Categorias=DB::table($db_name_categoria)
                                        ->select($db_name_categoria.'.*')
                                        ->where($db_name_categoria.'.id',@$articuloJuridico->categoria_id)
                                        ->first();
                @$articuloJuridico->Autores=DB::table('autores')->where('autorId', $articuloJuridico->autorId)->first();
                $articuloJuridico->NotasArticulosJuridicos=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
            }
            
            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];
        }
    }
    /**
     * Funcion para retornar un articulo juridico por slug
     */
    public function GetSlugArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                $db_name2="notas_civil_articulos_juridicos";
                break;
            case 2:
                $db_name="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                $db_name2="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                $db_name2="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                $db_name2="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                $db_name2="notas_jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
                $db_name2="notas_solucion_articulos_juridicos";
                break;
        }
        // hacer la consulta ala base de datos
        $ArticulosJuridicos=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        // asignar rutas alas imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$ArticulosJuridicos->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($ArticulosJuridicos->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
              @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;      
            }
            
            @$ArticulosJuridicos->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$ArticulosJuridicos->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($ArticulosJuridicos->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
              @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;      
            }
            
            @$ArticulosJuridicos->imagen_rectangular=@$imagen;
        }
        //retornar data de la categoria de articulos juridicos
        @$ArticulosJuridicos->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$ArticulosJuridicos->categoria_id)
                                    ->first();
        @$ArticulosJuridicos->Autores=DB::table('autores')->where('autorId', $ArticulosJuridicos->autorId)->first();
        $ArticulosJuridicos->NotasArticulosJuridicos=DB::table($db_name2)->where('pro_id', $ArticulosJuridicos->id)->get();
        return $ArticulosJuridicos;
    }
    /**
     * Funcion para retornar todos los articulos juridcos por fecha
     */
    public function GetFechaArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria=""; 
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                $db_name2="notas_civil_articulos_juridicos";
                break;
            case 2: 
                $db_name="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                $db_name2="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                $db_name2="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                $db_name2="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                $db_name2="notas_jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
                $db_name2="notas_solucion_articulos_juridicos";
                break;
        }
        // hacer consulta ala base de datos
        $ArticulosJuridicos=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->whereDate($db_name.'.created_at','=',$args['fecha'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        // asignar rutas alas imagenes
        foreach(@$ArticulosJuridicos as $articuloJuridico){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                   @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                }
                
                @$articuloJuridico->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                   @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                }
                
                @$articuloJuridico->imagen_rectangular=@$imagen;
            }
            //retornar data de la categoria de articulos juridicos
            $articuloJuridico->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$articuloJuridico->categoria_id)
                                    ->first();
            @$articuloJuridico->Autores=DB::table('autores')->where('autorId', $articuloJuridico->autorId)->first();
            $articuloJuridico->NotasArticulosJuridicos=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }  
        return ['nroTotal_items'=>$ArticulosJuridicos->total(),'data'=>$ArticulosJuridicos];
    }
    /**
     * Funcion para retornar articulos juridicos por la categoria de la misma
     */
    public function GetCategoriaArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria=""; 
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                $db_name2="notas_civil_articulos_juridicos";
                break;
            case 2: 
                $db_name="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                $db_name2="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                $db_name2="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                $db_name2="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                $db_name2="notas_jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
                $db_name2="notas_solucion_articulos_juridicos";
                break;
        }
        // hacer consulta ala base de datos
        $ArticulosJuridicos=null;
        // asignar rutas ala db
        if($args['slug']!=""){
            $ArticulosJuridicos=DB::table($db_name)
                ->join($db_name_categoria,$db_name_categoria.'.id','=',$db_name.'.categoria_id')
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name_categoria.'.slug',$args['slug'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        else{
            $ArticulosJuridicos=DB::table($db_name)
                ->join($db_name_categoria,$db_name_categoria.'.id','=',$db_name.'.categoria_id')
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }

        foreach(@$ArticulosJuridicos as $articuloJuridico){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
                
                @$articuloJuridico->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                   @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                }
                
                @$articuloJuridico->imagen_rectangular=@$imagen;
            }
            // retornar data para la categoria de articulos juridicos
            $articuloJuridico->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$articuloJuridico->categoria_id)
                                    ->first();
            @$articuloJuridico->Autores=DB::table('autores')->where('autorId', $articuloJuridico->autorId)->first();
            $articuloJuridico->NotasArticulosJuridicos=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }  
        return ['nroTotal_items'=>$ArticulosJuridicos->total(),'data'=>$ArticulosJuridicos];
    }
    public function GetKeywordsArticulosJuridicos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $db_name="";
        $db_name_categoria=""; 
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_articulos_juridicos";
                $db_name_categoria="civil_categoria_articulos_juridicos";
                $db_name2="notas_civil_articulos_juridicos";
                break;
            case 2: 
                $db_name="penal_articulos_juridicos";
                $db_name_categoria="penal_categoria_articulos_juridicos";
                $db_name2="notas_penal_articulos_juridicos";
                break;
            case 3:
                $db_name="constitucional_articulos_juridicos";
                $db_name_categoria="constitucional_categoria_articulos_juridicos";
                $db_name2="notas_constitucional_articulos_juridicos";
                break;
            case 4:
                $db_name="juridica_articulos_juridicos";
                $db_name_categoria="juridica_categoria_articulos_juridicos";
                $db_name2="notas_juridica_articulos_juridicos";
                break;
            case 5:
                $db_name="jurisprudencia_articulos_juridicos";
                $db_name_categoria="jurisprudencia_categoria_articulos_juridicos";
                $db_name2="notas_jurisprudencia_articulos_juridicos";
                break;
            case 6:
                $db_name="solucion_articulos_juridicos";
                $db_name_categoria="solucion_categoria_articulos_juridicos";
                $db_name2="notas_solucion_articulos_juridicos";
                break;
        }
        // hacer consulta ala base de datos
        $ArticulosJuridicos=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.keyword','LIKE','%'.$args['keyword'].'%')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        // asignar rutas alas imagenes
        foreach(@$ArticulosJuridicos as $articuloJuridico){
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                   @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                }
                
                @$articuloJuridico->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$articuloJuridico->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($articuloJuridico->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                   @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url; 
                }
                
                @$articuloJuridico->imagen_rectangular=@$imagen;
            }
            //retornar data de la categoria de articulos juridicos
            $articuloJuridico->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$articuloJuridico->categoria_id)
                                    ->first();
            @$articuloJuridico->Autores=DB::table('autores')->where('autorId', $articuloJuridico->autorId)->first();
            $articuloJuridico->NotasArticulosJuridicos=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }  
        return ['nroTotal_items'=>$ArticulosJuridicos->total(),'data'=>$ArticulosJuridicos];
    }
}
