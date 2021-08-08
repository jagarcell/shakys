$(document).ready(function(){
    createSupplierImageDrop()

    var supplierSearchText = document.getElementById('supplier_search_text')

    supplierSearchText.addEventListener("keyup", function (event){
        if(event.code == 'Enter'){
            supplierSearchClick()
        }        
    })

})

var MyDropzone

/**
 *  Dropzone initialization
 */
function createSupplierImageDrop(){

    $('#supplier_image').addClass('dropzone')

    MyDropzone = new Dropzone(
        "form#supplier_image", 
		{ 
			url: "/supplierimgupload", 
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
                    $('#supplier_data_entry_form_add').find('#supplier_image_to_upload').val(data.filename)
                })
			},
		}
    )
}
/**
 * 
 * To search a supplier according to the search text 
 */
function supplierSearchClick() {
    var supplierSearchText = document.getElementById('supplier_search_text').value
    window.location.replace('/suppliers?search_text=' + supplierSearchText)
}

/**
 *  Add supplier click action.
 *  Hides the add supplier Icon and
 *  Shows the add supplier form.
 */
function supplierAddClick() {
    $('#add_icon_frame').hide()
    $('#supplier_add_section').show()
}

/**
 * 
 * @param {string} newSupplierButton
 **           'HTML element of the button that initiated this action'
 *
 ** 'This action creates a new supplier'
 */
function newSupplier(newSupplierButton) {
    var add_section_div = garcellParentNodeById(newSupplierButton, 'add_section_div')
    var supplier_data_entry_form = $(add_section_div).find('#supplier_data_entry_form_add')[0]
    var element_tag = supplier_data_entry_form.id
    var action_result_message = $(add_section_div).find('#action_result_message')

    if(supplier_data_entry_form.checkValidity()){
        supplier_data_entry_form = $(supplier_data_entry_form)
        var code = supplier_data_entry_form.find('#supplier_code_entry').val()
        var email = supplier_data_entry_form.find('#supplier_email_entry').val()
        var name = supplier_data_entry_form.find('#supplier_name_entry').val()
        var address = supplier_data_entry_form.find('#supplier_address_entry').val()
        var phone = supplier_data_entry_form.find('#supplier_phone_entry').val()
        var pickup = supplier_data_entry_form.find('#supplier_pickup_entry').val()
        var image_path = supplier_data_entry_form.find('#supplier_image_to_upload').val()

        $.post('/addsupplier', 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                code:code,
                email:email,
                name:name,
                address:address,
                phone:phone,
                pickup:pickup,
                image_path:image_path,
                element_tag:element_tag,
            },
            function(data, status){
                if(status == 'success'){
                    switch (data.status) {
                        case 'ok':
                            var emptyList = $('#empty_list')
                            if(emptyList !== undefined){
                                emptyList.hide()
                            }
                            reportResult(
                                {
                                    frame:action_result_message,
                                    message:"THE SUPPLIER WAS SUCCESSFULLY CREATED",
                                    error:false,
                                    param:data,
                                }, function(frame, data){
                                    var element_tag = data.element_tag
                                    var supplier = data.supplier
                                    frame.hide()
                                    $('#supplier_add_section').hide()
                                    $('#add_icon_frame').show()
                                    supplier_data_entry_form = $('#' + element_tag)
                                    supplier_data_entry_form.find('#supplier_code_entry').val('')
                                    supplier_data_entry_form.find('#supplier_email_entry').val('')
                                    supplier_data_entry_form.find('#supplier_name_entry').val('')
                                    supplier_data_entry_form.find('#supplier_address_entry').val('')
                                    supplier_data_entry_form.find('#supplier_phone_entry').val('')
                                    supplier_data_entry_form.find('#supplier_pickup_entry').val('pickup')
                                    supplier_data_entry_form.find('#supplier_image_to_upload').val('')
                                    MyDropzone.removeAllFiles()
                                    
                                    var supplier_edit_section = document.getElementById('supplier_edit_section')
                                    var main_div = document.getElementById('edit_sections_div')
            
                                    supplier_section = $(supplier_edit_section).find('#supplier-id')[0]
            
                                    var editHTML = supplier_edit_section.innerHTML
                                    editHTML = editHTML.replace(/supplier-id/g, supplier.id)
                                    editHTML = editHTML.replace(/supplier-code/g, supplier.code)
                                    editHTML = editHTML.replace(/supplier-image-path/g, supplier.image_path)
                                    editHTML = editHTML.replace(/supplier-email/g, supplier.email)
                                    editHTML = editHTML.replace(/supplier-name/g, supplier.name)
                                    editHTML = editHTML.replace(/supplier-address/g, supplier.address)
                                    editHTML = editHTML.replace(/supplier-phone/g, supplier.phone)
                                    editHTML = editHTML.replace(/supplier-pickup/g, supplier.pickup)
            
                                    main_div.innerHTML = editHTML + main_div.innerHTML
                                }
                            )
                                    
                            break
                
                        case '419':
                            var message = getStatusMessage('419')
                            reportResult(
                                {
                                    frame:action_result_message,
                                    message:message,
                                    param:data.element_tag,
                                }
                            )
                            break
                        case 'emailtaken':
                            var message = getStatusMessage('emailtaken')
                            reportResult(
                                {
                                    frame:action_result_message,
                                    message:message,
                                }, function(frame, param){
                                    frame.hide()
                                }
                            )    
                            break

                        case 'error':
                            var message = getMessageFromErrorInfo(data.message)
                            reportResult(
                                {
                                    frame:action_result_message,
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
        supplier_data_entry_form.reportValidity()
    } 
}

/**
 * 
 * Action to discard the supplier's edition changes
 * 
 */
function discardNewSupplier() {
    $('#supplier_add_section').hide()
    $('#add_icon_frame').show()
    supplier_data_entry_form = $('#supplier_data_entry_form')
    supplier_data_entry_form.find('#supplier_email_entry').val('')
    supplier_data_entry_form.find('#supplier_name_entry').val('')
    supplier_data_entry_form.find('#supplier_address_entry').val('')
    supplier_data_entry_form.find('#supplier_phone_entry').val('')
    supplier_data_entry_form.find('#supplier_pickup_entry').val('pickup')
    supplier_data_entry_form.find('#supplier_image_to_upload').val('')
    MyDropzone.removeAllFiles()
}

/**
 * 
 * @param {string} editButton 
 * *        editbutton:'HTML of the button that initiated this action'
 */
function editClick(editButton) {
    var supplier_section_wrap = garcellParentNodeByClassName(editButton, 'supplier_section_wrap')
    var id = supplier_section_wrap.id

    $.post(
        '/getsupplier',
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id:id,
            element_tag:id
        },
        function(data, status){
            if(status == 'success'){
                var supplier_section_wrap = $('#' + data.element_tag)
                var action_result_message = supplier_section_wrap.find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var supplier = data.supplier
                        var supplier_add_section_html = document.getElementById('supplier_add_section_html')
                        var supplier_section = document.getElementById(supplier.id)
    
                        supplier_section.innerHTML = supplier_add_section_html.innerHTML
                        supplier_section.innerHTML = supplier_section.innerHTML.replace('supplier-image', 'supplier_image_' + supplier.id)
                    
                        supplier_data_entry_form = $(supplier_section).find('#supplier_data_entry_form')
                        supplier_data_entry_form.find('#edit_supplier_email_entry').val(supplier.email)
                        supplier_data_entry_form.find('#edit_supplier_name_entry').val(supplier.name)
                        supplier_data_entry_form.find('#edit_supplier_address_entry').val(supplier.address)
                        supplier_data_entry_form.find('#edit_supplier_phone_entry').val(supplier.phone)
                        supplier_data_entry_form.find('#edit_supplier_pickup_entry').val(supplier.pickup)
                        supplier_data_entry_form.find('#edit_supplier_image_to_upload').val(supplier.image_name)
        
                        $('#supplier_image_' + supplier.id).addClass('dropzone')
    
                        let mockFile = { name: supplier.image_name, size: supplier.image_size }
    
                            new Dropzone(
                                "form#supplier_image_" + supplier.id, 
                                { 
                                    url: "/supplierimgupload", 
                                    dictDefaultMessage : 'Drop An Image Or Click To Search One',
                                    init : function dropzoneInit() {
                                        // body...
                                        this.on('addedfile', function (file) {
                                            // body...
                                            var edit_div = this.element.parentNode
                                            $(edit_div).find('#edit_supplier_image_to_upload').val(file.name)
                                            filesAccepted = this.getAcceptedFiles()
            
                                            if(this.hidePreview !== undefined){
                                                $(edit_div).find('.dz-preview')[0].style.display = 'none'
                                            }
                                            else{
                                                this.hidePreview = 'hidePreview'
                                            }
        
                                            if(filesAccepted.length > 0){
                                                this.removeFile(filesAccepted[0])
                                            }
                                        })
                                        this.on('success', function(file, data){
                                            var edit_div = this.element.parentNode
                                            $(edit_div).find('#edit_supplier_image_to_upload').val(data.filename)
                                        })
                                    }
                                }
                            ).displayExistingFile(mockFile, supplier.image_path)

                                
                        break;
                    
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                                param:data.element_tag,
                            }, function(frame, element_tag){
                                frame.hide()
                                var supplier_section_wrap = document.getElementById(element_tag)
                                supplier_section_wrap.outerHTML = ""
                            }
                        )
                        break
                    
                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                            }
                        )
                        break
                    
                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:action_result_message,
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
 * Action to delete a supplier
 */
function deleteClick(deleteButton) {
    var supplier_section_wrap = garcellParentNodeByClassName(deleteButton, 'supplier_section_wrap')
    var id = supplier_section_wrap.id

    $.post('/deletesupplier',
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id:id,
            element_tag:id,
        },
        function(data, status){
            if(status == 'success'){
                var supplier_section_wrap = document.getElementById(data.element_tag)
                var action_result_message = $(supplier_section_wrap).find('#action_result_message')
                var element_tag = data.element_tag

                switch (data.status) {
                    case 'ok':
                        reportResult(
                            {
                                frame:action_result_message,
                                message:"THE SUPPLIER HAS BEEN SUCCESFULLY DELETED!",
                                error:false,
                                param:element_tag,
                            }, function(frame, element_tag){
                                var supplier_section_wrap = document.getElementById(element_tag)
                                supplier_section_wrap.outerHTML = ""
                            }
                        )                        
                        break
                
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                                param:element_tag,
                            }, function(frame, element_tag){
                                var supplier_section_wrap = document.getElementById(element_tag)
                                supplier_section_wrap.outerHTML = ""
                            }
                        )
                        break

                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                            }
                        )
                        break

                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                            }, function(frame, param){
                                frame.hide()
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
 * @param {string} acceptChangesButton 
 * *        acceptChangesButton:'HTML of the button that initiated this action'
 */
function acceptChanges(acceptChangesButton, button) {
    if(button !== undefined){
        button.disabled = true
    }
    
    var supplier_section_wrap = garcellParentNodeByClassName(acceptChangesButton, 'supplier_section_wrap')
    var id = supplier_section_wrap.id
    supplier_section = $(supplier_section_wrap)

    var email = supplier_section.find('#edit_supplier_email_entry').val()
    var name = supplier_section.find('#edit_supplier_name_entry').val()
    var address = supplier_section.find('#edit_supplier_address_entry').val()
    var phone = supplier_section.find('#edit_supplier_phone_entry').val()
    var pickup = supplier_section.find('#edit_supplier_pickup_entry').val()
    var image_path = supplier_section.find('#edit_supplier_image_to_upload').val()

    $.post('/updatesupplier',
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id:id,
            email:email,
            name:name,
            address:address,
            phone:phone,
            pickup:pickup,
            image_path:image_path,
            element_tag:id
        }, 
        function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                var supplier_section_wrap = $('#' + element_tag)
                var action_result_message = supplier_section_wrap.find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        reportResult(
                            {
                                frame:action_result_message,
                                message:"THE SUPPLIER WAS SUCCESSFULLY UPDATED!",
                                error:false,
                                param:data,
                                alignTop:false,
                            }, function(frame, data){
                                frame.hide()
                                var supplier = data.supplier
                                var supplier_edit_section = document.getElementById('supplier-id')
            
                                innerHTML = supplier_edit_section.innerHTML
            
                                innerHTML = innerHTML.replace(/supplier-image-path/g, supplier.image_path)
                                innerHTML = innerHTML.replace(/supplier-code/g, supplier.code)
                                innerHTML = innerHTML.replace(/supplier-email/g, supplier.email)
                                innerHTML = innerHTML.replace(/supplier-name/g, supplier.name)
                                innerHTML = innerHTML.replace(/supplier-address/g, supplier.address)
                                innerHTML = innerHTML.replace(/supplier-phone/g, supplier.phone)
                                innerHTML = innerHTML.replace(/supplier-pickup/g, supplier.pickup)
            
                                var supplier_section = document.getElementById(supplier.id)
                                supplier_section.innerHTML = innerHTML
                            }
                        )
                        break

                    case 'emailtaken':
                        var message = getStatusMessage('emailtaken')
                        reportResult(
                            {
                                frame:action_result_message,
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
                                frame:action_result_message,
                                message:message,
                            }
                        )
                        break

                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                                param:element_tag,
                            }, function(frame, element_tag){
                                var supplier_section_wrap = document.getElementById(element_tag)
                                supplier_section_wrap.outerHTML = ""
                            }
                        )
                        break

                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                                timeout:5000,
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                        break    
                
                    default:
                        break;
                }
                if(button !== undefined){
                    button.disabled = false
                }
            }
        }
    )
}

/**
 * 
 * @param {string} discardChangesButton 
 * *        discardChangesButton:'HTML of the button that initiated this action'
 */
function discardChanges(discardChangesButton) {

    if(discardChangesButton !== undefined){
        discardChangesButton.disabled = true
    }
    var supplier_section = garcellParentNodeByClassName(discardChangesButton, 'supplier_section_wrap')
    
    $.post(
        'getsupplier', 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id:supplier_section.id,
            element_tag:supplier_section.id,
        }, 
        function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                var supplier_section_wrap = $('#' + element_tag)
                var action_result_message = supplier_section_wrap.find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var supplier = data.supplier
                        var supplier_section = document.getElementById(element_tag)
                        var supplier_edit_section = document.getElementById('supplier_edit_section')
                        supplier_edit_section = $(supplier_edit_section).find('#supplier-id')[0]
                        
                        var innerHTML = supplier_edit_section.innerHTML
        
                        innerHTML = innerHTML.replace(/supplier-image-path/g, supplier.image_path)
                        innerHTML = innerHTML.replace(/supplier-code/g, supplier.code)
                        innerHTML = innerHTML.replace(/supplier-email/g, supplier.email)
                        innerHTML = innerHTML.replace(/supplier-name/g, supplier.name)
                        innerHTML = innerHTML.replace(/supplier-address/g, supplier.address)
                        innerHTML = innerHTML.replace(/supplier-phone/g, supplier.phone)
                        innerHTML = innerHTML.replace(/supplier-pickup/g, supplier.pickup)
        
                        supplier_section.innerHTML = innerHTML
                                
                        break;

                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                                param:element_tag
                            }, function(frame, element_tag){
                                var supplier_section_wrap = document.getElementById(element_tag)
                                supplier_section_wrap.outerHTML = ""
                            }
                        )
                        break

                    case '419':
                        var message = getStatusMessage('419')
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                            }
                        )
                        break

                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:action_result_message,
                                message:message,
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                        break
                
                    default:
                        break;
                }
                if(discardChangesButton !== undefined){
                    discardChangesButton.disabled = false
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
        case 'notfound':
            statusMessage = 'THIS SUPPLIER CAN NOT BE FOUND!<br>PLEASE TRY REFRESHING YOUR BROWSER.'
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
