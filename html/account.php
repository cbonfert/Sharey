<?php
    require_once('php/classes/User.php');
    require_once('php/classes/Offer.php');
    require_once('php/classes/Tag.php');
    require_once('php/classes/Conversation.php');
    require_once('php/classes/Message.php');
    require_once('php/classes/PLZ.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>Account</title>
    <?php
        include('basicsiteelements/header.php');
    ?>

    <script type="text/javascript" src="js/accountDeleteOffer.js"></script>
</head>

<body>
    <!-- Loading Container -->
    <div id="loadingContainer">
        <div id="loadingOverlay"></div>

        <div id="loading" class="row justify-content-center">
            <div class="col-10 col-md-6">
                <div class="progress" id="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                        style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Lädt...
                    </div>
                </div>
            </div>

        </div>

    </div>

    <?php
        include('basicsiteelements/navigationpages.php');
        include('modal/modalLogin.php');
    ?>

    <div class="content">
        <!-- Div content for padding-top (header) -->
        <div class="container">

            <?php
            if(isset($_SESSION['user']) && !empty($_SESSION['user'])){ 
                //only show page if a user is logged in
        ?>

            <div class="container fixed-top" id="conversationButtons">
                <div class="row">
                    <div class="col-10">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-secondary float-right" title="Zurück"
                            onclick="history.go(-1)"><i class=" fas fa-times"></i></button>
                    </div>

                </div>
            </div>

            <div class="row mt-4">

                <div class="col-lg-6">
                    <h1>Account</h1>
                    <!--account features not implemented yet-->
                    <ul class="list-unstyled">
                        <li><a title="Passwort ändern" onclick="alert('Diese Funktion ist im Prototypen nicht implementiert.');">Passwort ändern</a></li>
                        <li><a title="E-Mail ändern" onclick="alert('Diese Funktion ist im Prototypen nicht implementiert.');">E-Mail ändern</a></li>
                        <li><a title="Account löschen" onclick="alert('Diese Funktion ist im Prototypen nicht implementiert.');"> Account löschen</a></li>
                    </ul>
                </div>

                <div id="message" class="col-lg-6">
                    <h1>Nachrichten</h1>
                    <ul class="list-unstyled">
                        <!--feature not implemented yet-->
                        <li>E-Mail-Benachrichtigung
                            <div class="form-check" id="checkbox">
                                <input type="checkbox" class="form-check-input" onclick="alert('Diese Funktion ist im Prototypen nicht implementiert.');">
                            </div>
                        </li>
                    </ul>

                    <!--print conversations with last message-->
                    <?php
                                $conversations = $_SESSION['user']->getConversations();

                                if(!empty($conversations)){
                                    $currentOfferID = 0;
                                    $acceptorCounter = 1;
                                    $offerCounter = 0;

                                    foreach($conversations as $conversation){ 
                                        //if offerID of current conversation equals to offerID of the previous conversation --> add only the lastMessage
                                        if(!empty($conversation->getLastMessage())){ 
                                            //if in the conversation actually a message is present
                                                                                        
                                            if($conversation->getOfferID() == $currentOfferID){
                                                //color for timestamp-field:
                                                $colorTimeStamp = "timestampDark";   
                                                if(intval($offerCounter)%2 == 0){
                                                    $colorTimeStamp = "timestampLight";
                                                }

                                                echo '  <form method="POST" action="conversation.php#anker">
                                                            <input type="text" hidden required name="conID" value="'.$conversation->getConID().'" />
                                                            <div class="col-12">
                                                                <button class="btn shadow-none" type="submit">
                                                                    <div style="display: inline;">@'.$acceptorCounter.' '.$conversation->getLastMessage()->getContent().'</div>
                                                                    <div class="float-right" id="'.$colorTimeStamp.'">'.$conversation->getLastMessage()->getDate()->format('Y-m-d H:i').'</div>
                                                                </button>
                                                            </div>
                                                        </form>';
                                            }else{ 
                                                //if offerID of current conversation not equals to offerID of the previous conversation --> add a new title and then the lastMessage
                                                
                                                $offerCounter++;

                                                if($currentOfferID != 0){
                                                    echo '</div>'; //closing div of the group of conversations of one offer
                                                }

                                                $acceptorCounter = 1;

                                                //color for offer-group:
                                                $colorOfferGroup = "messageDark";  
                                                if(intval($offerCounter)%2 == 0){
                                                    $colorOfferGroup = "messageLight";
                                                }

                                                //color for timestamp-field:
                                                $colorTimeStamp = "timestampDark"; 
                                                if(intval($offerCounter)%2 == 0){
                                                    $colorTimeStamp = "timestampLight";
                                                }

                                                //messageText is bold, if last message isn't readed
                                                $messageUnreaded = "";
                                                if($conversation->getLastMessage()->getMessageRead() == false  
                                                    && $conversation->getLastMessage()->getSenderID() != $_SESSION['user']->getUserID()){
                                                        $messageUnreaded = "font-weight-bold"; //set this class to last message textdiv
                                                }

                                                //add "ich" if sender of lastMessage equals to currentUser
                                                $messageSendedByCurrentUser = "";
                                                if($conversation->getLastMessage()->getSenderID() == $_SESSION['user']->getUserID()){
                                                    $messageSendedByCurrentUser = "<i>Ich:</i> ";
                                                }

                                                echo '<div class="card" id="'.$colorOfferGroup.'">
                                                        <h4>'.$conversation->getOfferTitle().'</h4>
                                                        <form method="POST" action="conversation.php#anker">
                                                            <input type="text" hidden required name="conID" value="'.$conversation->getConID().'" />
                                                            <div class="col-12">
                                                                <button class="btn shadow-none" type="submit">
                                                                    <div class="'.$messageUnreaded.'" style="display: inline;">@'.$acceptorCounter.' '.$messageSendedByCurrentUser.$conversation->getLastMessage()->getContent().'</div>
                                                                    <div class="float-right" id="'.$colorTimeStamp.'">'.$conversation->getLastMessage()->getDate()->format('Y-m-d H:i').'</div>
                                                                </button>
                                                            </div>
                                                        </form>';

                                                $currentOfferID = $conversation->getOfferID();
                                            }

                                            $acceptorCounter++;
                                        }
                                    }
                                    echo '</div>'; //closing div for last offer-con-container
                                }
                            
                            ?>

                </div>

            </div>

            <div class="row mt-4">

                <div class="col-sm-12">
                    <h1>Eigene Angebote</h1>
                    <div class="container mt-4">
                        <div id="offerContainerAccount" class="row justify-content-center">

                            <?php 
                                    $offers = $_SESSION['user']->getOwnOffers();

                                    //print own offers:
                                    if(!empty($offers)){
                                        foreach($offers as $offer){
                                            echo '      <div id="'.$offer->getOfferID().'" class="col-auto m-3 card offerCardSize" style="background-color:'.$offer->getTag()->getColor().'">
                                                            <div id="cardContent">
                                                                <div class="row">
                                                                    <div class="col-7">
                                                                        <div class="row">
                                                                            <div id="offerTagDiv">
                                                                                <svg width="150px" height="55px">
                                                                                    <polygon points="10,30 30,10 140,10 140,50 30,50" id="offerTagPolygon"/>
                                                                                    <text x="40" y="36" fill="white">'.utf8_decode($offer->getTag()->getDescription()).'</text>
                                                                                </svg>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="=col-auto">
                                                                                <div id="locationTagDiv">
                                                                                    <i class="fas fa-map-marker-alt" id="offerLocationTag"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <div id="offerSiteCityDiv">
                                                                                    <div id="offerLocationDiv" class="whiteText locationDiv">'.utf8_decode($offer->getPlz()->getLocation()).'</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <br>
                                                                        <br>';

                                            if(!empty($offer->getPicture())){
                                                echo                    '<img src="data:image/jpeg;base64,'.base64_encode( $offer->getPicture() ).'" id="offerImage">';
                                            }  

                                            echo                   '</div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div id="accountSiteOfferDescription">
                                                                            <h5 class="whiteText">'.utf8_decode($offer->getTitle()).'</h5>
                                                                            <p class="whiteText">'.utf8_decode($offer->getDescription()).'</p>
                                                                        </div>

                                                                        <div id="symbolsDiv">
                                                                            <button type="button" class="buttonSymbols" onclick="alert(\'Diese Funktion ist im Prototypen nicht implementiert.\');">
                                                                                <i class="fas fa-edit" id="editSymbol"></i>
                                                                            </button>
                                                                            <button type="button" class="buttonSymbols" onclick="deleteOffer('.$offer->getOfferID().');">
                                                                                <i class="fas fa-trash" id="deleteSymbol"></i>
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                            ?>

                        </div>
                    </div>

                </div>

            </div>

            <?php                    
            }else{
                echo '<br><p>Du bist nicht eingeloggt. Bitte <a data-toggle="modal" data-target="#loginModal" title="Login"><strong>melde dich an</strong></a>, um auf deinen Account zuzugreifen.<br>
                </p>';
            }
        ?>

        </div>

    </div>

    <?php
        include('basicsiteelements/scripts.php');
    ?>

    <script type="text/javascript" src="js/dynamicFontSizeLibary.js"></script>

    <script type="text/javascript">
        //fit location font-size
        $(".locationDiv").boxfit({align_center:false, align_middle:false, maximum_font_size: 16});
    </script>
</body>

</html>