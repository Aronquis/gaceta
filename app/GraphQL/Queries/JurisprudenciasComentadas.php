<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class JurisprudenciasComentadas
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
     * Funcion para retornar todas las jurisprudencias comentadas
     */
    public function GetAllJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                $db_name_categoria="civil_categoria_jurisprudencias";
                $db_name2="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                $db_name_categoria="penal_categoria_jurisprudencias";
                $db_name2="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                $db_name_categoria="constitucional_categoria_jurisprudencias";
                $db_name2="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                $db_name_categoria="juridica_categoria_jurisprudencias";
                $db_name2="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                $db_name_categoria="jurisprudencia_categoria_jurisprudencias";
                $db_name2="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                $db_name_categoria="solucion_categoria_jurisprudencias";
                $db_name2="notas_solucion_jurisprudencias";
                break;
        }
        // hacer consulta ala base de datos
        $JurisprudenciasComentadas=null;
        if($args['slugEmisor']!=""){
            $JurisprudenciasComentadas=DB::table($db_name)
            ->select($db_name.'.*')
            ->where($db_name.'.slugEmisor',$args['slugEmisor'])
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        else{
            $JurisprudenciasComentadas=DB::table($db_name)
            ->select($db_name.'.*')
            ->orderBy($db_name.'.id', 'DESC')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }

        foreach(@$JurisprudenciasComentadas as $articuloJuridico){
            //Generar rutas para las imagenes
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
            $articuloJuridico->Emisores=DB::table('emisores')->where('emisorId', $articuloJuridico->emisorId)->first();
            $articuloJuridico->NotasJurisprudenciasComentadas=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }
        return ['nroTotal_items'=>$JurisprudenciasComentadas->total(),'data'=>$JurisprudenciasComentadas];
    }
    public function GetBusquedaAvanzadaJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                $db_name_categoria="civil_categoria_jurisprudencias";
                $db_name2="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                $db_name_categoria="penal_categoria_jurisprudencias";
                $db_name2="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                $db_name_categoria="constitucional_categoria_jurisprudencias";
                $db_name2="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                $db_name_categoria="juridica_categoria_jurisprudencias";
                $db_name2="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                $db_name_categoria="jurisprudencia_categoria_jurisprudencias";
                $db_name2="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                $db_name_categoria="solucion_categoria_jurisprudencias";
                $db_name2="notas_solucion_jurisprudencias";
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
                ->orwhere($db_name.'.nroResolucion', 'LIKE',"%".$palabras[0]."%")
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
                ->orwhere($db_name.'.nroResolucion', 'LIKE',"%".$palabras[$i]."%")
                ->orderBy($db_name.'.id', 'DESC')
                ->get();
                if(count($resultados_aux)>0){
                    $array0=array_intersect($array0, $resultados_aux->pluck('id')->toArray());        
                }
            }
            $resultados=$resultados->whereIn('id',array_unique($array0));

            foreach(@$resultados as $articuloJuridico){
                //Generar rutas para las imagenes
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
                $articuloJuridico->Emisores=DB::table('emisores')->where('emisorId', $articuloJuridico->emisorId)->first();
                $articuloJuridico->NotasJurisprudenciasComentadas=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
            }
            
            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];

        }
        else{
            foreach(@$resultados as $articuloJuridico){
                //Generar rutas para las imagenes
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
                $articuloJuridico->Emisores=DB::table('emisores')->where('emisorId', $articuloJuridico->emisorId)->first();
                $articuloJuridico->NotasJurisprudenciasComentadas=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
            }
            
            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];
        }
    }
    /**
     * Funcion para retornar por slug las jurisprudencias comentadas
     */
    public function GetSlugJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                $db_name_categoria="civil_categoria_jurisprudencias";
                $db_name2="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                $db_name_categoria="penal_categoria_jurisprudencias";
                $db_name2="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                $db_name_categoria="constitucional_categoria_jurisprudencias";
                $db_name2="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                $db_name_categoria="juridica_categoria_jurisprudencias";
                $db_name2="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                $db_name_categoria="jurisprudencia_categoria_jurisprudencias";
                $db_name2="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                $db_name_categoria="solucion_categoria_jurisprudencias";
                $db_name2="notas_solucion_jurisprudencias";
                break;
        }
        // hacer consulta ala base de datos
        $JurisprudenciasComentadas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        // generar rutas para las imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$JurisprudenciasComentadas->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($JurisprudenciasComentadas->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
            @$JurisprudenciasComentadas->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$JurisprudenciasComentadas->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($JurisprudenciasComentadas->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
                }
            @$JurisprudenciasComentadas->imagen_rectangular=@$imagen;
        }
        $JurisprudenciasComentadas->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$JurisprudenciasComentadas->categoria_id)
                                    ->first();
        $JurisprudenciasComentadas->Emisores=DB::table('emisores')->where('emisorId', $JurisprudenciasComentadas->emisorId)->first();
        $JurisprudenciasComentadas->NotasJurisprudenciasComentadas=DB::table($db_name2)->where('pro_id', $JurisprudenciasComentadas->id)->get();
        return $JurisprudenciasComentadas;
    }
    /**
     * Funcion para retornar por fecha las jurisprudencias comentadas
     */
    public function GetFechaJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                $db_name_categoria="civil_categoria_jurisprudencias";
                $db_name2="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                $db_name_categoria="penal_categoria_jurisprudencias";
                $db_name2="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                $db_name_categoria="constitucional_categoria_jurisprudencias";
                $db_name2="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                $db_name_categoria="juridica_categoria_jurisprudencias";
                $db_name2="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                $db_name_categoria="jurisprudencia_categoria_jurisprudencias";
                $db_name2="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                $db_name_categoria="solucion_categoria_jurisprudencias";
                $db_name2="notas_solucion_jurisprudencias";
                break;
        }
        // hacer consulta ala base de datos
        $JurisprudenciasComentadas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->whereDate($db_name.'.created_at','=',$args['fecha'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);

        foreach(@$JurisprudenciasComentadas as $articuloJuridico){
            // generar rutas para las imagenes
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
            $articuloJuridico->Emisores=DB::table('emisores')->where('emisorId', $articuloJuridico->emisorId)->first();
            $articuloJuridico->NotasJurisprudenciasComentadas=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }  
        return ['nroTotal_items'=>$JurisprudenciasComentadas->total(),'data'=>$JurisprudenciasComentadas];
    }
    /**
     * Funcion para retornar por categoria las jurisprudencias comentadas
     */
    public function GetCategoriaJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /// Bloque donde escoge el nombre de la tabla segun el tipo de productos
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                $db_name_categoria="civil_categoria_jurisprudencias";
                $db_name2="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                $db_name_categoria="penal_categoria_jurisprudencias";
                $db_name2="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                $db_name_categoria="constitucional_categoria_jurisprudencias";
                $db_name2="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                $db_name_categoria="juridica_categoria_jurisprudencias";
                $db_name2="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                $db_name_categoria="jurisprudencia_categoria_jurisprudencias";
                $db_name2="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                $db_name_categoria="solucion_categoria_jurisprudencias";
                $db_name2="notas_solucion_jurisprudencias";
                break;
        }
        // hacer consulta ala base de ddatos
        $JurisprudenciasComentadas=null;
        if($args['slug']!=""){
            $JurisprudenciasComentadas=DB::table($db_name)
                ->join($db_name_categoria,$db_name_categoria.'.id','=',$db_name.'.categoria_id')
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name_categoria.'.slug',$args['slug'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        else{
            $JurisprudenciasComentadas=DB::table($db_name)
                ->join($db_name_categoria,$db_name_categoria.'.id','=',$db_name.'.categoria_id')
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        foreach(@$JurisprudenciasComentadas as $articuloJuridico){
            //generar rutas para las imagenes
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
            $articuloJuridico->Emisores=DB::table('emisores')->where('emisorId', $articuloJuridico->emisorId)->first();
            $articuloJuridico->NotasJurisprudenciasComentadas=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }  
        return ['nroTotal_items'=>$JurisprudenciasComentadas->total(),'data'=>$JurisprudenciasComentadas];
    }
    public function GetKeywordsJurisprudenciasComentadas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_jurisprudencias";
                $db_name_categoria="civil_categoria_jurisprudencias";
                $db_name2="notas_civil_jurisprudencias";
                break;
            case 2:
                $db_name="penal_jurisprudencias";
                $db_name_categoria="penal_categoria_jurisprudencias";
                $db_name2="notas_penal_jurisprudencias";
                break;
            case 3:
                $db_name="constitucional_jurisprudencias";
                $db_name_categoria="constitucional_categoria_jurisprudencias";
                $db_name2="notas_constitucional_jurisprudencias";
                break;
            case 4:
                $db_name="juridica_jurisprudencias";
                $db_name_categoria="juridica_categoria_jurisprudencias";
                $db_name2="notas_juridica_jurisprudencias";
                break;
            case 5:
                $db_name="jurisprudencia_jurisprudencias";
                $db_name_categoria="jurisprudencia_categoria_jurisprudencias";
                $db_name2="notas_jurisprudencia_jurisprudencias";
                break;
            case 6:
                $db_name="solucion_jurisprudencias";
                $db_name_categoria="solucion_categoria_jurisprudencias";
                $db_name2="notas_solucion_jurisprudencias";
                break;
        }
        // hacer consulta ala base de datos
        $JurisprudenciasComentadas=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.keyword','like','%'.$args['keyword'].'%')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);

        foreach(@$JurisprudenciasComentadas as $articuloJuridico){
            // generar rutas para las imagenes
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
            $articuloJuridico->Emisores=DB::table('emisores')->where('emisorId', $articuloJuridico->emisorId)->first();
            $articuloJuridico->NotasJurisprudenciasComentadas=DB::table($db_name2)->where('pro_id', $articuloJuridico->id)->get();
        }  
        return ['nroTotal_items'=>$JurisprudenciasComentadas->total(),'data'=>$JurisprudenciasComentadas];
    }
}
