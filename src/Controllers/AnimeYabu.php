<?php

namespace App\Controllers;
use \Goutte\Client as Goutte;

class AnimeYabu {

	const SITE_URL = "https://animeyabu.com";

	static public function update(){

		$jsonData = new DataItem();

		$httpClient = new Goutte();
		$response = $httpClient->request('GET', self::SITE_URL."/api/show.php");
		$jsonData->set("list", json_decode($response->text(), true));

		$response = $httpClient->request('GET', self::SITE_URL."/");
		$jsonData->set("updated", []);
		$response->filter('.phpvibe-video-list .video')->each(function($node) use ($jsonData) {
			$data = [];
			$data["thumb"] = $node->filter('img')->attr("src");
			$data["ep_name"] = $node->filter('.video-title')->text();
			$jsonData->push("updated", $data);
		});
		$data = $jsonData->getAll();
		$data["updated"] = [];
		$res = $jsonData->getAll();
		foreach($res["updated"] as $key => $value){
			$d = array_find($res["list"], function($e) use ($value){
				$z = $e["cover"] == $value["thumb"];
				return $z;
			});
			$d["ep_name"] = $value["ep_name"];
			$data["updated"][] = $d;
		}
		file_put_contents("db/animeyabu.json", json_encode($data, JSON_PRETTY_PRINT));
		print("\nAtualizado com sucesso\n");

	}

	static public function find($req, $res){

		if(!isset($req->query->name) || empty(trim($req->query->name))){
			return $res->status(400)->send([
				"status" => 400,
				"message" => "Query name not found"
			]);
		}

		$jsonData = json_decode(file_get_contents("db/animeyabu.json"), true);

		$name = $req->query->name;
		$data = array_find($jsonData["list"], function($e) use ($name){
			return $e["slug"] == $name;
		});
		if(is_null($data)){
			return $res->send([
				"status" => 60001,
				"data" => []
			]);
		}
		$data["url"] = self::SITE_URL."/assistir/".$data["slug"];
		$data["thumb"] = self::SITE_URL."/assistir/".$data["cover"];
		return $res->send([
			"status" => 200,
			"data" => $data
		]);

	}

	static public function listUpdate($req, $res){

		$jsonData = json_decode(file_get_contents("db/animeyabu.json"), true);

		$data = [];

		foreach($jsonData["updated"] as $value){
			$value["url"] = self::SITE_URL."/assistir/".$value["slug"];
			$value["thumb"] = self::SITE_URL."/".$value["cover"];
			$data[] = $value;
		}
		return $res->send([
			"status" => 200,
			"data" => $data
		]);

	}

}