<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="Hero Game">
        <meta name="author" content="Mihail Cuculici, mihai.cuculici@gmail.com">

        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">


        <script type="text/javascript" src="assets/js/p5.js"></script>
        <script type="text/javascript" src="assets/js/p5.dom.js"></script>
        <script type="text/javascript" src="assets/js/p5.sound.js"></script>
        <script type="text/javascript" src="assets/js/jquery.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
        <meta charset="utf-8" />

    </head>
    <body>
        <div class="right width-50">
            <div class="playerControl left">
                <label>Game delay: </label><input type="text" class="input" id="delayInput" value="2000" />
                <i class="far fa-play-circle controlBtn newGame" title="New Simulation"></i>
                <i class="fas fa-reply-all controlBtn replayGame" title="Replay Existing Simulation"></i>
            </div>
        </div>
        <script src="assets/js/script.js"></script>
        <script type="text/javascript">;
            $("body").on("p5Ready", function (evt) {
                $("#delayInput").on("change", updateSpeed);
                $(".newGame").click(function (e) {
                    if (!playingGame){
                        newScript();
                    } else {
                        alert("Please, wait for the simulation to end before asking for a new one!")
                    }
                });
                $(".replayGame").click(function (e) {
                    if (!playingGame){
                        runScript();
                    } else {
                        alert("Please, wait for the simulation to end before playing it again!")
                    }
                });
            });
        </script>
    </body>
</html>



