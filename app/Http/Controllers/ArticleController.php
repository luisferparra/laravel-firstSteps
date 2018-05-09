<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller {
    
    public function lftest() {
        return response()->json(array('error'=>1,'msg'=>'jola caracola'), 201);
    }

    //
    public function index() {
        /**
         * Modificamos la respuesta para dar un json más acorde a lo que queremos o buscamos...
         * El truco para devolver es response()->json(ARRAY DE DATOS, CÓDIGO RESPUESTA)
         */
        $ret = array('error'=>0,'data'=>Article::all());
        return response()->json($ret,200);
//        return Article::all();
    }

    public function show(Article $article) {
        return $article;
//        return Article::find($id);
    }

    public function store(Request $request) {
        $article = Article::create($request->all());

        return response()->json($article, 201);
//        return Article::create($request->all());
    }

    public function update(Request $request, Article $article) {
        $article->update($request->all());

        return response()->json($article, 200);
//        $article = Article::findOrFail($id);
//        $article->update($request->all());
//
//        return $article;
    }

    public function delete(Article $article) {
        $article->delete();

        return response()->json(null, 204);
//        $article = Article::findOrFail($id);
//        $article->delete();
//
//        return 204;
    }

}
