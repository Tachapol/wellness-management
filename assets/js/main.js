// $( window ).on('scroll', function(){
//     var scrollTop = $(this).scrollTop()
//     var score = '15px'
//     if (scrollTop > 1) {
//         score = '5px 15px'
//     }
//     $('#navbar').css('padding', score)
// });

// $('.to-top').on('click', function (){
//     $('html, body').animate({scrollTop: '0px'}, 800);
// })

// $('jarallax').jarallax();

// let navbar = document.querySelector('.navbar');

// document.querySelector('#menu-btn').onclick = () =>{    
//     navbar.classList.toggle('active');
// }

document.addEventListener("DOMContentLoaded", function () {
    const header = document.querySelector(".header");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            header.classList.add("scrolled"); // Add the 'scrolled' class
        } else {
            header.classList.remove("scrolled"); // Remove the 'scrolled' class
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("menu-btn");
    const navbar = document.querySelector(".navbar");

    menuBtn.addEventListener("click", function () {
        navbar.classList.toggle("active");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const genderSelect = document.getElementById("gender");
    const genderLabel = document.getElementById("gender-label");

    genderSelect.addEventListener("change", function () {
        if (genderSelect.value) {
            genderLabel.style.display = "none"; // Hide the label
        } else {
            genderLabel.style.display = "block"; // Show the label if no value is selected
        }
    });
});

genderSelect.addEventListener("change", function () {
    if (genderSelect.value) {
        genderLabel.classList.add("hidden");
    } else {
        genderLabel.classList.remove("hidden");
    }
});

// Handle the "Enter" Key
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll("input, select");

    inputs.forEach((input, index) => {
        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                const nextInput = inputs[index + 1];
                if (nextInput) {
                    nextInput.focus();
                }
            }
        });
    });
});