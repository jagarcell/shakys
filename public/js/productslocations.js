$(document).ready(function(){
    $('#product_location_add_pic').addClass('dropzone')

    MyDropzone = new Dropzone(
        "form#product_location_add_pic", 
		{ 
			url: "/instoreimgupload", 
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
})

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
