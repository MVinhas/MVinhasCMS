$('#post_create').validate({
    rules : {
        title: {
            required: true,
            maxlength: 90,

        },
        short_content: {
            required: true,
            maxlenght: 100,
        },
        content: {
            required: true
        }
    },
    messages : {
        title: {
            required: 'Please write the title',
            maxlenght: 'The title cannot be more than 90 characters long'
        },
        short_content: {
            required: 'Please write a short description of your post',
            maxlenght: 'Your password must be at least 6 characters long'
        },
        content: {
            required: 'Please write your post'
        }
    }  
});