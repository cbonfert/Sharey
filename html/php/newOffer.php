<?php 
        require_once('classes/User.php');
        require_once('classes/PLZ.php');
        require_once('classes/Tag.php');
        require_once('classes/Offer.php');
        require_once('classes/Message.php');
        require_once('classes/Conversation.php');

    session_start();
    if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
        if(isset($_POST['title']) && !empty($_POST['title'])
        && isset($_POST['desc']) && !empty($_POST['desc'])
        && isset($_POST['plzID']) && !empty($_POST['plzID'])
        && isset($_POST['tagID']) && !empty($_POST['tagID'])){
            
            $imageData = null;

            //add imagedate if image is set:
            if(isset($_POST['img']) && !empty($_POST['img']) && ($_POST['img'] != "") && (strpos($_POST['img'], 'data:image') === 0)){
                $image = $_POST['img'];

                //clear sended image-string
                if(strpos($image, 'data:image/jpeg;base64,') === 0){
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                }elseif(strpos($image, 'data:image/jpg;base64,') === 0){
                    $image = str_replace('data:image/jpg;base64,', '', $image);
                }elseif(strpos($image, 'data:image/png;base64,') === 0){
                    $image = str_replace('data:image/png;base64,', '', $image);
                }else{
                    header("Location: ../error.php?errormessage=Bitte nur Bilder in den Formaten <strong>jpg, jpeg und png</strong> auswählen.");
                    exit;
                }

                //convert base64 img-data to binary-img-data
                $image = str_replace(' ', '+', $image);
                $imageData = addslashes(base64_decode($image));
            }

            //add expectationdate if is set
            $expdate = new DateTime('0000-00-00');
            if(isset($_POST['expdate']) && !empty($_POST['expdate'])){
                $expdate = new DateTime($_POST['expdate']);
            }

            //call user-function createOffer
            $success = $_SESSION['user']->createOffer($_POST['desc'], $expdate, $_POST['title'], intval($_POST['tagID']), $imageData, intval($_POST['plzID']));
            
            if($success){
                header("Location: ../account.php");
            }else{
                header("Location: ../error.php?errormessage=Es tut uns leid, leider konnte das Angebot nicht angelegt werden, bitte versuche es nochmal.");
                exit;
            }
                    
        }else{
            header("Location: ../error.php?errormessage=Es tut uns leid, leider konnte das Angebot nicht angelegt werden, bitte versuche es nochmal.");
            exit;
        }
    }

    
?>