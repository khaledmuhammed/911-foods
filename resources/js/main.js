// btn-scrolle-to-top
let btn_top = document.getElementById("top");
// side bar 
let show_side_bar = document.getElementById('show-side-bar');
let hide_side_bar = document.getElementById('cancel');
let side_bar = window.document.getElementById('side-bar');
let drop_down_btn = document.getElementsByClassName('drop_down_btn');
// top nav bar 
let main_nav_bar = document.getElementById('nav_bar');
// on window scroll

window.onscroll = function () { scrollFunction(); showBTN() };
// hide the nav bar when scrolle 
function scrollFunction() {
    if(main_nav_bar){					
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50){
            main_nav_bar.style.display='none';
            if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
                main_nav_bar.style.display='flex';
                main_nav_bar.classList.add('navbar-v2');
                main_nav_bar.classList.remove('navbar');
            }else{
                main_nav_bar.style.display='none';
                main_nav_bar.classList.add('navbar');
                main_nav_bar.classList.remove('navbar-v2');
            }
        }else{
            main_nav_bar.style.display='flex';
        }
    }
}
// show btn when scroll down
function showBTN() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        btn_top.style.display = "block";
    } else {
        btn_top.style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
if (btn_top) {
        btn_top.addEventListener('click', e => {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera   
    })
}
// show and hide the side bar 
if(show_side_bar){
        show_side_bar.addEventListener('click', e => {
        side_bar.style.marginLeft = 0;
    });

}
if (hide_side_bar) {
    
    hide_side_bar.addEventListener('click', e => {
        side_bar.style.marginLeft = '-1000px';
    });
}
// open the drop down on the side bar
if (drop_down_btn) {
    Array.from(drop_down_btn).forEach(element => {
    element.addEventListener('click', e => {
        element.classList.toggle('active');
        element.nextElementSibling.classList.toggle('show');
    })
});
}



//  phone_filter on grid-listing-filterscol-map page 
let phone_filter =document.getElementById('phone_filter');
let btn_phone_filter=document.getElementById('btn_phone_filter');

if(btn_phone_filter && phone_filter){
	btn_phone_filter.addEventListener('click',e=>{
	    phone_filter.classList.toggle('hidden');
    })
}

// range input on grid-listing-filterscol-map page 
var $distance_range = $("#distance");
var $fill = $(".bar .fill");
var distance_value=document.getElementById('distance_value')
if ($distance_range && $fill && distance_value) {
    function setBar() {
        $fill.css("width", $distance_range.val() + "%");
            distance_value.textContent=$distance_range.val();
        }
    $distance_range.on("input", setBar);		
    setBar();  
}
