let myFont;
let heroImg;
let beastImg;
let healthImg;
let attackImg;
let defenseImg;
let luckImg;

let heroStats;
let beastStats;
let beastName;
let heroName;
let heroIt = 0;
let beastIt = 0;

let scenario;
let script;

let scenarioScript;
let scriptDiv;

var intervalTime = 2000;

var shouldContinue = true;

let playingGame = false;

function updateSpeed() {
    var delay = parseInt($("#delayInput").val());
    if (!isNaN(delay) && delay > 0) {
        intervalTime = delay;
    }
    $("#delayInput").val(intervalTime);
}

function preload() {
    myFont = loadFont('assets/Pokemon_GB.ttf');
    heroImg = loadImage('assets/heroes/' + heroIt + '.png');
    beastImg = loadImage('assets/beasts/' + beastIt + '.png');
    healthImg = loadImage('assets/stats/health.png');
    attackImg = loadImage('assets/stats/attack.png');
    defenseImg = loadImage('assets/stats/defense.png');
    luckImg = loadImage('assets/stats/luck.png');
}

function resetChars() {
    heroImg = loadImage('assets/heroes/' + heroIt + '.png');
    beastImg = loadImage('assets/beasts/' + beastIt + '.png');
    heroStats = scenario["player1"]["initialStats"];
    beastStats = scenario["player2"]["initialStats"];
}

/**
 * Copied from http://jsfiddle.net/KJQ9K/554/
 * @param {type} json
 * @returns {unresolved}
 */
function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

function setup() {
    createCanvas(windowWidth * 0.5, windowHeight * 0.65);
    background(0);
    drawHero();
    fill("#FF9900");
    textFont(myFont);
    textSize(50);
    text("The Hero Game!", 50, height / 2 - 20, width - 80, height / 2);
    
    scenarioDiv = createDiv();
    scriptDiv = createDiv();
    scenarioDiv.id("scenario");
    scenarioDiv.class("height-46vh width-50 left");
    scriptDiv.id("script");
    scriptDiv.class("height-46vh width-50 right");
    
    $("body").trigger("p5Ready");
}

function drawHero() {
    image(heroImg, 30, height - 150, 100, 100);
}

function drawOpponent() {
    image(beastImg, width - 180, 80, 100, 100);
}

function writeHeroStats() {
    writeStats(heroName, heroStats, 30, height - 45, width - 180, 30);
}

function writeAnimalStats() {
    writeStats(beastName, beastStats, 100, 30, width - 140, 30);
}

function writeStats(playerName, stats, left, top, width, height) {
    stroke(255);
    fill(255);
    rect(left, top, width, height, 20);
    fill(0);
    textFont(myFont);

    playerName += ": ";

    let fontSize = 15;
    let health = stats["health"].toFixed(1);
    let strength = stats["strength"].toFixed(1);
    let defense = stats["defense"].toFixed(1);
    let luck = stats["luck"].toFixed(1);

    textSize(fontSize);

    text(playerName, left + 10, top + 20);

    leftOffset = left + (playerName.length) * fontSize;
    image(healthImg, leftOffset, top + 5, 20, 20);
    leftOffset += 25;
    text(health, leftOffset, top + 20);
    leftOffset += 10 + (health.length + 1) * fontSize;
    image(attackImg, leftOffset, top + 5, 20, 20);
    leftOffset += 25;
    text(strength, leftOffset, top + 20);
    leftOffset += 10 + (luck.length + 1) * fontSize;
    image(defenseImg, leftOffset, top + 5, 20, 20);
    leftOffset += 25;
    text(defense, leftOffset, top + 20);
    leftOffset += 10 + (defense.length + 1) * fontSize;
    image(luckImg, leftOffset, top + 5, 20, 20);
    leftOffset += 25;
    text(luck, leftOffset, top + 20);
}

function writeText(str, color, size, left, top) {
    background(0);
    drawHero();
    drawOpponent();
    writeHeroStats();
    writeAnimalStats();
    fill(color);
    textFont(myFont);
    textSize(size);
    text(str, left, top, width - 130, height / 2);
}

function runScriptStep(step) {
    var delay = 0;
    let lastAttack = false;
    if (script["attacks"] && script["attacks"][step]) {
        let attacks = script["attacks"][step];
        attacks.forEach((attack) => {
            delay += intervalTime;
            setTimeout(function () {
                lastAttack = attack;
                writeText(attack, "#FFFFFF", 20, 100, height / 2 - 25);
            }, delay);
        });
        setTimeout(function () {
            attackerStats = scenario["gameplay"][step]["attacker"]["stats"];
            defenderStats = scenario["gameplay"][step]["defender"]["stats"];
            defenderNewStats = scenario["gameplay"][step]["defender"]["newStats"];
            if (scenario["gameplay"][step]["attacker"]["id"] == scenario["player1"]["id"]) {
                heroStats = attackerStats;
                beastStats = defenderNewStats;
            } else {
                heroStats = defenderNewStats;
                beastStats = attackerStats;
            }
            if (lastAttack) {
                // write the last text again to update the stats
                writeText(lastAttack, "#FFFFFF", 20, 100, height / 2 - 25);
            }
        }, delay);
    }
    return delay;
}

function runIntro() {
    return new Promise((resolve, reject) => {
        if (!shouldContinue) {
            resolve(0);
            return;
        }
        resetChars();
        var delay = 0;
        script["intro"].forEach((text) => {
            delay += intervalTime;
            setTimeout(function () {
                writeText(text, "#FF9900", 30, 50, height / 2 - 100);
            }, delay);
        });
        setTimeout(() => {
            resolve(delay)
        }, delay);
    });
}

function runBattle() {
    return new Promise((resolve, reject) => {
        if (!shouldContinue) {
            resolve(0);
            return;
        }
        var delay = 0;
        if (script["attacks"]) {
            script["attacks"].forEach((attacks, it) => {
                setTimeout(function () {
                    if (shouldContinue) {
                        runScriptStep(it);
                    }
                }, delay);
                delay += intervalTime * attacks.length;
            });
        }
        setTimeout(() => {
            resolve(delay)
        }, delay);
    })
}

function runEnding() {
    return new Promise((resolve, reject) => {
        if (!shouldContinue) {
            resolve(0);
            return;
        }
        var delay = 0;
        if (script["ending"]) {
            setTimeout(function () {
                writeText(script["ending"], "#FF9900", 30, 50, height / 2 - 100);
                if (scenario["winner"] == scenario["player1"]["id"]) { // player 1 wins
                    beastImg.filter(GRAY);
                    drawOpponent();
                } else {
                    heroImg.filter(GRAY);
                    drawHero();
                }

            }, delay);
        }
        setTimeout(() => {
            resolve(delay)
        }, delay);
    })
}

async function newScript() {    
    shouldContinue = false;
    setTimeout(function () {
        shouldContinue = true;

        $.ajax({
            dataType: "json",
            url: "/game.php",
            data: {},
            success: async function (data) {
                heroIt = data["heroIt"];
                beastIt = data["beastIt"];
                script = data["script"];
                scenario = data["scenario"];

                heroName = scenario["player1"]["id"];
                beastName = scenario["player2"]["id"];

                scenarioDiv.html("<pre>" + syntaxHighlight(JSON.stringify(scenario, undefined, 4)) + "</pre>"); // spacing level = 2)
                //    scenarioDiv.html("<pre>"+(JSON.stringify(scenario, undefined, 4))+"</pre>"); // spacing level = 2)
                scriptDiv.html("<pre>" + syntaxHighlight(JSON.stringify(script, undefined, 4)) + "</pre>"); // spacing level = 2)
                //    scriptDiv.html("<pre>"+(JSON.stringify(script, undefined, 4))+"</pre>"); // spacing level = 2)

                runScript();
            },
            error: function (err) {
                alert("Cannot get game info from the server!");
                console.error(err);
            }
        });
    }, intervalTime + 1);
}

async function runScript() {
    
    playingGame = true;
    
    if (!scenario) {
        newScript();
    } else {
        await runIntro();
        await runBattle();
        setTimeout(async () => {
            await runEnding();
            
            playingGame = false;
        }, intervalTime);
    }
}

async function pauseScript() {
    shouldContinue = !shouldContinue;
}