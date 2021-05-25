$(document).ready(function(){
    attachDropzone('product_location_add_pic')
})

/**
 * 
 * @param {string} id
 *                  'id of the element to attach the dropzone to' 
 */
function attachDropzone(id){
    var section = document.getElementById(id)
    $(section).addClass('dropzone')
    var sectionDropzone = new Dropzone(
        "form#" + id,
        { 
            url: "/supplierlocationimgupload", 
            dictDefaultMessage : 'Drop An Image Or Click To Search One',
            init : function dropzoneInit() {
                // body...
                this.on('addedfile', function (file) {
                    // body...
                    filesAccepted = this.getAcceptedFiles()
                    if(filesAccepted.length > 0){
                        this.removeFile(filesAccepted[0])
                    }
                })
                this.on('success', function(file, data){
                    $('#image_to_upload').val(data.filename)
                })
            },
        }
    )
    
    section.Dropzone = sectionDropzone
    return sectionDropzone
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
    var productLocationAddPic = document.getElementById('product_location_add_pic')

    // Clear the add section fields
    $(addSectionFrame).find('#supplier_id').val('-1')
    $(addSectionFrame).find('#location_name').val('')
    $(addSectionFrame).find('#image_to_upload').val('')
    productLocationAddPic.Dropzone.removeAllFiles()

    // Change the views
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
        var supplier_id = $(addForm).find('#supplier_id').val() 
        var name = $(addForm).find('#location_name').val()
        var imagePath = $(addForm).find('#image_to_upload').val()

        $.post('/createsupplierlocation',
            {
                _token:$('meta[name="csrf-token"]').attr('content'),
                name:name,
                image_path:imagePath,
                supplier_id:supplier_id,
            }, function(data, status){
                if(status == 'success'){
                    var addProductLocationSection = $('#add_product_location_section')
                    var actionResultMessage = addProductLocationSection.find('#action_result_message')
                    switch (data.status) {
                        case 'ok':
                            var emptyList = $('#empty_list')
                            if(emptyList !== undefined){
                                emptyList.hide()
                            }
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

                                    var addIconFrame = document.getElementById('add_icon_frame')
                                    var addSectionFrame = document.getElementById('add_section_frame')
                                    var addSection = document.getElementById('product_location_add_pic')

                                    // Hide the add section and show the add icon
                                    addIconFrame.style.display = 'flex'
                                    addSectionFrame.style.display = 'none'

                                    // Clear the add section fields
                                    $(addSectionFrame).find('#supplier_id').val(-1)
                                    $(addSectionFrame).find('#location_name').val('')
                                    $(addSectionFrame).find('#image_to_upload').val('')
                                    addSection.Dropzone.removeAllFiles()

                                    // Get the location HTML template and substitute the values with
                                    // the ones in the response then add the result to the locations list

                                    // Get HTMLs
                                    var locationsListWrap = document.getElementById('locations_list_wrap')
                                    var locationHtml = document.getElementById('location_html').innerHTML

                                    // Substitute the values
                                    locationHtml = locationHtml.replace(/location-id/g, location.id)
                                    locationHtml = locationHtml.replace(/location-image-path/g, location.image_path)
                                    locationHtml = locationHtml.replace(/location-name/g, location.name)
                                    locationHtml = locationHtml.replace(/location-supplier-name/g, location.supplier_name)

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
    $.post('/deletesupplierlocation',
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
                                error:false,
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
   $.post('/getsupplierlocation',
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
                        var suppliers = data.suppliers
                        var locationEditHtml = $(document.getElementById('location_edit_html')).find('#location-id')[0].innerHTML

                        locationEditHtml = locationEditHtml.replace(/location-id/g, location.id)
                        productLocationSectionWrap.innerHTML = locationEditHtml
                        $(productLocationSectionWrap).find('#location_name').val(location.name)
                        $(productLocationSectionWrap).find('#image_to_upload').val(location.image_name)
                        var supplierSelect = document.getElementById('supplier_select')
                        var selectedIndex = 0
                        $.each(suppliers, function(index, supplier){
                            var option = document.createElement("option")
                            option.value = supplier.id
                            option.text = supplier.name
                            if(supplier.id == location.supplier_id)
                            {
                                selectedIndex = index + 1
                            }
                            supplierSelect.add(option)
                        })
                        supplierSelect.options.selectedIndex = selectedIndex

                        $('#product_location_add_pic_' + location.id).addClass('dropzone')

                        let mockFile = { name: location.image_name, size: location.image_size }
                        
                        new Dropzone(
                            "form#product_location_add_pic_" + location.id, 
                            { 
                                url: "/supplierlocationimgupload", 
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
    $.post('/getsupplierlocation',
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
                        var supplier = data.supplier
                        var locationHtml = $(document.getElementById('location_html')).find('#location-id')[0].innerHTML
                        
                        locationHtml = locationHtml.replace(/location-id/g, location.id)
                        locationHtml = locationHtml.replace(/location-name/g, location.name)
                        locationHtml = locationHtml.replace(/location-image-path/g, location.image_path)
                        locationHtml = locationHtml.replace(/location-supplier-name/g, supplier.name)

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
    var supplier_id = addForm.find('#supplier_select').val()
    if(addForm[0].checkValidity()){
        $.post('/updatesupplierlocation',
            {
                _token:$('meta[name="csrf-token"]').attr('content'),
                id:locationId,
                name:name,
                image_path:imageToUpload,
                supplier_id:supplier_id,
                element_tag:locationId,
            }, function(data, status){
                if(status == 'success'){
                    var elementTag = data.element_tag
                    var productLocationSectionWrap = document.getElementById(elementTag)
                    var actionResultMessage = $(productLocationSectionWrap).find('#action_result_message')
        
                    switch (data.status) {
                        case 'ok':
                            var location = data.location
                            var supplier = data.supplier
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
                                    locationHtml = locationHtml.replace(/location-supplier-name/g, supplier.name)

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
