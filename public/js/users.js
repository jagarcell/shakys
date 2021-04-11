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
            _token: $('meta[name="csrf-token"]').attr('content') + '1',
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
                if(data.status == 'email taken'){
                    $(form).find('#email_error').show()
                    setTimeout(function(){
                        $(form).find('#email_error').hide()
                    }, 3000)
                }
                if(data.status == '419'){
                    var recover_element = document.getElementById(data.element_tag)
                    form = recover_element.find('#')
                    console.log(recover_element)
                }
            }
        })
    }
    else{
        form.reportValidity()
    }
}
function discard(element) {
    var form = $(element)[0].parentNode.parentNode.parentNode
    var userId = $(form).find('#edited_user_id').val()
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
                user_section[0].innerHTML = user_section[0].innerHTML.replace('user_id', data.user.id)
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
