<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Return post by user.
     *
     * @return \Illuminate\Http\Response
     */
    public function postByUser(Request $user)
    {
        $user = json_decode($user->getContent());
        $posts = Post::orderBy('updated_at', 'DESC')->where('author',$user)->get();
        return response()->json(['exito' => true, 'msg' => 'Consulta ejecutada!', 'posts' => $posts],200);
    }

    /**
     * Return all posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function posts()
    {
        $posts = Post::orderBy('updated_at', 'DESC')->get();
        return response()->json(['exito' => true, 'msg' => 'Consulta ejecutada!', 'posts' => $posts],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $post)
    {
        Post::create(json_decode($post->getContent(), true));
        return response()->json(['exito' => true, 'msg' => 'Publicación creada correctamente!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $data)
    {
        
        $id = json_decode($data->getContent())->idPost;
        $user = json_decode($data->getContent())->user;
        $post = Post::find($id);
        $update = [];
        $update["content"] = json_decode($data->getContent())->content;
        if($post->author == $user){
            $post->update($update);
        }else{
            return response()->json(['exito' => true, 'msg' => 'Upps ha ocurrido un error!'],200);
        }
        return response()->json(['exito' => true, 'msg' => 'Publicación actualizada correctamente!'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $datos)
    {
        $id = json_decode($datos->getContent())->idPost;
        $user = json_decode($datos->getContent())->user;

        $post = Post::find($id);
        if($post->author == $user){
            $post->delete();
        }else{
            return response()->json(['exito' => true, 'msg' => 'Upps ha ocurrido un error!'],200);
        }
        return response()->json(['exito' => true, 'msg' => 'Publicación eliminada correctamente!'],200);
    }

    /**
     * Like a post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function likePost(Request $data)
    {
        $idPost = json_decode($data->getContent())->idPost;
        $user = json_decode($data->getContent())->user;
        $post = Post::find($idPost);
        $update = [];
        $like = false;
        if($post->like_user && strlen($post->like_user) > 1){
            $users = explode("-", $post->like_user);
            if(in_array($user, $users)){
                $update["likes"] = $post->likes - 1;
                unset($users[array_search($user,$users)]);
                $user_init = $users[0];
                if(count($users)>1){
                    for($i = 1, $cantUsers = count($users); $i<$cantUsers; $i++){
                        $users = $user_init.'-'.$users[$i];
                    }
                }else{
                    $users = $user_init;
                }
                $update["like_user"] = $users;
            }else{
                $update["likes"] = $post->likes + 1;
                $update["like_user"] = $post->like_user."-".$user;
                $like = true;
            }
        }else if($post->like_user && $post->likes > 0){
            if($user == $post->like_user){
                $update["likes"] = $post->likes - 1;
                $update["like_user"] = NULL;
            }else{
                $update["likes"] = $post->likes + 1;
                $update["like_user"] = $post->like_user."-".$user;
                $like = true;
            }
        }else if($post->like_user == NULL){
            $update["likes"] = $post->likes + 1;
            $update["like_user"] = $user;
            $like = true;
        }else{
            $update["likes"] = 0;
        }
        $post->update($update);
        return response()->json(['like' => $like, 'cant' => $post->likes], 200);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required'
        ];
    }

    public function messages()
    {
        $requerido = 'este campo es requerido';
        return [
            'content.required' => $requerido,
        ];
    }
}
