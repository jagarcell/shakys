$(document).ready(function(){
    attachDropzone('product_location_add_pic')
})

/**
 * 
 * @param {string} id
 *                  'id of the element to attach the dropzone to' 
 */
function attachDropzone(id){
    $('#' + id).addClass('dropzone')
    return(
        new Dropzone(
            "form#" + id,
            { 
                url: "/instoreimgupload", 
                dictDefaultMessage : 'Drop An Image Or Click To Search One',
                init : function dropzoneInit() {
                    // body...
                    this.on('addedfile', function (file) {
                        // body...
                        filesAccepted = this.getAcceptedFiles()
                        if(!addNew || filesAccepted.length > 0){
                            this.removeFile(filesAccepted[0])
                        }
                    })
                    this.on('success', function(file, data){
                        $('#image_to_upload').val(data.filename)
                    })
                },
            }
        )
    )
}

/**
 * 
 * @param {object} addLocationButton
 *                 'HTML Button'  
 */
function addLocationClick(addLocationButton) {
    var addIconFrame = document.getElementById('add_icon_frame')
    var addSectionFrame = document.getElementById('add_section_frame')

    addIconFrame.style.display = 'none'
    addSectionFrame.style.display = 'flex'    
}

/**
 * 
 * @param {object} discardLocationButton 
 *                 'HTML Button' 
 */
function discardLocationClick(discardLocationButton) {
    var addIconFrame = document.getElementById('add_icon_frame')
    var addSectionFrame = document.getElementById('add_section_frame')

    addIconFrame.style.display = 'flex'
    addSectionFrame.style.display = 'none'    
}

/**
 * 
 * @param {object} createLocationButton
 *                  'HTML Button 
 *  This is the action to create a product location
 * 
 */
function createLocationClick(createLocationButton) {
    var addForm = document.getElementById('add_form')
    if(addForm.checkValidity()){
        var name = $(addForm).find('#location_name').val()
        var imagePath = $(addForm).find('#image_to_upload').val()

        $.post('/createinstorelocation',
            {
                _token:$('meta[name="csrf-token"]').attr('content'),
                name:name,
                image_path:imagePath,
            }, function(data, status){
                if(status == 'success'){
                    var addProductLocationSection = $('#add_product_location_section')
                    var actionResultMessage = addProductLocationSection.find('#action_result_message')
                    switch (data.status) {
                        case 'ok':
                            // Report the result in a message
                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:"THE LOCATION WAS SUCCESSFULLY CREATED",
                                    error:false,
                                    param:data.location,
                                }, function(frame, location){
                                    // Hide the report message
                                    frame.hide()

                                    // Hide the add section and show the add icon
                                    var addIconFrame = document.getElementById('add_icon_frame')
                                    var addSectionFrame = document.getElementById('add_section_frame')
                                    addIconFrame.style.display = 'flex'
                                    addSectionFrame.style.display = 'none'

                                    // Get the location HTML template and substitute the values with
                                    // the ones in the response then add the result to the locations list

                                    // Get HTMLs
                                    var locationsListWrap = document.getElementById('locations_list_wrap')
                                    var locationHtml = document.getElementById('location_html').innerHTML

                                    // Substitute the values
                                    locationHtml = locationHtml.replace(/location-id/g, location.id)
                                    locationHtml = locationHtml.replace(/location-image-path/g, location.image_path)
                                    locationHtml = locationHtml.replace(/location-name/g, location.name)

                                    // Add the result to the locations list    
                                    locationsListWrap.innerHTML = locationHtml + locationsListWrap.innerHTML
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
                        default:
                            break;
                    }

                }
            }
        )
    }
    else{
        addForm.reportValidity()
    }
}

/**
 * 
 * @param {string} locationId
 *              'The location id that is also the products_locations_section_wrap id' 
 */
function deleteButtonClick(locationId) {
    $.post('/deleteinstorelocation',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:locationId,
            element_tag:locationId,
        }, function(data, status){
            if(status == 'success'){
                var productLocationSectionWrap = document.getElementById(data.element_tag)
                var actionResultMessage = $(productLocationSectionWrap).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"THIS LOCATION WAS SUCCESFULLY DELETED",
                                param:data.element_tag,    
                            }, function(frame, elementTag){
                                frame.hide()
                                var productLocationSectionWrap = document.getElementById(elementTag)
                                productLocationSectionWrap.outerHTML = ""
                            }
                        )
                        break;
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                param:data.element_tag,
                            }, function(frame, elementTag){
                                var productLocationSectionWrap = document.getElementById(elementTag)
                                productLocationSectionWrap.outerHTML = ""
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

                    default:
                        break;
                }
            }
        }
    )
}

/**
 * 
 * @param {string} locationId
 *               'The location id that is also the products_locations_section_wrap id' 
 * 
 * This action will open an editor section for a location in place of the location data section
 */

function editButtonClick(locationId) {
   $.post('/getinstorelocation',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:locationId,
            element_tag:locationId
        }, function(data, status){
            if(status == 'success'){
                var productLocationSectionWrap = document.getElementById(data.element_tag)
                var actionResultMessage = $(productLocationSectionWrap).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var location = data.location
                        var locationEditHtml = $(document.getElementById('location_edit_html')).find('#location-id')[0].innerHTML

                        locationEditHtml = locationEditHtml.replace(/location-id/g, location.id)
                        productLocationSectionWrap.innerHTML = locationEditHtml
                        $(productLocationSectionWrap).find('#location_name').val(location.name)
                        $(productLocationSectionWrap).find('#image_to_upload').val(location.image_name)

                        $('#product_location_add_pic_' + location.id).addClass('dropzone')

                        let mockFile = { name: location.image_name, size: location.image_size }
                        
                        new Dropzone(
                            "form#product_location_add_pic_" + location.id, 
                            { 
                                url: "/instoreimgupload", 
                                dictDefaultMessage : 'Drop An Image Or Click To Search One',
                                init : function dropzoneInit() {
                                    // body...
                                    this.on('addedfile', function (file) {
                                        // body...
                                        var productLocationSectionWrap = document.getElementById(location.id)

                                        filesAccepted = this.getAcceptedFiles()
                                        if(this.hidePreview !== undefined){
                                            $(productLocationSectionWrap).find('.dz-preview')[0].style.display = 'none'
                                        }
                                        else{
                                            this.hidePreview = 'hidePreview'
                                        }
    
                                        if(filesAccepted.length > 0){
                                            this.removeFile(filesAccepted[0])
                                        }
                                    })
                                    this.on('success', function(file, data){
                                        var productLocationSectionWrap = document.getElementById(location.id)

                                        $(productLocationSectionWrap).find('#image_to_upload').val(data.filename)
                                    })
                                }
                            }).displayExistingFile(mockFile, location.image_path)
                        break;
                
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                param:data.element_tag,
                            }, function(frame, elementTag){
                                frame.hide()

                                var productLocationSectionWrap = document.getElementById(elementTag)
                                productLocationSectionWrap.outerHTML = ""
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

                    default:
                        break;
                }
            }
        }
   )
}

/**
 * 
 * @param {string} locationId
 *                 'id of the location and also of the location section
 *
 */
function discardLocationChangesClick(locationId){
    $.post('/getinstorelocation',
        {
            _token:$('meta[name="csrf-token"').attr('content'),
            id:locationId,
            element_tag:locationId,
        }, function(data, status){
            if(status == 'success'){
                var productLocationSectionWrap = document.getElementById(data.element_tag)
                var actionResultMessage = $(productLocationSectionWrap).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var location = data.location
                        var locationHtml = $(document.getElementById('location_html')).find('#location-id')[0].innerHTML
                        
                        locationHtml = locationHtml.replace(/location-id/g, location.id)
                        locationHtml = locationHtml.replace(/location-name/g, location.name)
                        locationHtml = locationHtml.replace(/location-image-path/g, location.image_path)

                        productLocationSectionWrap.innerHTML = locationHtml
                        break;
                
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                param:data.element_tag,
                            }, function(frame, elementTag){
                                frame.hide()

                                var productLocationSectionWrap = document.getElementById(element_tag)
                                productLocationSectionWrap.outerHTML = ""
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
                    default:
                        break;
                }
            }
        }
    )
}

/**
 * 
 * @param {string} locationId
 *              'id of the product location and also of the location section'
 *   
 */
function acceptLocationChangesClick(locationId) {
    var productLocationSectionWrap = document.getElementById(locationId)
    var addForm = $(productLocationSectionWrap).find('#add_form')
    var name = addForm.find('#location_name').val()
    var imageToUpload = addForm.find('#image_to_upload').val()
    if(addForm[0].checkValidity()){
        $.post('/updateinstorelocation',
            {
                _token:$('meta[name="csrf-token"]').attr('content'),
                id:locationId,
                name:name,
                image_path:imageToUpload,
                element_tag:locationId,
            }, function(data, status){
                if(status == 'success'){
                    var elementTag = data.element_tag
                    var productLocationSectionWrap = document.getElementById(elementTag)
                    var actionResultMessage = $(productLocationSectionWrap).find('#action_result_message')
        
                    switch (data.status) {
                        case 'ok':
                            var location = data.location

                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:"THIS LOCATION WAS SUCCESFULLY UPDATED!",
                                    error:false,
                                    param:location,
                                }, function(frame, location){
                                    frame.hide()
                                    var productLocationSectionWrap = document.getElementById(location.id)
                                    var locationHtml = $(document.getElementById('location_html')).find('#location-id')[0].innerHTML
                                    
                                    locationHtml = locationHtml.replace(/location-id/g, location.id)
                                    locationHtml = locationHtml.replace(/location-name/g, location.name)
                                    locationHtml = locationHtml.replace(/location-image-path/g, location.image_path)

                                    productLocationSectionWrap.innerHTML = locationHtml
            
                                }
                            )    
                            break;
                    
                        case 'notfound':
                            var message = getStatusMessage('notfound')
                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:message,
                                    param:elementTag,
                                }, function(frame, elementTag){
                                    var productLocationSectionWrap = document.getElementById(elementTag)
                                    productLocationSectionWrap.innerHTML = ""
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
    else{
        addForm.reportValidity()
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
            statusMessage = 'THIS LOCATION CAN NOT BE FOUND!<br>PLEASE TRY REFRESHING YOUR BROWSER.'
            break;

        case '419':
            statusMessage = 'SESSION EXPIRED!<br>REFRESH YOUR BROWSER.'
            break
    
        default:
            break;
    }
    return statusMessage
}
