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
class CrudEditores
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
        $slug=Str::slug($args['nombreEditor']);
        $j=1;
        $buscar_slug=DB::table('editores')
                ->where('slugEditor',Str::slug($args['nombreEditor']))->first();
        while(isset($buscar_slug->editorId)==true){
            $slug=Str::slug($args['nombreEditor']).'-'.$j;
            $buscar_slug=DB::table('editores')
                            ->where('slugEditor',$slug)->first();
            $j+=1;
        }
        $id=DB::table('editores')->insertGetId([
            'nombreEditor'=>$args['nombreEditor'],
            'slugEditor'=>$slug,
            'keywordsEditor'=>$args['keywordsEditor'],
            'descripcionEditor'=>$args['descripcionEditor'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $editor=DB::table('editores')->where('editorId',$id)->first();
        return $editor;
    }
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $id=DB::table('editores')->where('editorId',$args['editorId'])->update([
            'nombreEditor'=>$args['nombreEditor'],
            'slugEditor'=>Str::slug($args['nombreEditor']),
            'keywordsEditor'=>$args['keywordsEditor'],
            'descripcionEditor'=>$args['descripcionEditor'],
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $editor=DB::table('editores')->where('editorId',$args['editorId'])->first();
        return $editor;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $editor=DB::table('editores')->where('editorId',$args['editorId'])->first();
        if(isset($editor->editorId)==true){
            DB::table('editores')->where('editorId',$args['editorId'])->Delete();
            return "ELIMINADO";
        }
        else{
            return "ERROR";
        }
        
    }
}
