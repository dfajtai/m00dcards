<?php
$CARD_COUNT = 62; #number of cards in deck
$init_state = range(1, $CARD_COUNT);
$current_state = array();
$state_counter = 0;
$selected_index = -1;
session_start();
require_once 'vendor/autoload.php';
require_once 'php/init_functions.php';
include 'php/connect.php';
include 'php/create_tables.php';
include 'php/init_session.php';
include 'php/get_state.php';
include 'php/post_state.php';
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
                                                <p>Kártya előnézethez/kiválaszsásához kattints egy kis kártyára.</p>
                                                <p>A kiválasztott kártya felfordításához kattints a nagy kártyára.</p>
                                                <p>A játék újrakezdéséhez kattints az 'Új játék' gombra.</p>
                                                <p>A játék állapota az URL másolásával segítségével megosztható, menthető.</p>
                                        </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                </div>
                        </div>
                </div>

                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <a class="navbar-brand" href="#">m00dcards</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                        <li class="nav-item active">
                                                <a id="btnRestart" class="nav-link" href="#">Új játék</span></a>
                                        </li>
                                </ul>
                        </div>
                </nav>

                <div class="container">
                        <div class="row mb-3">
                                <div class="col-md-8 ">
                                        <?php
                                        $position = 0;
                                        foreach ($init_state as $i) { ?>
                                                <button id="sc<?php echo $position ?>" class="small-card" data-rel="img<?php printf('%02d', $i) ?>.jpg" data-index=<?php echo $i ?> data-position=<?php echo $position ?>>
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
                                        <?php $position += 1;
                                        } ?>
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
                                </div>
                        </div>
        </main>

        <script>
                function update_big_card(small_card) {
                        var smallhover = small_card.querySelector('.smallhover');
                        var imgUrl = $(small_card).data('rel');
                        var selectedIndex = $(small_card).data('index');
                        var selectedPosition = $(small_card).data('position');
                        $("#big-card").html('<div class="bighover panel"><div class="front"><div class="pad"><img src="img/big/back/' + imgUrl + '"></div></div><div class="back"><div class="pad" id="big-card-back"><img src="img/big/front/' + imgUrl + '"/></div></div></div>');
                        if (smallhover.classList.contains('flip')) {
                                $('.bighover').addClass('flip');
                        } else {
                                $('.bighover').click(function() {
                                        $(this).addClass('flip');
                                        $(smallhover).addClass('flip');
                                        js_selected_index = selectedPosition;
                                        //post status to database
                                        $.ajax({
                                                type: "POST",
                                                url: "php/post_state.php",
                                                data: {
                                                        new_state: selectedPosition,
                                                        gameID: '<?php echo $gameID; ?>',
                                                        current_state: JSON.stringify(js_current_state)
                                                },
                                                dataType: "json",
                                                success: function(data) {
                                                        var unstaged = data["unstaged"];
                                                        if (unstaged) {
                                                                Array.from(unstaged).forEach(element => {
                                                                        var sc = $('.small-card[data-position="' + element + '"]');
                                                                        if (sc) {
                                                                                sc.children('.smallhover').addClass("flip");
                                                                        }
                                                                });
                                                        }
                                                        js_current_state = data["current_state"];
                                                }
                                        });
                                });
                        }

                }

                var js_current_state = JSON.parse('<?php echo json_encode($current_state); ?>');
                Array.from(js_current_state).forEach(element => {
                        var sc = $('#sc' + element);
                        if (sc) {
                                var smallhover = sc.children('.smallhover');
                                smallhover.addClass("flip");
                        }
                });
                var js_selected_index = JSON.parse('<?php echo json_encode($selected_index); ?>');
                if (js_selected_index != -1) {
                        var sc = document.getElementById("sc" + js_selected_index);

                        if (sc) {
                                update_big_card(sc);
                        }
                }

                $(".small-card").click(function() {
                        var small_card = this;
                        update_big_card(small_card);
                        var selectedPosition = $(small_card).data('position');
                        $.ajax({
                                type: "POST",
                                url: "php/post_state.php",
                                data: {
                                        selection_changed: selectedPosition,
                                        gameID: '<?php echo $gameID; ?>',
                                        current_state: JSON.stringify(js_current_state)
                                },
                                dataType: "json",
                                success: function(data) {
                                        var unstaged = data["unstaged"];
                                        if (unstaged) {
                                                Array.from(unstaged).forEach(element => {
                                                        var sc = $('.small-card[data-position="' + element + '"]');
                                                        if (sc) {
                                                                sc.children('.smallhover').addClass("flip");
                                                        }
                                                });
                                        }
                                        js_current_state = data["current_state"];
                                }
                        });
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