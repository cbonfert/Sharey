<?php
require_once('classes/PLZ.php');
require_once('classes/Tag.php');
require_once('classes/Offer.php');

//get a specific offer as JSON-data

if(isset($_POST['offerID']) && !empty($_POST['offerID'])){
    //call offer-function getOffer
    $offer = Offer::getOffer(intval($_POST['offerID']));

    if(!empty($offer)){
        $return = json_encode(array(
            'offerAvailable' => true,
            'offer' => $offer->toJson()));
        echo $return;
    }else{
        $return = json_encode(array(
            'offerAvailable' => false));
        echo $return;
    }

}else{
    $return = json_encode(array(
        'offerAvailable' => false));
    echo $return;
}

?>