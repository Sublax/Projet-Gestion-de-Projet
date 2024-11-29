$(document).ready(function() {
    function connexion(){
        $.ajax({
            url: "./process_login.php",
            type: "POST",
            dataType: "text",
            data:{
                username: $("#username").val(),
                password: $("#password").val()
            },
            success: function(response){
                if(response !== '1'){
                    $("#registerButton").after("<p class='messageErreur'> Les informations sont incorrectes.</p>")
                }
            }
        });
    }
    $("#loginButton").click(function(event){
        event.preventDefault();
        connexion();
    });
});