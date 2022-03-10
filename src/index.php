<?php

require("./config.php");
$app = new ExpressPHP\Express();

$app->get("/", function($req, $res){
	$res->send("Hello World");
});

$app->use("/v1", function($req, $res, $next){

	if($req->get("authorization") !== "Bearer " . SITE_TOKEN){
		return $res->status(400)->send([
			"status" => 400,
			"message" => "Token de authenticação invalido"
		]);
	}

});

$app->get("/v1/anime/:type/:action", function($req, $res){
	if($req->params->type == "animeyabu"){
		if($req->params->action == "find"){
			return \App\Controllers\AnimeYabu::find($req, $res);
		}
		if($req->params->action == "update"){
			return \App\Controllers\AnimeYabu::listUpdate($req, $res);
		}
	}
	return $res->status(404)->send([
		"status" => 404,
		"message" => "Page not found"
	]);
});

$app->listen();