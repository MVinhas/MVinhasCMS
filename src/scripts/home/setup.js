$("#first-account").validate({
    rules : {
        email: {
            required: true,
            email: true,
            remote: {
                url: "scripts/requests/CheckFormData.php",
                type: "post"
            }
        },
        username: {
            required: true,
            minlength: 3,
            remote: {
                url: "scripts/requests/CheckFormData.php",
                type: "post"
            }
        },
        password: {
            required: true,
            minlength: 6
        },
        confirmPassword: {
            required: true,
            minlength: 6,
            equalTo: "#password"
        },
        agree: {
            terms: {
                required: true
            },
            errorLabelContainer: "label.error"
        }
    },
    messages : {
        email: {
            required: "You must insert a valid email",
            remote: "Email already in use!"
        },
        username: {
            required: "Please provide a username",
            minlength: "Your username must have at least 3 characters",
            remote: "Username already in use!"
        },
        password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 6 characters long"
        },
        confirmPassword: {
            equalTo: "Please enter the same password as above"
        },
        agree: {
            required: "You must accept our Terms & Conditions"
        }
    },
    
});  