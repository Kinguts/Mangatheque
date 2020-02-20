$( document ).ready(function() {
    console.log( "ready!" );
    $("#selectCollection").on("change", function(){
        var value = this.value; 
        console.log(value);
    })
});