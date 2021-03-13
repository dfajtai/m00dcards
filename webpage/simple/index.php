[<?php

        $CARD_COUNT = 62; #number of cards in deck
        ?>

<!DOCTYPE html>
<html lang="hu">

<head>
        <meta charset="UTF-8">

        <title>Kártyajáték</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="styles.css">
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</head>

<body>
        <div id="welcome" class="modal" tabindex="-1">
                <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title">M00dcards</h5>
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

        <section class="flip-card-game-grid">
                <?php
                $nums = range(1, $CARD_COUNT);
                shuffle($nums);
                foreach ($nums as $i) { ?>
                        <div class="flip-card" data-rel="img<?php printf('%02d', $i) ?>.jpg">
                                <img class="front-face" src="img/small/front/img<?php printf('%02d', $i) ?>.jpg" alt="?" />
                                <img class="back-face" src="img/small/back/img<?php printf('%02d', $i) ?>.jpg" alt="?" />
                        </div>
                <?php } ?>

        </section>

        <section class="big-flip-card-ground">
                <div class="big-flip-card" id="big-card">
                        <img class="big-front-face" src="img/MOODCARDS.jpg" alt="?" />
                        <img class="big-back-face" src="img/MOODCARDS.jpg" alt="?" />
                </div>
<!---
                <div class="game-controls">
                        <button type="button" onclick="restartBtnClick()">Újrakezd.</button><br><br>
                        <input type="text" id="txtSeedText" value="Árvíztűrő tükörfúrógép" width="200px">
                        <button type="button" onclick="setRandomSeed()">Jelmondat megadása.</button>
                </div>
--->
        </section>

        <script>
                $(document).ready(function() {
                        $('#welcome').modal('show');
                });
        </script>
        <script src="scripts.js"></script>


</body>


</html>