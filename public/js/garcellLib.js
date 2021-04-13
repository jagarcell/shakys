function garcellParentNodeById(element, parentId) {
    while (element !== 'undefined') {
        if(element.id == parentId){
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
