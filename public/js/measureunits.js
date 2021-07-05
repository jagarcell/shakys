$(document).ready(function(){
})

function addIconClick() {
    document.getElementById('add_icon_frame').style.display = 'none'
    document.getElementById('add_section_frame').style.display = 'block'
    discardEditChanges(-1)
}

function createButtonClick() {
    var unitDescription = document.getElementById('unit_description').value
 
    $.post('/createmeasureunit',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            unit_description:unitDescription,
            element_tag:'add_section_wrap',
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#' + elementTag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var measureUnit = data.measureunit
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"THE UNIT WAS SUCCESFULLY CREATED",
                                error:false,
                                alignTop:false,
                                param:{elementTag:elementTag, measureUnit:measureUnit},
                            }, function(frame, param){
                                frame.hide()
                                var measureUnit = param.measureUnit
                                var sectionHtml = document.getElementById('section_html').outerHTML
                                var productsListWrap = document.getElementById('products_list_wrap')

                                document.getElementById('add_section_frame').style.display = 'none'
                                document.getElementById('add_icon_frame').style.display = 'flex'

                                unitSectionHTML = sectionHtml
                                unitSectionHTML = unitSectionHTML.replace(/section_html/g, measureUnit.id)
                                unitSectionHTML = unitSectionHTML.replace(/unit-id/g, measureUnit.id)
                                unitSectionHTML = unitSectionHTML.replace(/unit-description/g, measureUnit.unit_description)
                                productsListWrap.innerHTML = unitSectionHTML + productsListWrap.innerHTML

                                document.getElementById('unit_description').value = ""

                                $(productsListWrap).find('#' + measureUnit.id)[0].classList.add("measure_unit_edition")
                                $(productsListWrap).find('#' + measureUnit.id)[0].style.display = 'block'
                            }   
                        )                        
                        break
                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                            }, function(frame, param){
                                frame.hide()
                            }
                        )    
                        break
                
                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                            }
                        )
                        break    
                    default:
                        break;
                }
            }
        }
    )
}

function discardButtonClick() {
    document.getElementById('add_section_frame').style.display = 'none'
    document.getElementById('add_icon_frame').style.display = 'flex'
    document.getElementById('unit_description').value = ""
}

function editButtonClick(unitId) {
    discardEditChanges(-1)

    $.get('/getmeasureunit',
    {
        id:unitId,
        element_tag:unitId,
    }, function(data, status){
        if(status == 'success'){
            var elementTag = data.element_tag
            var actionResultMessage = $('#' + elementTag).find('#action_result_message')
            switch (data.status) {
                case 'ok':
                    var measureUnit = data.measureunit
                    var unitSection = document.getElementById(elementTag)
                    var addSectionHtml = document.getElementById('add_section_html').innerHTML

                    unitSection.innerHTML = addSectionHtml
                    unitSection.innerHTML = unitSection.innerHTML.replace(/unit-id/g, measureUnit.id)
                    unitSection.innerHTML = unitSection.innerHTML.replace(/unit-description/g, measureUnit.unit_description)
                    break
                case 'notfound':
                    reportResult(
                        {
                            frame:actionResultMessage,
                            message:"MEASURE UNIT NOT FOUND",
                            alignTop:false,
                            param:elementTag,
                        }, function(frame, elementTag){
                            document.getElementById(elementTag).outerHTML = ""
                        }
                    )
                    break
            
                default:
                    break
            }
        }
    }
)

}

function deleteButtonClick(unitId) {
    $.post('/removemeasureunit',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:unitId,
            verbose:true,
            element_tag:unitId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#' + elementTag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"THE MEASURE UNIT WAS SUCCESFULLY DELETED",
                                error:false,
                                alignTop:false,
                                param:elementTag        
                            }, function(frame, elementTag){
                                frame.hide()
                                document.getElementById(elementTag).outerHTML = ""
                            }
                        )
                        break
                    case 'inuse':
                        var measureUnit = data.measureunit
                        var acceptCancelUnitLinkDialogFrameHtml = document.getElementById('accept_cancel_unit_link_dialog_frame_html').innerHTML
                        var acceptCancelUnitLinkDialogFrame = document.getElementById('accept_cancel_unit_link_dialog_frame')

                        acceptCancelUnitLinkDialogFrame.innerHTML = acceptCancelUnitLinkDialogFrameHtml
                        acceptCancelUnitLinkDialogFrame.innerHTML = acceptCancelUnitLinkDialogFrame.innerHTML.replace(/unit-id/g, measureUnit.id)
                        acceptCancelUnitLinkDialogFrame.innerHTML = acceptCancelUnitLinkDialogFrame.innerHTML.replace(/unit-description/g, measureUnit.unit_description)
                        acceptCancelUnitLinkDialogFrame.style.display = 'block'
                        break
                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:false,                                
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                        break
                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:false,
                            }
                        )    
                        break
                
                    default:
                        break;
                }
            }
        }
    )
}

/**
 * 
 * @param {sting} unitId
 *  
 */
function acceptEditChanges(unitId) {
   $.post('/updatemeasureunit',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:unitId,
            unit_description:$('#' + unitId).find('.unit_description_input').val(),
            element_tag:unitId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#' + elementTag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var measureUnit = data.measureunit
                        param = {elementTag:elementTag, measureUnit:measureUnit}
                        console.log(data.measureunit.id)
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"MEASURE UNIT SUCCESFULLY UPDATED",
                                error:false,
                                alignTop:false,
                                param:param,
                            }, function(frame, param){
                                frame.hide()
                                elementTag = param.elementTag
                                measureUnit = param.measureUnit

                                var unitSection = document.getElementById(elementTag)
                                var sectionHtml = document.getElementById('section_html').innerHTML

                                unitSection.innerHTML = sectionHtml
                                unitSection.innerHTML = unitSection.innerHTML.replace(/unit-id/g, measureUnit['id'])
                                unitSection.innerHTML = unitSection.innerHTML.replace(/unit-description/g, measureUnit['unit_description'])
                            }
                        )
                        break
                
                    default:
                        break
                }
            }
        }
   )  
}

/**
 * 
 * @param {sting} unitId
 *  
 */
function discardEditChanges(unitId) {
    if(unitId == -1){
        var measureUnitEditions = $('.measure_unit_edition')
        $.each(measureUnitEditions, function(index, measureUnitEdition){
            discardEditChanges(measureUnitEdition.id)
        })
        return
    }

    $.get('/getmeasureunit',
        {
            id:unitId,
            element_tag:unitId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var measureUnit = data.measureunit
                        var unitSection = document.getElementById(elementTag)

                        unitSection.innerHTML = document.getElementById('section_html').innerHTML
                        unitSection.innerHTML = unitSection.innerHTML.replace(/unit-id/g, measureUnit.id)
                        unitSection.innerHTML = unitSection.innerHTML.replace(/unit-description/g, measureUnit.unit_description)
                        break
                
                    default:
                        break
                }
            }
        }
    )    
}

/**
 * 
 * Action to close the unit removal dialog
 * 
 */
function acceptCancelUnitLinkDialogClose(){
    var acceptCancelUnitLinkDialogFrame = document.getElementById('accept_cancel_unit_link_dialog_frame')
    acceptCancelUnitLinkDialogFrame.style.display = 'none'
    acceptCancelUnitLinkDialogFrame.innerHTML = ""
}

/**
 * 
 * @param {string} unitId 
 */
function acceptUnitRemoval(unitId){
    var acceptCancelUnitLinkDialogFrame = document.getElementById('accept_cancel_unit_link_dialog_frame')
    acceptCancelUnitLinkDialogFrame.style.display = 'none'
    acceptCancelUnitLinkDialogFrame.innerHTML = ""

    $.post('/removemeasureunit',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:unitId,
            element_tag:unitId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#' + elementTag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"THE MEASURE UNIT WAS SUCCESFULLY DELETED",
                                error:false,
                                alignTop:false,
                                param:elementTag        
                            }, function(frame, elementTag){
                                frame.hide()
                                document.getElementById(elementTag).outerHTML = ""
                            }
                        )
                        break
                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:false,                                
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                        break
                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:false,
                            }
                        )    
                        break
                
                    default:
                        break;
                }
            }
        }
    )
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
 **          notfound:'THIS USER CAN NOT BE FOUND!<br>PLEASE TRY REFRESHING YOUR BROWSER.'
 **          emailtaken:'THIS EMAIL HAS BEEN TAKEN'
 **          passwordmissmatch:'PASSWORD CONFIRMATION DOES NOT MATCH!'
 **          419:'SESSION EXPIRED!<br>REFRESH YOUR BROWSER.'
 * 
 */
 function getStatusMessage(status) {
    var statusMessage = ""
    
    switch (status) {
        case '419':
            statusMessage = 'SESSION EXPIRED!<br>REFRESH YOUR BROWSER.'
            break
        case 'notfound':
            statusMessage = 'THIS PRODUCT WAS NOT FOUND!'
            break
        case 'nodata':
            statusMessage = 'NOT ENOUGH DATA WAS PROVIDED'
            break
        default:
            break;
    }
    return statusMessage
}
