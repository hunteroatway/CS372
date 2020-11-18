// set the default slide shown to 1
var index = 0;
showSlide(index);

// set up left/right buttons
function change(n) {
    // change the slide based on which button is pressed
    showSlide(index += n);
}

// set up dot buttons
function slide(n){
        // change the slide based on which dot is pressed
        showSlide(n - 1);
}

function showSlide(n){

    index = n;

    //get the html objects
    var slide = document.getElementsByClassName("image");
    var dots = document.getElementsByClassName("dot");
    // make sure n doesnt go past last image, if it does set to image 1
    if( n > slide.length - 1)
        index = 0;
    
    // make sure n doesnt go past first image, if it does set to last image
    if( n < 0)
        index = slide.length - 1;

    // set all of them to inactive
    for(var i = 0; i < slide.length; i++)
        slide[i].style.display = "none";
    for(var i = 0; i < dots.length; i++)
        dots[i].className = dots[i].className.replace("active", "");


    // activate the image and dot based on index
    slide[index].style.display = "block";
    dots[index].className += " active";
}