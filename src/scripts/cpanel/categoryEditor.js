$("#category_create").validate({
    rules : {
        name: {
            required: true,
            maxlength: 64,

        }
    },
    messages : {
        name: {
            required: "Please write the category name",
            maxlenght: "The name cannot be more than 64 characters long"
        }
    }  
});