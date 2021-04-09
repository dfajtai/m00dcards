cards = document.querySelectorAll('.flip-card');

let hasSelectedCard = false; //van kijelölt kártya
let lockBoard = false;
var selectedCard, bigCard = null;
let randomSeed = 1234;


bigCard = document.getElementById("big-card");


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
  var fileName = $(this).data('rel');
  bigCard.children[1].src = "img/big/back/"+fileName;

  setTimeout(()=>{  
    bigCard.classList.remove('flip');
    lockBoard= false;
  },100);
  
}

function viewOldCard(){
  if (lockBoard) return;
  hasSelectedCard = false;
  selectedCard = null;
  bigCard.classList.add('flip');
  var fileName = $(this).data('rel');
  bigCard.children[0].src = "img/big/front/"+fileName;
}

function flipCard() {
  if (lockBoard) return;
  if  (!hasSelectedCard) return;
  lockBoard = true;

  var fileName = $(selectedCard).data('rel');
  bigCard.children[0].src = "img/big/front/"+fileName;

  setTimeout(()=>{  
    this.classList.add('flip');
    selectedCard.classList.add('flip');
    hasSelectedCard = false;
    lockBoard = false;
    revealCard();
  },100);

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
  //shuffle_cards();
  cards.forEach(card => card.removeEventListener('click', viewOldCard));
  cards.forEach(card => card.addEventListener('click', selectCard));
  bigCard.addEventListener('click',flipCard);
  bigCard.classList.remove('flip');
  bigCard.children[1].src = "img/MOODCARDS.jpg";
  cards.forEach(card => card.classList.remove('flip'));
}

function restartBtnClick() {
  if(confirm("Újra szeretnéd kezdeni a játékot?")){
    $.ajax({
      url : 'new_session.php',
      type : 'GET',
      success : function(result) {
        console.log(result);
        $("#txtSessionId").val(result);
        location.reload();
      },
      error : function() {
         console.log('error');
      }
    });
  }
}

function shuffle_cards() {
  console.log("shuffling");
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
