$("#login").validate({
    rules : {
        username: {
            required: true,
            minlength: 3,
            remote: {
                url: "scripts/requests/CheckLogin.php",
                type: "post"
            }
        },
        password: {
            required: true,
            minlength: 5,
            remote: {
                url: "scripts/requests/CheckLogin.php",
                type: "post",
                data: {
                    password() {
                        return $( "#username" ).val()+"||"+$( "#password" ).val();
                    }
                }
            }
        }
    },
    messages : {
        username: {
            required: "Please provide a username",
            minlength: "Your username must have at least 3 characters",
            remote: "Username not found!"
        },
        password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 6 characters long",
            remote: "Wrong Password"
        }
    }
}); 
