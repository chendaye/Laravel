<?php
//Route::get('/', '[控制器@方法]');
Route::post('/posts', '\App\Http\Controllers\PostController@index');
/*
 <from action = '/posts' method = 'post'>
</from>
 */

//表单提交只支持 get put 如果要支持rest的其他方法
Route::put('/posts', '\App\Http\Controllers\PostController@index');
/*
 <from action = '/posts' method = 'post'>
<input type='hidden' name='_method' value="PUT">
</from>
 <from action = '/posts' method = 'post'>
{{method_field="PUT"}}
</from>
 */


//任意路由
Route::any('/posts', '\App\Http\Controllers\PostController@index');
//匹配路由
Route::match(['get', 'post'] ,'/posts', '\App\Http\Controllers\PostController@index');


//路由参数
Route::get('/posts/{id}', '\App\Http\Controllers\PostController@index');
function index($id){}

//模型绑定
//普通
Route::show('/posts/{id}', '\App\Http\Controllers\PostController@show');
function show($id){
    //找到文章
    \App\posts::find($id);
}
//模型传递
//posts => 表   posts => 主键：id  laravel 默认 可设置           
Route::show('/posts/{posts}', '\App\Http\Controllers\PostController@show');
function show(\App\posts $posts){

}



//路由分组
Route::group(['prefix' => 'posts'], function(){
    Route::get('/', '\App\Http\Controllers\PostController@index');
    Route::post('/{id}', '\App\Http\Controllers\PostController@index');
    Route::put('/{id}', '\App\Http\Controllers\PostController@index');
});
?>