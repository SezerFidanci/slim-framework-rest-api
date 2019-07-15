<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

 

    $app->get('/tumkitaplar', function ($request, $response, $args) {
        $sth = $this->db->prepare("SELECT * FROM kitaplar" );
        $sth->execute();
        $result = $sth->fetchAll();
 
         if(!empty($result))
         {
             return $this->response->withStatus(200)->withHeader("Content-Type","application/json")->withJson($result);
         }
         else
         {
             $errorArray= array('status' => 'False','message' =>'Kitap bulunamadi' );
             return $this->response->withStatus(200)->withHeader("Content-Type","application/json")->withJson($errorArray);
         }
    });


    $app->get('/kitap/{id}', function ($request, $response, $args) {

        $id = $request->getAttribute('id');
        $sth = $this->db->prepare("SELECT * FROM kitaplar WHERE id=:id" );
        $sth->execute(array(':id'=>$id));
        $result = $sth->fetchAll();
 
         if(!empty($result))
         {
             return $this->response->withStatus(200)->withHeader("Content-Type","application/json")->withJson($result);
         }
         else
         {
             $errorArray= array('status' => 'False','message' =>'Kitap bulunamadi' );
             return $this->response->withStatus(200)->withHeader("Content-Type","application/json")->withJson($errorArray);
         }
    });


    $app->post('/kitapekle', function ($request, $response, $args) {


        try{

        $kit_adi  = $request->getParam('kit_adi');
        $kit_yazar  = $request->getParam('kit_yazar');
        $kit_isbn  = $request->getParam('kit_isbn');
        $kit_sayfa  = $request->getParam('kit_sayfa');
    
        $sth = $this->db->prepare("INSERT INTO kitaplar (kit_adi,kit_yazar,kit_isbn,kit_sayfa) VALUES (?,?,?,?)" );
        $status = $sth->execute(array($kit_adi, $kit_yazar, $kit_isbn,$kit_sayfa));
      
        if(!empty($status) && $status==1)
        {
            $successArray= array('status' => 'True','message' =>'Kitap Eklendi' );
            return $this->response->withStatus(200)->withHeader("Content-Type","application/json")->withJson($successArray);
        }
        else
        {
            $errorArray= array('status' => 'False','message' =>'Kitap Eklenemedi' );
            return $this->response->withStatus(200)->withHeader("Content-Type","application/json")->withJson($errorArray);
        }

        }
        catch(PDOException $ex)
        {
            echo $ex->getMessage();
        }
        
        
     });
};
