cards = document.querySelectorAll('.flip-card');

let hasSelectedCard = false; //van kijelölt kártya
let lockBoard = false;
let selectedCard, enlargedCard;
let randomSeed = 1234;

enlargedCard = document.getElementById("big-card");


function Random(seed) {
  this._seed = seed % 2147483647;
  if (this._seed <= 0) this._seed += 2147483646;
}


Random.prototype.next = function () {
  return this._seed = this._seed * 16807 % 2147483647;
};


Random.prototype.nextFloat = function (opt_minOrMax, opt_max) {
  return (this.next() - 1) / 2147483646;
};


function selectCard() {
  if (lockBoard) return;
  if (this === selectedCard) return;

  hasSelectedCard = true;
  selectedCard = this;
  lockBoard = true;
  setTimeout(()=>{
    var src = this.children[1].src;
    enlargedCard.children[1].src = src.replace("/small/","/big/");
    //console.log(this.children[1].src);
    enlargedCard.classList.remove('flip');
    },100);
    lockBoard= false;
}

function viewOldCard(){
  if (lockBoard) return;
  hasSelectedCard = false;
  selectedCard = null;
  enlargedCard.classList.add('flip');
  var src = this.children[0].src;
  enlargedCard.children[0].src = src.replace("/small/","/big/");
}

function flipCard() {
  if (lockBoard) return;
  if  (!hasSelectedCard) return;
  lockBoard = true;
  var src = selectedCard.children[0].src;
  enlargedCard.children[0].src = src.replace("/small/","/big/");
  this.classList.add('flip');
  selectedCard.classList.add('flip');
  hasSelectedCard = false;
  lockBoard = false;
  revealCard();
}

function revealCard() {
  selectedCard.removeEventListener('click', selectCard);
  selectedCard.addEventListener('click', viewOldCard);
  resetBoard();
}

function resetBoard() {
  [hasFlippedCard, lockBoard] = [false, false];
  selectedCard = null;
}

function restartGame(){
  resetBoard();
  shuffle();
  cards.forEach(card => card.removeEventListener('click', viewOldCard));
  cards.forEach(card => card.addEventListener('click', selectCard));
  enlargedCard.addEventListener('click',flipCard);
  enlargedCard.classList.remove('flip');
  enlargedCard.children[1].src = "img/MOODCARDS.jpg";
  cards.forEach(card => card.classList.remove('flip'));
}

function restartBtnClick() {
  if(confirm("Újra szeretnéd kezdeni a játékot?")){
    restartGame();
  }
}

function shuffle() {
  cards.forEach(card => {
    //let randomPos = Math.floor(myrng.nextFloat() * cards.length);
    let randomPos = Math.floor(Math.random() * cards.length);
    card.style.order = randomPos;
  });
}

function showgameInfo(){
  var gameInfoText= "Zárt felhasználású kártyajáték.\n";
  gameInfoText+= "1: Kártya előnézethez/kiválaszsásához kattints egy kis kártyára.\n"
  gameInfoText+= "2: A kiválasztott kártya felfordításához kattints a nagy kártyára.\n"
  gameInfoText+= "3: A játék újrakezdéséhez kattints az 'Újrakezd.' gombra.\n"
  gameInfoText+= "Azonos kártyaleosztás azonos jelmondat megadásával lehetséges. (fejlesztés alatt...)"

  window.alert(gameInfoText);
}

function stringToSeed(init_string){
  var _count_ = 5;
  var vals = [];
  if(init_string=="") init_string = "Life, the universe, and everything";
  if(init_string.length<_count_) init_string=init_string.repeat(_count_);
  var i = Math.max(Math.floor(init_string.length/_count_),1);
  do{ 
    vals.push(init_string.substring(0, Math.min(i,init_string.length)).charCodeAt(0));
    init_string = init_string.substring(i, init_string.length);
  } 
  while( init_string != "" );

  //console.log(vals);
  var seed_value = Math.floor(vals.reduce((a,b)=>(a*b*3),1)); 

  if(seed_value % 2 == 0) return seed_value+1;
  return seed_value;
}


function setRandomSeed(){
  var tb = document.getElementById("txtSeedText");
  var newRandomSeed = stringToSeed(tb.value);
  if(newRandomSeed != randomSeed){
    console.log(randomSeed);
    if(confirm("Újra szeretnéd kezdeni a játékot a megadott jelmondattal?")){
      restartGame();
    }
  }
}

restartGame();
//showgameInfo();