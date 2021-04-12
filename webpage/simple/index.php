<?php
$CARD_COUNT = 62; #number of cards in deck
require_once 'vendor/autoload.php';
include 'php/connect.php';
include 'php/create_tables.php';
include 'php/init_session.php';
include 'php/register_session.php';
?>

<!DOCTYPE html>
<html lang="hu">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>m00dcards</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/modernizr.min.js"></script>
        <link href="grid.css" rel="stylesheet">
</head>

<body class="py-4">
        <script>
                // For IE11. May we one day live without your BS.
                Modernizr.addTest('preserve3d', function() {
                        return Modernizr.testAllProps('transformStyle', 'preserve-3d');
                });
        </script>

        <main>
                <div id="welcome" class="modal" tabindex="-1">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <h5 class="modal-title">m00dcards</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tovább"></button>
                                        </div>
                                        <div class="modal-body">
                                                <p>Zárt felhasználású kártyajáték.</p>
                                                <p>1: Kártya előnézethez/kiválaszsásához kattints egy kis kártyára.</p>
                                                <p>2: A kiválasztott kártya felfordításához kattints a nagy kártyára.</p>
                                        </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                </div>
                        </div>
                </div>

                <div class="container">
                        <div class="row mb-3">
                                <div class="col-md-8 ">
                                        <?php
                                        foreach ($nums as $i) { ?>
                                                <button class="small-card" data-rel="img<?php printf('%02d', $i) ?>.jpg">
                                                        <div class="smallhover panel">
                                                                <div class="front">
                                                                        <div class="pad">
                                                                                <img src="img/small/back/img<?php printf('%02d', $i) ?>.jpg" alt="?">
                                                                        </div>
                                                                </div>
                                                                <div class="back">
                                                                        <div class="pad">
                                                                                <img src="img/small/front/img<?php printf('%02d', $i) ?>.jpg" alt="?" />
                                                                        </div>
                                                                </div>
                                                        </div>

                                                </button>
                                        <?php } ?>
                                </div>
                                <div class="col-md-4 ">
                                        <div class="big-card" id="big-card">
                                                <div class="bighover panel">
                                                        <div class="front">
                                                                <div class="pad">
                                                                        <img src="img/MOODCARDS.jpg" alt="?">
                                                                </div>
                                                        </div>
                                                        <div class="back">
                                                                <div class="pad" id="big-card-back">
                                                                        <img src="img/MOODCARDS.jpg" alt="?" />
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="game-controls">
                                                <button id="btnRestart">Újrakezd.</button>
                                        </div>
                                </div>
                        </div>
        </main>

        <script>
                $(".small-card").click(function() {
                        var imgUrl = $(this).data('rel');
                        var thumb = this;
                        var smallhover = thumb.querySelector('.smallhover');
                        $("#big-card").html('<div class="bighover panel"><div class="front"><div class="pad"><img src="img/big/back/' + imgUrl + '"></div></div><div class="back"><div class="pad" id="big-card-back"><img src="img/big/front/' + imgUrl + '"/></div></div></div>');
                        if (smallhover.classList.contains('flip')) {
                                $('.bighover').addClass('flip');
                        } else {
                                $('.bighover').click(function() {
                                        $(this).addClass('flip');
                                        $(smallhover).addClass('flip');
                                });
                        }

                });


                $(document).ready(function() {
                        $('#welcome').modal('show');
                        $('.bighover').click(function() {
                                $(this).addClass('flip');
                        });
                        $('#btnRestart').click(function() {
                                if (confirm("Újra szeretnéd kezdeni a játékot?")) {
                                        window.location.href = location.protocol + '//' + location.host + location.pathname;
                                }
                        });
                });
        </script>

</body>

</html>