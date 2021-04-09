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

restartGame();