<?php

 require_once('lib/limonade.php');
 require_once('lib/facebook/src/facebook.php');
 require_once('lib/foursquare/src/FoursquareAPI.class.php');
 

 dispatch('/','home');

 function home()
{
 $facebook=new Facebook(array("appid"=>"186690998096099","appsecret"=>"cede4c05fa1b3adc1aaddb8a6005d0b2"));
  

  $user=$facebook->getUser();
}
 dispatch('/findPalce/:partner/:place','getPlace');
 function getPlace()
{
  $friends=$facebook->api('/me/friends/','GET');
  $friends_a=json_decode($friends,true);
  foreach($friends_a as $friend)
{
   if(params('partner')==$friend['name'])
    {
      $partner=$friend;
      getCheckin($partner);
     }
   else 
    {
      echo "Boy that takes some guts to ask out a girl,who is not even in your facebook list.";
     }
}
$location=params('place');
getSuggestions($location);
}

 function getCheckin($partner)
{
   $checkins=$facebook->api('/'+.$partner['id'].'/checkins/','GET');
   $checkins_a=json_decode($checkins,true);
 }

 function getSuggestions($location)
{
   $client_key="3BCHYET02RP3D4TYEVQLVDUPAKHMAF3CRT45X3MAVJREB11Q";
   $client_secret="T4DREJ31ZABXTOXOQWESYFEDSKQ3COOENUAPB0V2TS3OLWMA";
   $four=new FoursquareAPI($client_key,$client_secret);
   list($lat,$long)=$four->GeoLocate($location);
   $coords=array("ll"=>"$lat,$long");
   $response=$four->GetPublic("/venues/search",$coords);
   $venues=json_decode($response);
   foreach($venues->response->venues as $venue)
{
   if($venue->categories->name==("Restuarants"||"Dinner Places"||"Shopping Mall"||"Bowling Alley"||"Theatres"||"Multiplex"||"Coffee-House"));
   {
     $place->name=$venue->name;
     $place->id=$venue->id;
     $place->location=$venue->location->address;
    
   }
  
}
}
?>

