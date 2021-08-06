$(document).ready(function(){

    var usersSearchText = document.getElementById('user_search_text')

    usersSearchText.addEventListener("keyup", function (event){
        if(event.code == 'Enter'){
            userSearchClick()
        }        
    })

})

/**
 * 
 * To search users selectively
 *  
 */
function userSearchClick() {
    var searchText = document.getElementById('user_search_text').value
    window.location = '/users?search_text=' + searchText
}

/**
 * 
 * @param {string} userid
 **                userid:'The id of the user being edited'  
 */
function edit(userid){

    $.post('/userbyid', 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            userid:userid,
            element_tag:userid,
        }, 
        function(data, status){
            if(status == 'success'){
                var userData = $('#' + data.element_tag)
                var editSection = $('#edit_section')
                var editHTML = editSection[0].innerHTML
                var userEditActionResult = userData.find('#user_edit_action_result')
                switch (data.status) {
                    case 'ok':
                        var user = data.user
                        userData[0].innerHTML = editHTML
                        userData.find('#edited_user_id').val(user.id)
                        userData.find('#edited_name').val(user.name)
                        userData.find('#edited_email').val(user.email)
                        userData.find('#edited_user_type').val(user.user_type)
                    
                        break;
                    
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:userEditActionResult,
                                message:message,
                                param:data.element_tag,
                                alignTop:false,
                            }, function(frame, element_tag){
                                frame.hide()
                                var userData = document.getElementById(element_tag)
                                userData.outerHTML = ""
                            }
                        )
                        
                        break;

                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:userEditActionResult,
                                message:message,
                                
                            }, function(frame, param){
                                frame.hide()
                            }
                        )                        
                        break;
                    
                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:userEditActionResult,
                                message:message,
                            }
                        )

                    default:
                        break;
                }
    
            }
        }
    )
}

/**
 * 
 * @param {string} userid
 **                 userid:'The id of the user being deleted'
 */
function deleteUser(userid){
    if(confirm('ARE YOU SURE THAT YOU WANT TO DELETE THIS USER')){
        $.post('/deleteuser', 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                userid:userid,
                element_tag:userid,
            }, 
            function(data, status){
                if(status == 'success'){
                    var element_tag = data.element_tag
                    var edit_section = document.getElementById(element_tag)
                    var user_edit_action_result = $(edit_section).find('#user_edit_action_result')
                    switch (data.status) {
                            case 'ok':
                                reportResult(
                                    {
                                        frame:user_edit_action_result,
                                        message:"THIS USER HAS BEEN SUCCESSFULLY DELETED!",
                                        error:false,
                                        timeout:4000,
                                        param:element_tag,
                                        alignTop:false,
                                    }, function(frame, element_tag){
                                        frame.hide()
                                        var edit_section = document.getElementById(element_tag)
                                        edit_section.outerHTML = ""
                                    }
                                )

                                break;

                            case 'noadmin':
                                reportResult(
                                    {
                                        frame:user_edit_action_result,
                                        message:"THIS USER CAN NOT BE DELETED!<br>NO MORE admin LEFT!",
                                        timeout:5000,
                                        alignTop:false,
                                    },
                                    function(frame, param){
                                        frame.hide()
                                    }
                                )

                                break;

                            case 'notfound':
                                var message = getStatusMessage('notfound')
                                reportResult(
                                    {
                                        frame:user_edit_action_result,
                                        message:message,
                                        timeout:4000,
                                        param:element_tag,
                                        alignTop:false,
                                    }, function(frame, element_tag){
                                        frame.hide()
                                        var edit_section = document.getElementById(element_tag)
                                        edit_section.outerHTML = ""
                                    }
                                )
                                break
                            case 'error':
                                var message = getMessageFromErrorInfo(data.message)
                                reportResult(
                                    {
                                        frame:user_edit_action_result,
                                        message:message,
                                    },function(frame, param){
                                        frame.hide()
                                    }
                                )

                                break;

                            case '419':
                                var message = getStatusMessage('419')
                                reportResult(
                                    {
                                        frame:user_edit_action_result,
                                        message:message,
                                    }
                                )
                                break;
    
                            default:
                                break;
                    }
                }
            }
        )
    }
}

/**
 * 
 * @param {string} element 
 **         element:'The HTML element that initiated this action' 
 */
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
            user_id:userId,
            name:name,
            email:email,
            user_type:user_type,
            element_tag:userId,
        }, function (data, status) {
            if(status == 'success'){
                var user_section = $('#' + data.element_tag)
                var reportFrame = user_section.find('#user_edit_message')
                switch (data.status) {
                    case 'ok':
                        var user = data.user
                        reportResult(
                            {
                                frame:reportFrame,
                                message:"THE USER WAS SUCCESSFULLY UPDATED!",
                                error:false,
                                param:user,
                                alignTop:false,
                            },
                            function(frame, param){
                                var user = param
                                frame.hide()
                                var user_data = $('#user_data')
                                var user_section = $('#' + user.id)
                
                                user_section[0].innerHTML = user_data[0].innerHTML
                                user_section.find('#user_username')[0].innerHTML = user.username
                                user_section.find('#user_name')[0].innerHTML = user.name
                                user_section.find('#user_email')[0].innerHTML = user.email
                                user_section.find('#user_type')[0].innerHTML = user.user_type
                                user_section[0].innerHTML = user_section[0].innerHTML.replace(/user_id/g, user.id)
                            }
                        )
                        break;

                    case 'emailtaken':
                        reportResult(
                            {
                                frame:reportFrame,
                                message:"THIS EMAIL IS TAKEN!",
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                            
                        break;
                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:reportFrame,
                                message:message,
                            }
                        )
                        break;
                    case 'noadmin':
                        reportResult(
                            {
                                frame:reportFrame,
                                message:"THE TYPE OF THIS USER CAN NOT BE CHANGED TO 'user'!<br>THERE IS NOT OTHER 'admin' USER LEFT.",
                                timeout:6000,
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                        break;

                    case 'error':
                        reportResult(
                            {
                                frame:reportFrame,
                                message:getMessageFromErrorInfo(data.message),
                            }, function(frame, param){
                                frame.hide()
                            }
                        )    
                        break;
                    
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:reportFrame,
                                message:message,
                                timeout:4000,
                                param:data.element_tag,
                            },function(frame, element_tag){
                                frame.hide()
                                var user_section = $('#' + element_tag)
                                user_section.hide()
                            }
                        )
                            break;
                    default:
                        break;
                }
            }
        })
    }
    else{
        form.reportValidity()
    }
}

/**
 * 
 * @param {string} element 
 **         element:'The HTML element that initiated this action' 
 */
function discard(element) {
    var edit_section = garcellParentNodeById(element, 'user_section')
    var userId = $(edit_section).find('#edited_user_id').val()

    $.post('/userbyid',
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            userid:userId,
            element_tag:userId,
        },
        function(data, status){
            if(status == 'success'){
                var user_data = $('#user_data')
                var user_section = $('#' + data.element_tag)
                var user_edit_message = user_section.find('#user_edit_message')

                switch (data.status) {
                    case 'ok':
                        var user = data.user
        
                        user_section[0].innerHTML = user_data[0].innerHTML
                        user_section.find('#user_username')[0].innerHTML = user.username
                        user_section.find('#user_name')[0].innerHTML = user.name
                        user_section.find('#user_email')[0].innerHTML = user.email
                        user_section.find('#user_type')[0].innerHTML = user.user_type
                        user_section[0].innerHTML = user_section[0].innerHTML.replace(/user_id/g, user.id)
                            
                        break;
                
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:user_edit_message,
                                message:message,
                                timeout:5000,
                                param:data.element_tag,
                                alignTop:false,
                            }, function(frame, element_tag){
                                frame.hide()
                                var user_section = $('#' + element_tag)
                                user_section.hide()
                            }
                        )
    
                        break;

                    case 'error':
                        reportResult(
                            {
                                frame:user_edit_message,
                                message:getMessageFromErrorInfo(data.message),
                                timeout:4000,
                            }, function(frame, param){
                                frame.hide()
                            }
                        )

                        break;

                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:user_edit_message,
                                message:message
                            }
                        )
                        break;

                        default:
                           break;
                }
            }
        }
    )
}

/**
 * 
 * @param {string} userId 
 **         userid:'The id of the user that initiated this action' 
 */
function newPassword(userId) {
    var userData = $('#' + userId)
    var passwordReset = userData.find('#user_password_reset')
    var passwordSection = $('#password_section')[0]
    var passwordHTML = passwordSection.innerHTML
    passwordHTML = passwordHTML.replace(/user_id/g, userId)
    passwordReset[0].innerHTML = passwordHTML
    userData[0].scrollIntoView(false)
}

/**
 * 
 * @param {string} element
 **         element:'The HTML element that initiated this action'
 */
function createPassword(element) {
    var user_edit_wrap = garcellParentNodeById(element, 'user_edit_wrap')
    var user_password_reset_form = $(user_edit_wrap).find('#user_password_reset_form')
    var user_id = $(user_edit_wrap).find('#password_id').val()
    var user_password = $(user_edit_wrap).find('#user_password')
    var user_password_confirm = $(user_edit_wrap).find('#user_password_confirm')
    var new_password_value = $(user_password).val()
    var confirm_new_password_value = $(user_password_confirm).val()
    
    if(user_password_reset_form[0].checkValidity()){
        $.post('changepassword', 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: user_id,
                password: new_password_value,
                confirm_password:confirm_new_password_value,
                element_tag : user_id,
            }, function(data, status){
                if(status == 'success'){
                    var password_change_result = $('#' + data.element_tag).find('#password_change_result')
                    switch (data.status) {
                        case 'ok':
                            reportResult(
                                {
                                    frame:password_change_result,
                                    message:"PASSWORD SUCCESFULLY CHANGED!",
                                    error:false,
                                    param:data.element_tag,
                                    alignTop:false,
                                }, function(frame, element_tag){
                                    frame.hide()
                                    var userSection = $('#' + element_tag)
                                    var user_password_reset = $(userSection).find('#user_password_reset')
                                    user_password_reset[0].innerHTML = ""
                                }
                            )

                            break;
                        case 'notfound':
                            var message = getStatusMessage('notfound')
                            reportResult(
                                {
                                    frame:password_change_result,
                                    message:message,
                                    timeout:5000,
                                    param:data.element_tag,
                                }, function(frame, element_tag){
                                    frame.hide()
                                    var user_section = $('#' + element_tag)
                                    user_section.hide()
                                }
                            )
                            break;

                        case 'error':
                            reportResult(
                                {
                                    frame:password_change_result,
                                    message:getMessageFromErrorInfo(data.message),
                                }, function(frame, param){
                                    frame.hide()
                                }
                            )
                        
                            break;

                        case 'passwordmissmatch':
                            var message = getStatusMessage('passwordmissmatch')
                            reportResult(
                                {
                                    frame:password_change_result,
                                    message:message,
                                    alignTop:false,
                                }, function(frame, param){
                                    frame.hide()
                                }
                            )
                            break;

                        case '419':
                            var message = getStatusMessage('419')
                            reportResult(
                                {
                                    frame:password_change_result,
                                    message:message,
                                }
                            )    
                            break;
                        default:
                            break;
                    }
                }
            }
        )
    }
    else{
        user_password_reset_form[0].reportValidity()
    }
}

/**
 * 
 * @param {string} element
 **         element:'The HTML element that initiated this action'
 */
function discardPassword(element) {
    var userPasswordReset = garcellParentNodeById(element, 'user_password_reset')
    var userData = garcellParentNodeById(element, 'user_edit_wrap')
    var editButtons = $(userData).find('#edit_buttons')

    userPasswordReset.innerHTML = ""
    $(editButtons).show()
}

/**
 * 
 * @param {string} userAddIcon
 **         userAddIcon:'The HTML element that initiated this action'
 */
function userAddClick(userAddIcon) {
    var user_add_section = $(garcellParentNodeByClassName(userAddIcon, 'user_add_section'))
    var user_add_icon = user_add_section.find('#user_add_icon')
    var user_add_form = user_add_section.find('#user_add_form')
    user_add_icon.hide()
    user_add_form.show()
}

/**
 * 
 * @param {string} userAbortButton
 **         userAbortButton:'The HTML element that initiated this action'  
 */
function userAbortClick(userAbortButton) {
    var user_add_icon = $('#user_add_icon')
    var user_add_form = $('#user_add_form')

    user_add_form.find('#add_user_name').val('')
    user_add_form.find('#add_user_email').val('')
    user_add_form.find('#add_user_password').val('')
    user_add_form.find('#add_confirm_user_password').val('')
    
    user_add_form.hide()
    user_add_icon.show()
}

/**
 * 
 * @param {string} userCreateButton
 **         userCreateButton:'The HTML element that initiated this action'
 */
function userCreateClick(userCreateButton) {
    var user_form = $('#user_form')

    if((user_form[0]).checkValidity()){
        var userName = user_form.find('#add_user_username').val()
        var name = user_form.find('#add_user_name').val()
        var email = user_form.find('#add_user_email').val()
        var type = user_form.find('#add_user_type').val()
        var password = user_form.find('#add_user_password').val()
        var confirmPassword = user_form.find('#add_confirm_user_password').val()
    
        $.post('/createuser',
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_name:userName,
                name:name,
                email:email,
                user_type:type,
                password:password,
                confirm_password:confirmPassword,
            },
            function(data, status){
                var createMessage = $('#create_message')
                switch (data.status) {
                    case 'ok':
                        var user = data.user
                        reportResult(
                            {
                                frame:createMessage,
                                message:"THE USER WAS SUCCESSFULLY CREATED!",
                                error:false,
                                param:user,
                                alignTop:false,
                            },
                            function(frame, user){
                                frame.hide()
                                $('#user_add_form').hide()
                                $('#add_user_username').val('')
                                $('#add_user_email').val('')
                                $('#add_user_name').val('')
                                $('#add_user_password').val('')
                                $('#add_confirm_user_password').val('')
                                $('#user_add_icon').show()
                                var addedUserHtml = document.getElementById('added_user_html')
                                var innerHTML = addedUserHtml.innerHTML
                                innerHTML = innerHTML.replace(/user-id/g, user.id)
                                innerHTML = innerHTML.replace(/user-name/g, user.name)
                                innerHTML = innerHTML.replace(/user-email/g, user.email)
                                innerHTML = innerHTML.replace(/user-type/g, user.user_type)
                                var usersList = document.getElementById('users_list')
                                usersList.innerHTML = innerHTML + usersList.innerHTML
                            }
                        )
                    break;

                    case 'passwordmissmatch':
                        var message = getStatusMessage('passwordmissmatch')
                        reportResult(
                            {
                                frame:createMessage,
                                message:message,
                            },
                            function(createMessage, param){
                                createMessage.hide()
                            }
                        )
                    break;
                
                    case 'emailtaken':
                        var message = getStatusMessage('emailtaken')
                        reportResult(
                            {
                                frame:createMessage,
                                message:message,
                            },
                            function(createMessage, param){
                                createMessage.hide()
                            }
                        )
                    break;

                    case 'error':
                        reportResult(
                            {
                                frame:createMessage,
                                message: data.message
                            },
                            function(createMessage, param){
                                createMessage.hide()
                            }
                        )
                    break;

                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:createMessage,
                                message:message,
                            }
                        )
                    break;

                    default:
                        break;
                }
            }
        )
    }
    else{
        (user_form[0]).reportValidity()
    }
}

/**
 * 
 * @param {string} status
 *                  'notfound'
 *                  'emailtaken'
 *                  'passwordmissmatch'
 *                  '419'
 *  
 * @returns
 *          
 **          notfound:'THIS USER CAN NOT BE FOUND!<br>PLEASE TRY REFRESHING YOUR BROWSER.'
 **          emailtaken:'THIS EMAIL HAS BEEN TAKEN'
 **          passwordmissmatch:'PASSWORD CONFIRMATION DOES NOT MATCH!'
 **          419:'SESSION EXPIRED!<br>REFRESH YOUR BROWSER.'
 * 
 */
 function getStatusMessage(status) {
    var statusMessage = ""
    
    switch (status) {
        case 'notfound':
            statusMessage = 'THIS USER CAN NOT BE FOUND!<br>PLEASE TRY REFRESHING YOUR BROWSER.'
            break;
    
        case 'emailtaken':
            statusMessage = 'THIS EMAIL HAS BEEN TAKEN'
            break

        case 'passwordmissmatch':
            statusMessage = 'PASSWORD CONFIRMATION DOES NOT MATCH!'
            break

        case '419':
            statusMessage = 'SESSION EXPIRED!<br>REFRESH YOUR BROWSER.'
            break
    
        default:
            break;
    }
    return statusMessage
}
