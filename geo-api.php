<?php


$key = "539dde176b69c758f78d412e5378974b";



if(isset($_GET['request']) and $_GET['request']=='all_country'){
    $endpoint = file_get_contents("https://battuta.medunes.net/api/country/all/?key=".$key);
    //@file_put_contents('geo/countries.json',$endpoint);
    echo $endpoint;
}


if(isset($_GET['request']) and $_GET['request']=='all_regions' and isset($_GET['country'])){
    $country = $_GET['country'];
    $endpoint = file_get_contents("https://battuta.medunes.net/api/region/".$country."/all/?key=".$key);
    echo $endpoint;
    //@file_put_contents('geo/'.$country.'.json',$endpoint);
}


if(isset($_GET['request']) and $_GET['request']=='all_cities' and isset($_GET['region']) and isset($_GET['country'])){
    $region = $_GET['region'];
    $country = $_GET['country'];
    $endpoint = file_get_contents("https://battuta.medunes.net/api/city/".$country."/all/?region=".$region."&key=".$key);
    echo $endpoint;
}


