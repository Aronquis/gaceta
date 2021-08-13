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
class CrudAutores
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
        //Validar un slug repetido
        $slug=Str::slug($args['nombreAutor']);
        $j=1;
        $buscar_slug=DB::table('autores')
                ->where('slugAutor',Str::slug($args['nombreAutor']))->first();
        while(isset($buscar_slug->autorId)==true){
            $slug=Str::slug($args['nombreAutor']).'-'.$j;
            $buscar_slug=DB::table('autores')
                            ->where('slugAutor',$slug)->first();
            $j+=1;
        }
        $id=DB::table('autores')->insertGetId([
            'nombreAutor'=>$args['nombreAutor'],
            'slugAutor'=>$slug,
            'keywordsAutor'=>$args['keywordsAutor'],
            'descripcionAutor'=>$args['descripcionAutor'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $autor=DB::table('autores')->where('autorId',$id)->first();
        return $autor;
    }
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        /*
        $slug=Str::slug($args['nombreAutor']);
        $j=1;
        $buscar_slug=DB::table('autores')
                ->where('slugAutor',Str::slug($args['nombreAutor']))->first();
        while(isset($buscar_slug->autorId)==true){
            $slug=Str::slug($args['nombreAutor']).'-'.$j;
            $buscar_slug=DB::table('autores')
                            ->where('slugAutor',$slug)->first();
            $j+=1;
        }
        */
        $id=DB::table('autores')->where('autorId',$args['autorId'])->update([
            'nombreAutor'=>$args['nombreAutor'],
            'slugAutor'=>Str::slug($args['nombreAutor']),
            'keywordsAutor'=>$args['keywordsAutor'],
            'descripcionAutor'=>$args['descripcionAutor'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $autor=DB::table('autores')->where('autorId',$args['autorId'])->first();
        return $autor;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $autor=DB::table('autores')->where('autorId',$args['autorId'])->first();
        if(isset($autor->autorId)==true){
            DB::table('autores')->where('autorId',$args['autorId'])->delete();
            return "ELIMINADO";
        }
        else{
            return "ELIMINADO";
        }
        
    }
}
