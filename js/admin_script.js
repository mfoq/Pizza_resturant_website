//Start Burger menu navbar part 
let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick =  () => {
    navbar.classList.toggle("active");
    profile.classList.remove('active');
}

//Ends Burger menu navbar part 




//Start User Acoount navbar part 
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active');
    navbar.classList.remove('active');
};

//Ends User Acoount navbar part 


//when scroll window
window.onscroll = () =>{
    navbar.classList.remove('active');
    profile.classList.remove('active');
}