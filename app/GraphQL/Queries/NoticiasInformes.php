<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class NoticiasInformes
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
     *Funcion para retornar todas las noticias,opiniones y informes 
     */
    public function GetAllNoticiasInformes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_noticias_informes_opiniones";
                $db_name_categoria="civil_categoria_noticias_informes_opiniones";
                $db_name2="notas_civil_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="penal_noticias_informes_opiniones";
                $db_name_categoria="penal_categoria_noticias_informes_opiniones";
                $db_name2="notas_penal_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="constitucional_noticias_informes_opiniones";
                $db_name_categoria="constitucional_categoria_noticias_informes_opiniones";
                $db_name2="notas_constitucional_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="juridica_noticias_informes_opiniones";
                $db_name_categoria="juridica_categoria_noticias_informes_opiniones";
                $db_name2="notas_juridica_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="jurisprudencia_noticias_informes_opiniones";
                $db_name_categoria="jurisprudencia_categoria_noticias_informes_opiniones";
                $db_name2="notas_jurisprudencia_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="solucion_noticias_informes_opiniones";
                $db_name_categoria="solucion_categoria_noticias_informes_opiniones";
                $db_name2="notas_solucion_noticias_informes_opiniones";
                break;
        }
        //hacer consulta ala base de datos
        $noticiasInformes=null;
        if($args['slugAutor']!=""){
            $noticiasInformes=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.slugAutor',$args['slugAutor'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }
        else{
            $noticiasInformes=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        }

        foreach(@$noticiasInformes as $noticiaInforme){
            // generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_rectangular=@$imagen;
            }
            $noticiaInforme->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$noticiaInforme->categoria_id)
                                    ->first();
            $noticiaInforme->Autores=DB::table('autores')->where('autorId', $noticiaInforme->autorId)->first();
            $noticiaInforme->NotasNoticiasInformesOpiniones=DB::table($db_name2)->where('pro_id', $noticiaInforme->id)->get();
        }
        return ['nroTotal_items'=>$noticiasInformes->total(),'data'=>$noticiasInformes];
    }
    public function GetBusquedaAvanzadaNoticiasInformes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_noticias_informes_opiniones";
                $db_name_categoria="civil_categoria_noticias_informes_opiniones";
                $db_name2="notas_civil_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="penal_noticias_informes_opiniones";
                $db_name_categoria="penal_categoria_noticias_informes_opiniones";
                $db_name2="notas_penal_noticias_informes_opiniones";
                break;
            case 3:
                $db_name="constitucional_noticias_informes_opiniones";
                $db_name_categoria="constitucional_categoria_noticias_informes_opiniones";
                $db_name2="notas_constitucional_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="juridica_noticias_informes_opiniones";
                $db_name_categoria="juridica_categoria_noticias_informes_opiniones";
                $db_name2="notas_juridica_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="jurisprudencia_noticias_informes_opiniones";
                $db_name_categoria="jurisprudencia_categoria_noticias_informes_opiniones";
                $db_name2="notas_jurisprudencia_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="solucion_noticias_informes_opiniones";
                $db_name_categoria="solucion_categoria_noticias_informes_opiniones";
                $db_name2="notas_solucion_noticias_informes_opiniones";
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

            foreach(@$resultados as $noticiaInforme){
                // generar rutas para las imagenes
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_principal)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($noticiaInforme->imagen_principal)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$noticiaInforme->imagen_principal=@$imagen;
                }
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_rectangular)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($noticiaInforme->imagen_rectangular)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$noticiaInforme->imagen_rectangular=@$imagen;
                }
                $noticiaInforme->Categorias=DB::table($db_name_categoria)
                                        ->select($db_name_categoria.'.*')
                                        ->where($db_name_categoria.'.id',@$noticiaInforme->categoria_id)
                                        ->first();
                $noticiaInforme->Autores=DB::table('autores')->where('autorId', $noticiaInforme->autorId)->first();
                $noticiaInforme->NotasNoticiasInformesOpiniones=DB::table($db_name2)->where('pro_id', $noticiaInforme->id)->get();
            }

            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];

        }
        else{

            foreach(@$resultados as $noticiaInforme){
                // generar rutas para las imagenes
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_principal)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($noticiaInforme->imagen_principal)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$noticiaInforme->imagen_principal=@$imagen;
                }
                @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_rectangular)->first();
                @$year=date("Y",strtotime($imagen->created_at));
                @$mes=date("m", strtotime($imagen->created_at));
                if(isset($noticiaInforme->imagen_rectangular)==true){
                    if(@$imagen->tipo_imagen==0){
                        @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                    }
                    @$noticiaInforme->imagen_rectangular=@$imagen;
                }
                $noticiaInforme->Categorias=DB::table($db_name_categoria)
                                        ->select($db_name_categoria.'.*')
                                        ->where($db_name_categoria.'.id',@$noticiaInforme->categoria_id)
                                        ->first();
                $noticiaInforme->Autores=DB::table('autores')->where('autorId', $noticiaInforme->autorId)->first();
                $noticiaInforme->NotasNoticiasInformesOpiniones=DB::table($db_name2)->where('pro_id', $noticiaInforme->id)->get();
            }

            $nroItems=\count($resultados);
            $resultados = $resultados->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['nroTotal_items'=>$nroItems,'data'=>$resultados];
        }
        
        
    }
    /**
     * Funcion para retornar por slug
     */
    public function GetSlugNoticiasInformes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Bloque donde escoge el nombre de la tabla segun el tipo de producto
        $db_name="";
        $db_name_categoria="";
        $db_name2="";
        switch ($args['tipo']) {
            case 1:
                $db_name="civil_noticias_informes_opiniones";
                $db_name_categoria="civil_categoria_noticias_informes_opiniones";
                $db_name2="notas_civil_noticias_informes_opiniones";
                break;
            case 2:
                $db_name="penal_noticias_informes_opiniones";
                $db_name_categoria="penal_categoria_noticias_informes_opiniones";
                $db_name2="notas_penal_noticias_informes_opiniones";
                break; 
            case 3:
                $db_name="constitucional_noticias_informes_opiniones";
                $db_name_categoria="constitucional_categoria_noticias_informes_opiniones";
                $db_name2="notas_constitucional_noticias_informes_opiniones";
                break;
            case 4:
                $db_name="juridica_noticias_informes_opiniones";
                $db_name_categoria="juridica_categoria_noticias_informes_opiniones";
                $db_name2="notas_juridica_noticias_informes_opiniones";
                break;
            case 5:
                $db_name="jurisprudencia_noticias_informes_opiniones";
                $db_name_categoria="jurisprudencia_categoria_noticias_informes_opiniones";
                $db_name2="notas_jurisprudencia_noticias_informes_opiniones";
                break;
            case 6:
                $db_name="solucion_noticias_informes_opiniones";
                $db_name_categoria="solucion_categoria_noticias_informes_opiniones";
                $db_name2="notas_solucion_noticias_informes_opiniones";
                break;
        }
        // hacer consulta ala base de datos
        $noticiasInformes=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.slug',$args['slug'])
                ->first();
        // generar rutas para las imagenes
        @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiasInformes->imagen_principal)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($noticiasInformes->imagen_principal)==true){
            if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
            @$noticiasInformes->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)$noticiasInformes->imagen_rectangular)->first();
        @$year=date("Y",strtotime($imagen->created_at));
        @$mes=date("m", strtotime($imagen->created_at));
        if(isset($noticiasInformes->imagen_rectangular)==true){
            if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
            @$noticiasInformes->imagen_rectangular=@$imagen;
        }
        @$noticiasInformes->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$noticiasInformes->categoria_id)
                                    ->first();
        @$noticiasInformes->Autores=DB::table('autores')->where('autorId', $noticiasInformes->autorId)->first();
        @$noticiasInformes->NotasNoticiasInformesOpiniones=DB::table($db_name2)->where('pro_id', $noticiasInformes->id)->get();
        return $noticiasInformes;
    }
    /**
     * Funcion para retornar por fecha todas las noticias,informes y opiniones
     */
    public function GetFechaNoticiasInformes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
         // Bloque donde escoge el nombre de la tabla segun el tipo de producto
         $db_name="";
         $db_name_categoria="";
         $db_name2="";
         switch ($args['tipo']) {
             case 1:
                 $db_name="civil_noticias_informes_opiniones";
                 $db_name_categoria="civil_categoria_noticias_informes_opiniones";
                 $db_name2="notas_civil_noticias_informes_opiniones";
                 break;
             case 2:
                 $db_name="penal_noticias_informes_opiniones";
                 $db_name_categoria="penal_categoria_noticias_informes_opiniones";
                 $db_name2="notas_penal_noticias_informes_opiniones";
                 break;
             case 3:
                 $db_name="constitucional_noticias_informes_opiniones";
                 $db_name_categoria="constitucional_categoria_noticias_informes_opiniones";
                 $db_name2="notas_constitucional_noticias_informes_opiniones";
                 break;
             case 4:
                 $db_name="juridica_noticias_informes_opiniones";
                 $db_name_categoria="juridica_categoria_noticias_informes_opiniones";
                 $db_name2="notas_juridica_noticias_informes_opiniones";
                 break;
             case 5:
                 $db_name="jurisprudencia_noticias_informes_opiniones";
                 $db_name_categoria="jurisprudencia_categoria_noticias_informes_opiniones";
                 $db_name2="notas_jurisprudencia_noticias_informes_opiniones";
                 break;
            case 6:
                $db_name="solucion_noticias_informes_opiniones";
                $db_name_categoria="solucion_categoria_noticias_informes_opiniones";
                $db_name2="notas_solucion_noticias_informes_opiniones";
                break;
         }
        // hacer consulta ala base de datos
        $noticiasInformes=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->whereDate($db_name.'.created_at','=',$args['fecha'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$noticiasInformes as $noticiaInforme){
            // generar las rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_rectangular=@$imagen;
            }
            $noticiaInforme->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$noticiaInforme->categoria_id)
                                    ->first();
            $noticiaInforme->Autores=DB::table('autores')->where('autorId', $noticiaInforme->autorId)->first();
            $noticiaInforme->NotasNoticiasInformesOpiniones=DB::table($db_name2)->where('pro_id', $noticiaInforme->id)->get();
        }  
        return ['nroTotal_items'=>$noticiasInformes->total(),'data'=>$noticiasInformes];
    }
    /**
     * Funcion para retornar por categoria las noticias,informes y opiniones
     */
    public function GetCategoriaNoticiasInformes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
         // Bloque donde escoge el nombre de la tabla segun el tipo de producto
         $db_name="";
         $db_name_categoria="";
         $db_name2="";
         switch ($args['tipo']) {
             case 1:
                 $db_name="civil_noticias_informes_opiniones";
                 $db_name_categoria="civil_categoria_noticias_informes_opiniones";
                 $db_name2="notas_civil_noticias_informes_opiniones";
                 break;
             case 2:
                 $db_name="penal_noticias_informes_opiniones";
                 $db_name_categoria="penal_categoria_noticias_informes_opiniones";
                 $db_name2="notas_penal_noticias_informes_opiniones";
                 break;
             case 3:
                 $db_name="constitucional_noticias_informes_opiniones";
                 $db_name_categoria="constitucional_categoria_noticias_informes_opiniones";
                 $db_name2="notas_constitucional_noticias_informes_opiniones";
                 break;
             case 4:
                 $db_name="juridica_noticias_informes_opiniones";
                 $db_name_categoria="juridica_categoria_noticias_informes_opiniones";
                 $db_name2="notas_juridica_noticias_informes_opiniones";
                 break;
             case 5:
                 $db_name="jurisprudencia_noticias_informes_opiniones";
                 $db_name_categoria="jurisprudencia_categoria_noticias_informes_opiniones";
                 $db_name2="notas_jurisprudencia_noticias_informes_opiniones";
                 break;
            case 6:
                $db_name="solucion_noticias_informes_opiniones";
                $db_name_categoria="solucion_categoria_noticias_informes_opiniones";
                $db_name2="notas_solucion_noticias_informes_opiniones";
                break;
         }
        // hascer consulta ala base de datos
        $noticiasInformes=DB::table($db_name)
                ->join($db_name_categoria,$db_name_categoria.'.id','=',$db_name.'.categoria_id')
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name_categoria.'.slug',$args['slug'])
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);

        foreach(@$noticiasInformes as $noticiaInforme){
            //generar rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_rectangular=@$imagen;
            }
            $noticiaInforme->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$noticiaInforme->categoria_id)
                                    ->first();
            $noticiaInforme->Autores=DB::table('autores')->where('autorId', $noticiaInforme->autorId)->first();
            $noticiaInforme->NotasNoticiasInformesOpiniones=DB::table($db_name2)->where('pro_id', $noticiaInforme->id)->get();
        }  
        return ['nroTotal_items'=>$noticiasInformes->total(),'data'=>$noticiasInformes];
    }
    public function GetKeywordsNoticiasInformes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
         // Bloque donde escoge el nombre de la tabla segun el tipo de producto
         $db_name="";
         $db_name_categoria="";
         $db_name2="";
         switch ($args['tipo']) {
             case 1:
                 $db_name="civil_noticias_informes_opiniones";
                 $db_name_categoria="civil_categoria_noticias_informes_opiniones";
                 $db_name2="notas_civil_noticias_informes_opiniones";
                 break;
             case 2:
                 $db_name="penal_noticias_informes_opiniones";
                 $db_name_categoria="penal_categoria_noticias_informes_opiniones";
                 $db_name2="notas_penal_noticias_informes_opiniones";
                 break;
             case 3:
                 $db_name="constitucional_noticias_informes_opiniones";
                 $db_name_categoria="constitucional_categoria_noticias_informes_opiniones";
                 $db_name2="notas_constitucional_noticias_informes_opiniones";
                 break;
             case 4:
                 $db_name="juridica_noticias_informes_opiniones";
                 $db_name_categoria="juridica_categoria_noticias_informes_opiniones";
                 $db_name2="notas_juridica_noticias_informes_opiniones";
                 break;
             case 5:
                 $db_name="jurisprudencia_noticias_informes_opiniones";
                 $db_name_categoria="jurisprudencia_categoria_noticias_informes_opiniones";
                 $db_name2="notas_jurisprudencia_noticias_informes_opiniones";
                 break;
            case 6:
                $db_name="solucion_noticias_informes_opiniones";
                $db_name_categoria="solucion_categoria_noticias_informes_opiniones";
                $db_name2="notas_solucion_noticias_informes_opiniones";
                break;
         }
        // hacer consulta ala base de datos
        $noticiasInformes=DB::table($db_name)
                ->select($db_name.'.*')
                ->orderBy($db_name.'.id', 'DESC')
                ->where($db_name.'.keyword','like','%'.$args['keyword'].'%')
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach(@$noticiasInformes as $noticiaInforme){
            // generar las rutas para las imagenes
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_principal)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_principal)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_principal=@$imagen;
            }
            @$imagen=DB::table('imagenes')->where('imagenes.id',(Int)$noticiaInforme->imagen_rectangular)->first();
            @$year=date("Y",strtotime($imagen->created_at));
            @$mes=date("m", strtotime($imagen->created_at));
            if(isset($noticiaInforme->imagen_rectangular)==true){
                if(@$imagen->tipo_imagen==0){
                    @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;     
                }
                @$noticiaInforme->imagen_rectangular=@$imagen;
            }
            $noticiaInforme->Categorias=DB::table($db_name_categoria)
                                    ->select($db_name_categoria.'.*')
                                    ->where($db_name_categoria.'.id',@$noticiaInforme->categoria_id)
                                    ->first();
            $noticiaInforme->Autores=DB::table('autores')->where('autorId', $noticiaInforme->autorId)->first();
            $noticiaInforme->NotasNoticiasInformesOpiniones=DB::table($db_name2)->where('pro_id', $noticiaInforme->id)->get();
        }  
        return ['nroTotal_items'=>$noticiasInformes->total(),'data'=>$noticiasInformes];
    }
}
