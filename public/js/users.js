$(document).ready(function(){

})

function edit(userid){
    var id = '#' + userid
    $.get('/userbyid', 
        {userid:userid}, 
        function(data, status){
            if(status == 'success'){
                if(data.status =='ok'){
                    var userData = $(id)
                    var editHTML = $('#edit_section')[0].innerHTML
                    userData[0].innerHTML = editHTML
                    userData.find('#edited_user_id').val(data.user.id)
                    userData.find('#edited_name').val(data.user.name)
                    userData.find('#edited_email').val(data.user.email)
                    userData.find('#edited_user_type').val(data.user.user_type)
                }
            }
        }
    )
}

function deleteUser(userid){
    if(confirm('ARE YOU SURE THAT YOU WANT TO REMOVE THIS USER')){
        var element_tag = userid
        $.post('/deleteuser', 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                userid:userid,
                element_tag:element_tag,
            }, 
            function(data, status){
               var edit_section = document.getElementById(data.element_tag)
               console.log(edit_section)
                if(data.status == 'ok'){
                    var delete_user_success = $(edit_section).find('#delete_user_success')
                    delete_user_success.show()
                    setTimeout(function(edit_section_to_remove){
                        $(edit_section_to_remove)[0].outerHTML = ""
                    }, 4000, edit_section)
                }
                else{
                    if(data.status == 'noadmin'){
                        var delete_user_error = $(edit_section).find('#delete_user_error')
                        delete_user_error.show()
                        setTimeout(function(element_to_hide){delete_user_error.hide()}, 5000, delete_user_error)
                    }
                }
                console.log(data)
        })
    }
}

function saveUser(element) {
    var form = garcellParentNodeById(element, "user_edit_form")
    
    var userId = $(form).find('#edited_user_id').val()
    var email = $(form).find('#edited_email').val()
    var name = $(form).find('#edited_name').val()
    var user_type = $(form).find('#edited_user_type').val()
    if(form.checkValidity())
    {
        $.post('/saveuser',
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            element_tag:userId,
            user_id:userId,
            name:name,
            email:email,
            user_type:user_type,
        }, function (data, status) {
            if(data.status == 'ok'){
                var user_data = $('#user_data')
                var user_section = $('#' + data.user_id)
                user_section[0].innerHTML = user_data[0].innerHTML
                user_section.find('#user_name')[0].innerHTML = data.name
                user_section.find('#user_email')[0].innerHTML = data.email
                user_section.find('#user_type')[0].innerHTML = data.user_type
                user_section[0].innerHTML = user_section[0].innerHTML.replace('user_id', data.user_id)
            }
            else{
                var edit_section = document.getElementById(data.element_tag)
                if(data.status == 'email taken'){
                    var email_error = $(edit_section).find('#email_error')
                    email_error.show()
                    setTimeout(function(element_to_hide){
                        element_to_hide.hide()
                    }, 3000, email_error)
                }
                if(data.status == '419'){
                    var save_session_expired = $(edit_section).find('#save_session_expired')
                    save_session_expired.show()
                    setTimeout(function(element_to_hide){
                        element_to_hide.hide()
                    }, 3000, save_session_expired)
                }
                if(data.status == 'noadmin'){
                    var edited_user_type_error = $(edit_section).find('#edited_user_type_error')
                    edited_user_type_error.show()
                    setTimeout(function(element_to_hide){
                        edited_user_type_error.hide()
                    }, 6000, edited_user_type_error)
                }
            }
        })
    }
    else{
        form.reportValidity()
    }
}

function discard(element) {
    var edit_section = garcellParentNodeById(element, 'user_section')
    var userId = $(edit_section).find('#edited_user_id').val()
    console.log(userId)
    $.get('/userbyid',
        {userid:userId},
        function(data, status){
            if(data.status == 'ok'){
                var user_data = $('#user_data')
                var user_section = $('#' + data.user.id)
                user_section[0].innerHTML = user_data[0].innerHTML
                user_section.find('#user_name')[0].innerHTML = data.user.name
                user_section.find('#user_email')[0].innerHTML = data.user.email
                user_section.find('#user_type')[0].innerHTML = data.user.user_type
                user_section[0].innerHTML = user_section[0].innerHTML.replace(/user_id/g, data.user.id)
            }
        }
    )
}

function newPassword(userId) {
    var userData = $('#' + userId)
    var editButtons = userData.find('#edit_buttons')
    var passwordReset = userData.find('#user_password_reset')
    var passwordHTML = $('#password_section')[0].innerHTML
    editButtons.hide();
    passwordReset[0].innerHTML = passwordHTML
}

function discardPassword(element) {
    var userPasswordReset = garcellParentNodeById(element, 'user_password_reset')
    var userData = garcellParentNodeById(element, 'user_edit_frame')
    var editButtons = $(userData).find('#edit_buttons')

    userPasswordReset.innerHTML = ""
    $(editButtons).show()
}
