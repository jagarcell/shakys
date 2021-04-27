function garcellParentNodeById(element, parentId) {
    while (element !== 'undefined') {
        if(element.id == parentId){
            return element
        }
        element = element.parentNode
    }
    return 'undefined'
}

function garcellParentNodeByClassName(element, parentClass) {
    while (element !== 'undefined') {
        if(element.className.includes(parentClass)){
            return element
        }
        element = element.parentNode
    }
    return 'undefined'
}

function ValidateEmail(inputText)
{
    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if(inputText.value.match(mailformat))
    {
        alert("Valid email address!");
        return true;
    }
    else
    {
        inputText.setCustomValidity('INVALID EMAIL FORMAT')
        alert("You have entered an invalid email address!")
        return false;
    }
}

/**
 * 
 * @param { JSON } reportInfo
 *                  frame:'jQuery element to contain the message in the view', 
 *                  message: 'string value with the success or error message', 
 *                  error: 'boolean to indicate error or success'
 *                         'if not defined is considered an error',
 *                  timeout: 'time in milliseconds to run the callback function'
 *                  param: 'callback function parameter'
 *
 * @param {JSON, JSON} endFunction
 *                      'frame: jQuery element coming from reportInfo.frame'
 *                      'param: any type coming from reportInfo.param'
 * 
 */
function reportResult(reportInfo,  endFunction=null) {
    reportInfo.frame[0].innerHTML = reportInfo.message

    if(reportInfo.error !== undefined && !reportInfo.error){
        reportInfo.frame[0].style.color = 'blue'
    }
    else{
        reportInfo.frame[0].style.color = 'red'
    }

    reportInfo.frame.show()
    var timeout = reportInfo.timeout != undefined ? reportInfo.timeout : 3000

    setTimeout(function(frame, param){
        if(endFunction !== null){
            endFunction(frame, param)
        }
    }, timeout, reportInfo.frame, reportInfo.param)
}

/**
 * 
 * @param {string[]} errorResponse 
 * @returns {string} 'readable error message'
 * 
 * */
function getMessageFromErrorInfo(errorResponse) {
    var message = ""
    $.each(errorResponse.errorInfo, function(index, value){
        message = message + value + "<br>"
    })
    return message
}
