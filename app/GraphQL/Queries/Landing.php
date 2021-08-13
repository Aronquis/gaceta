<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class Landing
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
     * Funcion para retornar la landing segun el tipo de subproducto
     */
    public function GetLanding($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // hacer consulta ala base de datos
        $recup_landing=DB::table('landing')->where('producto_id',$args['tipo'])->first();
        //seccion 1
        $recup_landing->seccion1=\json_decode($recup_landing->seccion1);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion1->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($recup_landing->seccion1->imagen_fondo)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion1->imagen_fondo=@$imagen;
        }
        for ($i=0; $i < @\count($recup_landing->seccion1->galeria); $i++) { 
            @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion1->galeria[$i])->first();
            @$year=date("Y",strtotime(@$imagen->created_at));
            @$mes=date("m", strtotime(@$imagen->created_at));
            if(isset($imagen->id)==true){
                if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
                $recup_landing->seccion1->galeria[$i]= @$imagen;
            }
        }
        // seccion 2
        $recup_landing->seccion2=\json_decode($recup_landing->seccion2);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion2->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion2->imagen_fondo=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion2->imagen_principal)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion2->imagen_principal=@$imagen;
        }
        //seccion 3
        $recup_landing->seccion3=\json_decode($recup_landing->seccion3);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion3->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion3->imagen_fondo=@$imagen;
        }
        if(isset($recup_landing->seccion3->itemInput)==true){
            $recup_landing->seccion3->item=@$recup_landing->seccion3->itemInput;
            foreach($recup_landing->seccion3->item as $items){
                @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$items->icono)->first();
                @$year=date("Y",strtotime(@$imagen->created_at));
                @$mes=date("m", strtotime(@$imagen->created_at));
                if(isset($imagen->id)==true){
                    if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
                    @$items->icono=@$imagen;
                }
            }
        }
        //seccion 4
        $recup_landing->seccion4=\json_decode($recup_landing->seccion4);
        if(isset($recup_landing->seccion4->itemInput)==true){
            $recup_landing->seccion4->item=$recup_landing->seccion4->itemInput;
            foreach($recup_landing->seccion4->item as $items){
                @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$items->icono)->first();
                @$year=date("Y",strtotime(@$imagen->created_at));
                @$mes=date("m", strtotime(@$imagen->created_at));
                if(isset($imagen->id)==true){
                    if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
                    @$items->icono=@$imagen;
                }
            }
        }
        
        //seccion 5
        $recup_landing->seccion5=\json_decode($recup_landing->seccion5);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion5->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion5->imagen_fondo=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion5->imagen_principal)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion5->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion5->imagen_secundaria)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion5->imagen_secundaria=@$imagen;
        }
        //seccion 6
        $recup_landing->seccion6=\json_decode($recup_landing->seccion6);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion6->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion6->imagen_fondo=@$imagen;
        }
        //seccion 7
        $recup_landing->seccion7=\json_decode($recup_landing->seccion7);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion7->imagen_principal)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion7->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion7->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion7->imagen_fondo=@$imagen;
        }
        if(isset($recup_landing->seccion7->item1Input)==true){
            $recup_landing->seccion7->item1=$recup_landing->seccion7->item1Input;
            foreach($recup_landing->seccion7->item1 as $items){
                @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$items->imagen)->first();
                @$year=date("Y",strtotime(@$imagen->created_at));
                @$mes=date("m", strtotime(@$imagen->created_at));
                if(isset($imagen->id)==true){
                    if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
                    @$items->imagen=@$imagen;
                }
            }
        }
        // seccion 8
        $recup_landing->seccion8=\json_decode($recup_landing->seccion8);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion8->imagen_principal)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion8->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion8->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion8->imagen_fondo=@$imagen;
        }

        for ($i=0; $i <@\count($recup_landing->seccion8->galeria); $i++) { 
            @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion8->galeria[$i])->first();
            @$year=date("Y",strtotime(@$imagen->created_at));
            @$mes=date("m", strtotime(@$imagen->created_at));
            if(isset($imagen->id)==true){
                if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
                $recup_landing->seccion8->galeria[$i]=@$imagen;
            }
        }
        // seccion 9
        
        $recup_landing->seccion9=\json_decode($recup_landing->seccion9);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion9->imagen)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion9->imagen=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion9->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion9->imagen_fondo=@$imagen;
        }
        for ($i=0; $i <@\count($recup_landing->seccion9->galeria); $i++) { 
            @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion9->galeria[$i])->first();
            @$year=date("Y",strtotime(@$imagen->created_at));
            @$mes=date("m", strtotime(@$imagen->created_at));
            if(isset($imagen->id)==true){
                if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
                $recup_landing->seccion9->galeria[$i]=@$imagen;
            }
        }
        // seccion 10
        $recup_landing->seccion10=\json_decode($recup_landing->seccion10);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion10->imagen_principal)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion10->imagen_principal=@$imagen;
        }
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion10->imagen_fondo)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion10->imagen_fondo=@$imagen;
        }
        // seccion 11
        $recup_landing->seccion11=\json_decode($recup_landing->seccion11);
        @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion11->imagen_principal)->first();
        @$year=date("Y",strtotime(@$imagen->created_at));
        @$mes=date("m", strtotime(@$imagen->created_at));
        if(isset($imagen->id)==true){
            if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
            @$recup_landing->seccion11->imagen_principal=@$imagen;
        }
        // seccion 12
        $recup_landing->seccion12=\json_decode($recup_landing->seccion12);
        for ($i=0; $i <@\count($recup_landing->seccion12->galeria); $i++) { 
            @$imagen=DB::table('imagenes')->where('imagenes.id',@(Int)@$recup_landing->seccion12->galeria[$i])->first();
            @$year=date("Y",strtotime(@$imagen->created_at));
            @$mes=date("m", strtotime(@$imagen->created_at));
            if(isset($imagen->id)==true){
                if(@$imagen->tipo_imagen==0){
                @$imagen->url=env('APP_URL').'imagenesGenerales/'.$year.'-'.$mes.'/'.$imagen->url;
            }
                $recup_landing->seccion12->galeria[$i]=@$imagen;
            }
        }
        return $recup_landing;
    }
}
