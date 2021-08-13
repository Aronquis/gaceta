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
class CrudEmisores
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
        $slug=Str::slug($args['nombreEmisor']);
        $j=1;
        $buscar_slug=DB::table('emisores')
                ->where('slugEmisor',Str::slug($args['nombreEmisor']))->first();
        while(isset($buscar_slug->emisorId)==true){
            $slug=Str::slug($args['nombreEmisor']).'-'.$j;
            $buscar_slug=DB::table('emisores')
                            ->where('slugEmisor',$slug)->first();
            $j+=1;
        }
        $id=DB::table('emisores')->insertGetId([
            'nombreEmisor'=>$args['nombreEmisor'],
            'slugEmisor'=>$slug,
            'keywordsEmisor'=>$args['keywordsEmisor'],
            'descripcionEmisor'=>$args['descripcionEmisor'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $editor=DB::table('emisores')->where('emisorId',$id)->first();
        return $editor;
    }
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $id=DB::table('emisores')->where('emisorId',$args['emisorId'])->update([
            'nombreEmisor'=>$args['nombreEmisor'],
            'slugEmisor'=>Str::slug($args['nombreEmisor']),
            'keywordsEmisor'=>$args['keywordsEmisor'],
            'descripcionEmisor'=>$args['descripcionEmisor'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $editor=DB::table('emisores')->where('emisorId',$args['emisorId'])->first();
        return $editor;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $editor=DB::table('emisores')->where('emisorId',$args['emisorId'])->first();
        if(isset($editor->emisorId)==true){
            DB::table('emisores')->where('emisorId',$args['emisorId'])->Delete();
            return "ELIMINADO";
        }
        else{
            return "ERROR";
        }
        
    }
}
