
//Start Burger menu navbar part 
let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick =  () => {
    navbar.classList.toggle("active");
}

//Ends Burger menu navbar part 




//Start User Acoount navbar part 
let account = document.querySelector('.user-account');

document.querySelector('#user-btn').onclick = () => {
    account.classList.add('active');
};

document.querySelector('#close-account').onclick = () => {
    account.classList.remove('active');
};

//Ends User Acoount navbar part 




//Starts My orders a side part 
let orders = document.querySelector('.my-orders');

document.querySelector('#order-btn').onclick = () => {
    orders.classList.toggle('active');
};

document.querySelector('#close-orders').onclick = () => {
    orders.classList.remove('active');
};
//ends My orders a side part


//Starts cart aside part 
let cart = document.querySelector('.shopping-cart');

document.querySelector('#cart-btn').onclick = () => {
    cart.classList.toggle('active');
};

document.querySelector('#close-cart').onclick = () => {
    cart.classList.remove('active');
};
//ends  cart aside part


//slider mechanisim (functionality)
let slides = document.querySelectorAll('.home-bg .home .slide-container .slide');
let index = 0;

function next(){
    slides[index].classList.remove('active');
    index = (index + 1) % slides.length;
    slides[index].classList.add('active');
}

function prev(){
    slides[index].classList.remove('active');
    index = (index - 1 + slides.length) % slides.length;
    slides[index].classList.add('active');
}



//Hide Asides (cart. orders, navbar) when scroll
window.onscroll = () => {
    navbar.classList.remove("active");
    orders.classList.remove('active');
    cart.classList.remove('active');
};


//Accordion functionality
let accordion = document.querySelectorAll('.faq .accordion-container .accordion');

accordion.forEach(function (el) {
    el.onclick = () => {
       accordion.forEach(el => {el.classList.remove('active')});
       el.classList.add('active');
    }
});