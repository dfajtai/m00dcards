const cards = document.querySelectorAll('.flip-card');

let hasSelectedCard = false; //van kijelölt kártya
let lockBoard = false;
let selectedCard, enlargedCard;

enlargedCard = document.getElementById("big-card");

function selectCard() {
  if (lockBoard) return;
  if (this === selectedCard) return;

  hasSelectedCard = true;
  selectedCard = this;
  lockBoard = true;
  setTimeout(()=>{  
    oldsrc = enlargedCard.children[0].src;
    enlargedCard.children[1].src = this.children[1].src;
    enlargedCard.classList.remove('flip');
    },100);
    lockBoard= false;
}

function viewOldCard(){
  if (lockBoard) return;
  if (this === selectedCard) return;

  hasSelectedCard = false;
  selectedCard = null;
  enlargedCard.children[0].src = this.children[0].src;
}

function flipCard() {
  if (lockBoard) return;
  if  (!hasSelectedCard) return;
  lockBoard = true;
  enlargedCard.children[0].src = selectedCard.children[0].src;
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

function restartBtnClick() {
  shuffle();
  cards.forEach(card => card.addEventListener('click', selectCard));
  cards.forEach(card => card.classList.remove('flip'));
  enlargedCard.front
  resetBoard();
}

function shuffle() {
  cards.forEach(card => {
    let randomPos = Math.floor(Math.random() * 56);
    card.style.order = randomPos;
  });
}

shuffle();
cards.forEach(card => card.addEventListener('click', selectCard));
enlargedCard.addEventListener('click',flipCard);