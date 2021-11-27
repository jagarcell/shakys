$(document).ready(function(){
    createProductImageDrop()

    var productSearchText = document.getElementById('product_search_text')

    productSearchText.addEventListener("keyup", function (event){
        if(event.code == 'Enter'){
            productSearchClick()
        }        
    })

})

function productSearchClick() {
     var productSearchText = document.getElementById('product_search_text').value
     window.location ='/listproducts?search_text=' + productSearchText
}

/**
 *  Dropzone initialization
 */
 function createProductImageDrop(){

    var productImage = document.getElementById('product_image')

    $(productImage).addClass('dropzone')
    MyDropzone = new Dropzone(
        "form#product_image", 
		{ 
			url: "/productimgupload", 
			dictDefaultMessage : 'Drop An Image Or Click To Search One',
			init : function dropzoneInit() {
				// body...
				this.on('addedfile', function (file) {
					// body...
					filesAccepted = this.getAcceptedFiles()
					if(filesAccepted.length > 0){
						this.removeFile(filesAccepted[0])
					}
                    file.previewElement.addEventListener("click", function() {
                        this.parentNode.click()
                    })                                            
                })
                this.on('success', function(file, data){
                    $('#add_section_frame').find('.product_form').find('.image_to_upload').val(data.filename)
                })
			},
		}
    )
    productImage.MyDropzone = MyDropzone
}

/**
 * Shows the data entry form to add a product.
 */
function addIconClick(button) {
    if(button !== undefined){
        button.disabled = true
    }
    var productEditions = $('#products_list_wrap').find('.product_edition')

    $('#add_section_wrap').removeClass('add_icon_visible')

    discardEditChanges(-1)

    $.get('/getsuppliers', function(data, status){
        if(status == 'success'){
            switch (data.status) {
                case 'ok':
                    var defaultSupplier = $('#add_section_frame').find('.default_supplier')[0]
                    $.each(data.suppliers, function(index, supplier){
                        var option = document.createElement('option')
                        option.text = supplier.name
                        option.value = supplier.id
                        defaultSupplier.add(option)
                    })
                    $('#add_icon_frame').hide()
                    $('#add_section_frame').show()
                    $('.page_title')[0].scrollIntoView(true)

                    break;

                case 'error':
                    var actionResult = $('#add_section_wrap').find('#action_result_message')
                    var message = getMessageFromErrorInfo(data.message)
                    reportResult({
                        frame:actionResult,
                        message:message,
                    })
                    break
                default:
                    break;
            }
        }
        if(button !== undefined){
            button.disabled = false
        }
    })
}

/**
 * Action to discard the product creation.
 */
function discardButtonClick() {
    $('#add_section_frame').find('.code').val('')
    $('#add_section_frame').find('.description').val('')
    $('#add_section_frame').find('.days_to_count').val('')
    $('#add_section_frame').find('.measure_unit').val('')
    var defaultSupplier = $('#add_section_frame').find('.default_supplier')[0]
    while(defaultSupplier.options.length > 1){
        defaultSupplier.options.remove(1)
    }
    $('#add_section_frame').find('#supplier_code').val('')
    $('#add_section_frame').find('#supplier_product_description').val('')
    $('#add_section_frame').find('#supplier_product_location').val('')
    $('#add_section_frame').find('.image_to_upload').val('')

    $('#add_section_frame').hide()
    $('#add_icon_frame').show()

    productImage = document.getElementById('product_image')
    productImage.MyDropzone.removeAllFiles()
    clearUnitsChange()

    $('#add_section_wrap').addClass('add_icon_visible')
}

/**
 * Action to create a new product.
 */
function createButtonClick(button) {
    if(!unitsSelected()){
        alert("YOU MUST SELECT AT LEAST ONE UNIT FOR THE PRODUCT!")
        return
    }
    if(button !== undefined){
        button.disabled = true
    }
    measuresDialogCreate(-1, false)

    var productForm = $('#add_section_frame').find('.product_form')[0]
    var defaultMeasureSelect = $('#unit_link_dialog_frame').find('.default_measure_select')[0]
    var planTypeSelect = $(productForm).find('.plan_type_select')[0]
    var planType = planTypeSelect.options[planTypeSelect.selectedIndex].value
    var defaultMeasureUnitId = -1

    if(defaultMeasureSelect !== undefined){
        defaultMeasureUnitId = defaultMeasureSelect.options[defaultMeasureSelect.selectedIndex].getAttribute('value')
    }

    if(productForm.checkValidity()){
        $.post('/createproduct',
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                internal_code:$('#add_section_frame').find('.code').val(),
                internal_description:$('#add_section_frame').find('.description').val(),
                days_to_count:$('#add_section_frame').find('.days_to_count').val(),
                default_measure_unit_id:defaultMeasureUnitId,
                plan_type:planType,
                default_supplier_id:$(productForm).find('.default_supplier')[0].selectedOptions[0].value,
                image_to_upload:$('#add_section_frame').find('.image_to_upload').val(),
            }, function(data, status){
                if(status == 'success'){
                    var actionResultMessage = $('#add_section_wrap').find('#action_result_message')
                    switch (data.status) {
                        case 'ok':
                            var product = data.product
                            var emptyList = $('#empty_list')
                            if(emptyList !== undefined){
                                emptyList.hide()
                            }
                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:"THE PRODUCT WAS SUCCESSFULLY REGISTERED",
                                    error:false,
                                    param:product,
                                    timeout:1000,
                                }, function(frame, product){
                                    fromEditToShow(product, true)
                                    frame.hide()
                                    discardButtonClick()                                    
                                }
                            )
                            saveUnitChanges(product.id)
                            clearUnitsChange()
                            break;
                        case 'error':
                            var message = getMessageFromErrorInfo(data.message)
                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:message,
                                },
                                function(frame, param){
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
                        case 'exist':
                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:"THIS PRODUCT CODE IS ALREADY TAKEN",
                                }, function(frame, param){
                                    frame.hide()
                                }
                            )
                            break    
                        default:
                            break;
                    }
                }
                if(button !== undefined){
                    button.disabled = false
                }
            }
        )
    }
    else{
        productForm.reportValidity()
    }
}

/**
 * 
 * Action to open a dialog to link a product to a supplier
 * @param {string} productId
 *  
 */
function suppliersButtonClick(productId, button) {
    if(button !== undefined){
        button.disabled = true
    }
    discardEditChanges(-1)
    $.get('/getproduct',
        {
            id:productId,
            suppliers:"include",
            element_tag:productId,
        }, function (data, status) {
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#' + elementTag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var productHtml = document.getElementById(productId)

                        productHtml.innerHTML = document.getElementById('link_product_section_html').innerHTML
                        productHtml.innerHTML = productHtml.innerHTML.replace(/image-path/g, product.image_path)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/product-id/g, product.id)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/product-code/g, product.internal_code)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/product-description/g, product.internal_description)

                        var suppliersSelect = $(productHtml).find('#supplier_product_select')[0]

                        $.each(product.suppliers, function(index, supplier){
                            var option = document.createElement("option")
                            option.value = supplier.id
                            option.text = supplier.name
                            suppliersSelect.options.add(option)
                        })
                        document.getElementById(elementTag).classList.add('editting')

                        break
                
                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:false,
                            }, function (frame, param) {
                                frame.hide()
                            }
                        )
                        break
                        
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                            }, function (frame, param) {
                                frame.hide()
                            }
                        )
                        break

                    default:
                        break
                }
            }
            if(button !== undefined){
                button.disabled = false
            }
        }
    )
}

/**
 * Accept product linking to a supplier
 * @param {string} productId 
 * 
 */
function acceptSupplierProductChanges(productId, button){
    if(button !== undefined){
        button.disabled = true
    }

    var productHtml = document.getElementById(productId)
    var supplierSelect = $(productHtml).find('#supplier_product_select')[0]
    var supplierId = supplierSelect.options[supplierSelect.selectedIndex].value
    var supplierCode = $(productHtml).find('#supplier_product_code').val()
    var supplierDescription = $(productHtml).find('#supplier_product_description').val()
    var supplierProductLocationStop = $(productHtml).find('#supplier_product_location_stop')

    $.post('/createsuppliersproductspivot',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            supplier_id:supplierId,
            product_id:productId,
            supplier_code:supplierCode,
            supplier_description:supplierDescription,
            location_stop:supplierProductLocationStop[0].options[supplierProductLocationStop[0].selectedIndex].value,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $(document.getElementById(elementTag)).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var sectionHtml = document.getElementById('section_html')
                        var productHtml = document.getElementById(elementTag)
                        
                        productHtml.innerHTML = sectionHtml.innerHTML
                        productHtml.innerHTML = productHtml.innerHTML.replace(/image-path/g, product.image_path)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/internal-code/g, product.internal_code)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/internal-description/g, product.internal_description)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/days-to-count/g, product.days_to_count)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/measure-unit/g, product.measure_unit)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/default-supplier-name/g, product.default_supplier_name)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/plan-type/g, product.plan_type)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/section_html/g, product.id)
                        
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

                    case 'nodata':
                        var message = getStatusMessage('nodata')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:false,
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                    default:
                        break
                }
            }
            if(button !== undefined){
                button.disabled = false
            }
        }
    )
}

/**
 * 
 * 
 * @param {string} productId
 *  
 */
function supplierProductSelectChange(supplierProductSelect, productId) {
    var supplierId = supplierProductSelect.options[supplierProductSelect.selectedIndex].value

    $.get('/getsuppliersproductspivot',
        {
            product_id:productId,
            supplier_id:supplierId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var productHtml = document.getElementById(elementTag)

                switch (data.status) {
                    case 'ok':
                        var suppliersProductsPivot = data.suppliersproductspivot
                        var supplierProductCode = $(productHtml).find('#supplier_product_code')
                        var supplierProductDescription = $(productHtml).find('#supplier_product_description')
                        var supplierProductLocationStop = $(productHtml).find('#supplier_product_location_stop')

                        supplierProductCode.val(suppliersProductsPivot.supplier_code)
                        supplierProductDescription.val(suppliersProductsPivot.supplier_description)

                        supplierProductLocationStop[0].options[supplierProductLocationStop[0].selectedIndex].removeAttribute('selected')
                        if(suppliersProductsPivot.location_stop !== -1){
                            supplierProductLocationStop[0].options[suppliersProductsPivot.location_stop].setAttribute('selected', true)
                        }
                        else{
                            supplierProductLocationStop[0].options[0].setAttribute('selected', true)
                        }

                        supplierProductCode.removeAttr('disabled')
                        supplierProductDescription.removeAttr('disabled')
                        supplierProductLocationStop.removeAttr('disabled')
                        break

                    case 'notfound':
                        var supplierProductCode = $(productHtml).find('#supplier_product_code')
                        var supplierProductDescription = $(productHtml).find('#supplier_product_description')
                        var supplierProductLocationStop = $(productHtml).find('#supplier_product_location_stop')

                        supplierProductCode.val("")
                        supplierProductDescription.val("")
                        supplierProductCode.removeAttr('disabled')
                        supplierProductDescription.removeAttr('disabled')
                        supplierProductLocationStop.removeAttr('disabled')

                        supplierProductLocationStop[0].options[supplierProductLocationStop[0].selectedIndex].removeAttribute('selected')
                        supplierProductLocationStop[0].options[0].setAttribute('selected', true)

                        break

                    default:
                        break
                }
            }
        }
    )
}

/**
 * Discard the product linking to a supplier
 * @param {string} productId 
 * 
 */
function discardSupplierProductChanges(productId) {
    $.get('getproduct',
        {
            id:productId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var sectionHtml = document.getElementById('section_html')
                        var productHtml = document.getElementById(elementTag)
                        
                        productHtml.innerHTML = sectionHtml.innerHTML
                        productHtml.innerHTML = productHtml.innerHTML.replace(/image-path/g, product.image_path)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/internal-code/g, product.internal_code)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/internal-description/g, product.internal_description)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/days-to-count/g, product.days_to_count)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/measure-unit/g, product.measure_unit)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/default-supplier-name/g, product.default_supplier_name)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/plan-type/g, product.plan_type)
                        productHtml.innerHTML = productHtml.innerHTML.replace(/section_html/g, product.id)
                        
                        break
                
                    default:
                        break
                }
            }
        }
    )
}

/**
 * Action to delete a product
 * @param {string} productId
 *  
 */
function deleteButtonClick(productId, button) {
    if(button !== null){
        button.disabled = true
    }
    discardEditChanges(-1)
    $.post('/deleteproduct',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:productId,
            element_tag:productId,
        }, function(data, status){
            if (status == 'success'){
                var element_tag = data.element_tag
                var actionResultMessage = $('#' + element_tag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"THE PRODUCT WAS SUCCESSFULLY DELETED!",
                                error:false,
                                alignTop:false,
                                param:productId,
                            }, function(frame, productId){
                                var section = document.getElementById(productId)
                                section.outerHTML = ""
                            }
                        )
                        break;

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
                                message:message
                            }
                        )
                        break
                    
                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                param:productId,
                            }, function(frame, productId){
                                var section = document.getElementById(productId)
                                section.outerHTML = ""
                            }
                        )
                    default:
                        break;
                }
            }
            if(button !== null){
                button.disabled = false
            }
        }
    )
}

/**
 * 
 * @param {string} productId
 *  
 */
function editButtonClick(productId, button) {
    if(button !== undefined){
        button.disabled = true
    }
    discardEditChanges(-1)
    discardButtonClick()

    $.get('/getproduct',
        {
            id:productId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var actionResultMessage = $('#' + data.element_tag).find('#action_result_message')
                var element_tag = data.element_tag

                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var section = document.getElementById(product.id)
                        var addSectionHtml = document.getElementById('add_section_html')

                        measuresDialogCreate(product.id, false)

                        section.innerHTML = addSectionHtml.innerHTML
                        section.innerHTML = section.innerHTML.replace(/product-image/g, 'product_image_' + product.id)
                        section.innerHTML = section.innerHTML.replace(/product-id/g, product.id)

                        var productForm = $(section).find('.product_form')

                        productForm.find('.code').val(product.internal_code)
                        productForm.find('.description').val(product.internal_description)
                        if(product.plan_type == 2){
                            productForm.find('.days_to_count')[0].disabled = true
                            productForm.find('.days_to_count').val('')
                        }
                        else{
                            productForm.find('.days_to_count').disabled = false
                            productForm.find('.days_to_count').val(product.days_to_count)
                        }
                        productForm.find('.days_to_count').attr('oldValue', product.days_to_count)
                        productForm.find('.measure_unit').val(product.measure_unit)
                        productForm.find('.measure_unit')[0].setAttribute("default_measure_unit_id", product.default_measure_unit_id)
                        productForm.find('.default_supplier').val(product.default_supplier_name)
                        productForm.find('.plan_type_select').val(product.plan_type)
                        productForm.find('.image_to_upload').val(product.image_path)

                        $('#product_image_' + product.id).addClass('dropzone')
    
                        let mockFile = { name: product.image_name, size: product.image_size }

                        new Dropzone(
                            "form#product_image_" + product.id, 
                            { 
                                url: "/productimgupload", 
                                dictDefaultMessage : 'Drop An Image Or Click To Search One',
                                init : function dropzoneInit() {
                                    // body...
                                    this.on('addedfile', function (file) {
                                        // body...
                                        var edit_div = this.element.parentNode
                                        $(edit_div).find('.image_to_upload').val(file.name)
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
                                        file.previewElement.addEventListener("click", function() {
                                            this.parentNode.click()
                                        })                                            
                                    })
                                    this.on('success', function(file, data){
                                        var section = this.element.parentNode.parentNode

                                        $(section).find('.product_form').find('.image_to_upload').val(data.filename)
                                    })
                                }
                            }
                        ).displayExistingFile(mockFile, product.image_path)

                        $.get('/getsuppliers',
                            {
                                element_tag:product.id
                            }, function (data, status) {
                                if(status == 'success'){
                                    if(data.status == 'ok'){
                                        var section = document.getElementById(data.element_tag)
                                        var supplierSelect = $(section).find('.product_form').find('.default_supplier')[0]
                                        var suppliers = data.suppliers
                                        $.each(suppliers, function (index, supplier) {
                                            var option = document.createElement('option')
                                            option.value = supplier.id
                                            option.text = supplier.name
                                            if(supplier.id == product.default_supplier_id){
                                                option.selected = true
                                            }
                                            supplierSelect.add(option)
                                        })
                                    }
                                    document.getElementById(element_tag).classList.add('editting')
                                }
                            }
                        )
                       break
                
                    case 'error':
                        var message = getMessageFromErrorInfo(data.message)
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                            }, function (frame, param) {
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
                            })
                        break

                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                param:element_tag,
                            }, function(frame, productId){
                                var section = document.getElementById(productId)
                                section.outerHTML = "" 
                            }
                        )
                        break
                    default:
                        break
                }
            }
            if(button !== undefined){
                button.disabled = false                
            }
        }
    )
}

/**
 * 
 * Action to discard the product's edit changes
 * 
 * @param {string} productid
 *  
 */
function discardEditChanges(productId, button) {
    if(productId == -1){
        var productEditions = document.getElementsByClassName('editting')
        $.each(productEditions, function(index, productEdition){
            discardEditChanges(productEdition.id)
            productEdition.classList.remove('editting')
        })
        return
    }
    if(button !== undefined){
        button.disabled = true
    }
    discardUnitChanges()

    $.get('/getproduct',
        {
            id:productId,
            element_tag:productId
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                var actionResultMessage = $(document.getElementById(element_tag)).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var section = document.getElementById(element_tag)
                        var sectionHtml = document.getElementById('section_html').innerHTML

                        sectionHtml = sectionHtml.replace(/image-path/g, product.image_path)
                        sectionHtml = sectionHtml.replace(/internal-code/g, product.internal_code)
                        sectionHtml = sectionHtml.replace(/internal-description/g, product.internal_description)
                        sectionHtml = sectionHtml.replace(/days-to-count/g, product.days_to_count)
                        sectionHtml = sectionHtml.replace(/measure-unit/g, product.measure_unit)
                        sectionHtml = sectionHtml.replace(/default-supplier-name/g, product.default_supplier_name)
                        sectionHtml = sectionHtml.replace(/plan-type/g, product.plan_type)
                        sectionHtml = sectionHtml.replace(/section_html/g, product.id)

                        section.innerHTML = sectionHtml
                        clearUnitsChange()
                        document.getElementById(product.id).classList.remove('editting')
                       break;

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
                                message:message
                            }
                        )
                        break

                    case 'notfound':
                        var message = getStatusMessage('notfound')
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                param:element_tag,
                            }, function(frame, element_tag){
                                var section = document.getElementById(element_tag)
                                section.outerHTML = ""
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
 * Action to accept the product's edit changes.
 * 
 * @param {string} productId
 *  
 */
function acceptEditChanges(productId, button){
    if(!unitsSelected()){
        alert("YOU MUST SELECT AT LEAST ONE UNIT FOR THE PRODUCT!")
        return
    }
    if(button !== undefined){
        button.disabled = true
    }

    var productForm = $(document.getElementById(productId)).find('.product_form')
    
    if(productForm[0].checkValidity()){
        var internalCode = productForm.find('.code').val()
        var internalDescription = productForm.find('.description').val()
        var daysToCount = productForm.find('.days_to_count').val()
        var defaultMeasureSelect = $('#unit_link_dialog_frame').find('.default_measure_select')[0]
        var defaultMeasureUnitId = ''
        var defaultSupplierId = productForm.find('.default_supplier').val()
        var planTypeSelect = productForm.find('.plan_type_select')[0]
        var planType = planTypeSelect.options[planTypeSelect.selectedIndex].value
        var imagePath = productForm.find('.image_to_upload').val()

        if(defaultMeasureSelect === undefined){
            defaultMeasureUnitId = productForm.find('.measure_unit')[0].getAttribute("default_measure_unit_id")
        }
        else{
            defaultMeasureUnitId = defaultMeasureSelect.options[defaultMeasureSelect.selectedIndex].getAttribute("value")
        }
        $.post('/updateproduct',
            {
                _token:$('meta[name="csrf-token"]').attr('content'),
                id:productId,
                internal_code:internalCode,
                internal_description:internalDescription,
                days_to_count:daysToCount,
                default_measure_unit_id:defaultMeasureUnitId,
                default_supplier_id:defaultSupplierId,
                plan_type:planType,
                image_path:imagePath,
                element_tag:productId,
            }, function(data, status){
                if(status == 'success'){
                    var element_tag = data.element_tag
                    var actionResultMessage = $(document.getElementById(element_tag)).find('#action_result_message')
                    switch (data.status) {
                        case 'ok':
                            var product = data.product
                            var section = document.getElementById(product.id)
                            var sectionHtml = document.getElementById('section_html').innerHTML

                            sectionHtml = sectionHtml.replace(/internal-code/g, product.internal_code)
                            sectionHtml = sectionHtml.replace(/internal-description/g, product.internal_description)
                            sectionHtml = sectionHtml.replace(/days-to-count/g, product.days_to_count)
                            sectionHtml = sectionHtml.replace(/measure-unit/g, product.measure_unit)
                            sectionHtml = sectionHtml.replace(/default-supplier-name/g, product.default_supplier_name)
                            sectionHtml = sectionHtml.replace(/section_html/g, product.id)
                            sectionHtml = sectionHtml.replace(/plan-type/g, product.plan_type)
                            sectionHtml = sectionHtml.replace(/image-path/g, product.image_path)
                            
                            section.innerHTML = sectionHtml
                            saveUnitChanges(product.id)
                            clearUnitsChange()
                            break;

                        case 'notfound':
                            var message = getStatusMessage('notfound')
                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:message,
                                    param:element_tag,   
                                    alignTop:false,                                 
                                }, function(frame, element_tag){
                                    var section = document.getElementById(element_tag)
                                    section.outerHTML = ""
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
                        case 'exist':
                            reportResult(
                                {
                                    frame:actionResultMessage,
                                    message:"THIS PRODUCT CODE IS ALREADY TAKEN",
                                    alignTop:false,
                                }, function(frame, param){
                                    frame.hide()
                                }
                            )
                            break    
                        default:
                            break;
                    }
                }
                if(button !== undefined){
                    button.disabled = false
                }
            }
        )
    }
    else{
        productForm[0].reportValidity()
        if(button !== undefined){
            button.disabled = false
        }
    }
}

/**
 * 
 * @param {object} product 
 * @param {boolean} added 'true' if new product 'false' if edited
 * 
 */
function fromEditToShow(product, added) {
    var sectionHtml = document.getElementById('section_html').outerHTML
    
    sectionHtml = sectionHtml.replace(/image-path/g, product.image_path)
    sectionHtml = sectionHtml.replace(/internal-code/g, product.internal_code)
    sectionHtml = sectionHtml.replace(/internal-description/g, product.internal_description)
    sectionHtml = sectionHtml.replace(/days-to-count/g, product.days_to_count)
    sectionHtml = sectionHtml.replace(/measure-unit/g, product.measure_unit)
    sectionHtml = sectionHtml.replace(/default-supplier-name/g, product.default_supplier_name)
    sectionHtml = sectionHtml.replace(/plan-type/g, product.plan_type)

    if(added){
        var productsListWrapHtml = document.getElementById('products_list_wrap').innerHTML
        sectionHtml = sectionHtml.replace(/section_html/g, product.id)
        productsListWrapHtml = sectionHtml + productsListWrapHtml
        document.getElementById('products_list_wrap').innerHTML = productsListWrapHtml
    }   
    else{
        var section = document.getElementById(product.id)
        section.innerHTML = sectionHtml
    }
    $('#' + product.id).show()
}

/**
 * 
 * @param {String} productId 
 */
function measuresButtonClick(productId, button) {
    measuresDialogCreate(productId, true, button)
}

/**
 * 
 * Action to open the product-units link dialog
 *  
 *  
 */
function measuresDialogCreate(productId, show = false, button = undefined) {
    var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')

    if(productId == -1 && $(unitLinkDialogFrame).find('.unit_link_dialog').length > 0){
        // Check if the dialog will be shown after cretion 
        if(show){
            unitLinkDialogFrame.style.display = 'block'
        }
        if(button !== undefined){
            button.disabled = false
        }
        return
    }
    // Let's request the units linked to this product
    // as well as the whole set of available units
    $.get('/getproductunits',
        {
            product_id:productId,
            element_tag:productId,
        }, function(data, status){
            if (status == 'success'){
                var element_tag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var measureUnits = data.measureunits
                        var productUnits = data.productunits
                        var product = data.product
                        var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')
                        var unitLinkDialogFrameHtml = document.getElementById('unit_link_dialog_frame_html')
                        unitLinkDialogFrame.innerHTML = unitLinkDialogFrameHtml.innerHTML
                        var defaultMeasureSelect = $(unitLinkDialogFrame).find('.default_measure_select')[0]
                        var unitLinkCheckboxFrameHtml = document.getElementById('unit_link_checkbox_frame_html')
                    
                        $.each(measureUnits, function(index, measureUnit){
                            var checkBoxHtml = unitLinkCheckboxFrameHtml.innerHTML
                            checkBoxHtml = checkBoxHtml.replace(/measureunit-id/g, measureUnit.id)
                            checkBoxHtml = checkBoxHtml.replace(/unit-description/g, measureUnit.unit_description)
                            $(unitLinkDialogFrame).find('.unit_links')[0].innerHTML += checkBoxHtml
                        })

                        // As the backend gave us the units linked to
                        // this product let's check them in the list
                        $.each(productUnits, function(index, productUnit){
                            $(unitLinkDialogFrame).find('#unit_' + productUnit.id)[0].setAttribute('checked', true)
                            var option = document.createElement("option")
                            option.value = productUnit.id
                            option.text = productUnit.unit_description
                            defaultMeasureSelect.add(option)
                            if(productUnit.default_unit !== undefined){
                                defaultMeasureSelect.options[index + 1].setAttribute('selected', '')
                            }
                        })

                        // If this is a new product to be created ...
                        if(productId == -1){
                            unitLinkDialogFrame.innerHTML = unitLinkDialogFrame.innerHTML.replace(/product-id/g, -1)
                            unitLinkDialogFrame.innerHTML = unitLinkDialogFrame.innerHTML.replace(/product-description/g, "New product")
                        }
                        else{
                            // Update other dialog data
                            unitLinkDialogFrame.innerHTML = unitLinkDialogFrame.innerHTML.replace(/product-id/g, product.id)
                            unitLinkDialogFrame.innerHTML = unitLinkDialogFrame.innerHTML.replace(/product-description/g, product.internal_description)
                        }

                        // Check if the dialog will be shown after cretion 
                        if(show){
                            unitLinkDialogFrame.style.display = 'block'
                        }
                        break
                
                    default:
                        break
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
 * @param {string} productId
 *  
 */
function acceptUnitChanges(productId){
    if(!unitsSelected()){
        alert("YOU MUST SELECT AT LEAST ONE UNIT FOR THE PRODUCT!")
        return
    }
    var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')
    var unitLinkDialogFrameHtmlSaved = document.getElementById('unit_link_dialog_frame_html_saved')
    var defaultUnitSelect = $(unitLinkDialogFrame).find('.default_measure_select')[0]
    var selectedIndex = $(unitLinkDialogFrame).find('.selected_index')[0]
    var unitLinkCheckboxFrames = $(unitLinkDialogFrame).find('.unit_link_checkbox_frame')

    $.each(unitLinkCheckboxFrames, function(index, unitLinkCheckboxFrame){
        if(unitLinkCheckboxFrame.getAttribute("removed") == 'true'){
            var unitId = unitLinkCheckboxFrame.getAttribute("measure_unit_id")
            var element_tag = 
                {
                    element_tag:'unit_link_checkbox_frame_' + unitId,
                    productid:productId,
                }

            $.post('/removemeasureunit',
                {
                    _token:$('meta[name="csrf-token"]').attr('content'),
                    id:unitId,
                    verbose:true,
                    element_tag:element_tag,
                }, function(data, status){
                    var element_tag = data.element_tag.element_tag
                    var productid = data.element_tag.productid
                    if (status == 'success'){
                        switch (data.status) {
                            case 'ok':
                                saveUnitChanges(productid)
                                document.getElementById(element_tag).outerHTML = ""
                                break
                            case 'inuse':
                                openAcceptCancelUnitLinkDialog(data.measureunit)
                                break
                            case 'error':
                                break
                            case '419':
                                break        
                        
                            default:
                                break;
                        }
                    }
                }
            )
        }
    })

    selectedIndex.setAttribute("value", $(unitLinkDialogFrame).find('.default_measure_select')[0].selectedIndex)

    var measureUnit = ''
    if(productId == -1){
        measureUnit = $('#add_section_frame').find('.measure_unit')
    }
    else{
        measureUnit = $('#' + productId).find('.measure_unit')
    }
    measureUnit.val(defaultUnitSelect.options[defaultUnitSelect.selectedIndex].text)
    unitLinkDialogFrameHtmlSaved.innerHTML = unitLinkDialogFrame.innerHTML

    saveUnitChanges(productId)
 
    unitLinkDialogFrame.style.display = 'none'
}

/**
 * 
 * Action to close the product-units link dialog
 * 
 *  
 */
 function discardUnitChanges() {
    var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')
    var addedUnits = $(unitLinkDialogFrame).find('.added_unit')

    $.each(addedUnits, function(index, addedUnit){
        var unitId = addedUnit.getAttribute("measure_unit_id")
        acceptUnitRemoval(unitId)
    })


    unitLinkDialogFrame.style.display = 'none'
}

function unitsSelected() {
    var unitLinkCheckboxes = $('#unit_link_dialog_frame').find('.unit_link_checkbox:checkbox:checked')

    if(unitLinkCheckboxes.length == 0){
        return false
    }
    else{
        return true
    }

}

/**
 * 
 * @param {string} productId 
 * @returns 
 */
function saveUnitChanges(productId) {
    var unitLinkCheckboxes = $('#unit_link_dialog_frame').find('.unit_link_checkbox')
    var measureUnits = []

    if(unitLinkCheckboxes.length == 0){
        return
    }
    $.each(unitLinkCheckboxes, function(index, unitLinkCheckbox){
        measureUnits.push(
            {
                id:unitLinkCheckbox.getAttribute('measure_id'),
                checked:unitLinkCheckbox.getAttribute('checked')
            }
        )
    })

    $.post('/setproductunits',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            product_id:productId,
            measure_units:measureUnits,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#' + elementTag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')

                        unitLinkDialogFrame.style.display = 'none'
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
                        var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')
                        var message = getStatusMessage('419')

                        unitLinkDialogFrame.style.display = 'none'
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                            }
                        )
                    default:
                        break
                }
            }
        }
    )
}

/**
 * 
 * Takes actions when a measure unit selection is changed
 * 
 * @param {string} checkboxId
 *  
 */
function measureUnitChange(checkboxId) {
    // Set variables for the measure units select
    // and for the changing unit selection
    var defaultMeasureSelect = $('#unit_link_dialog_frame').find('.default_measure_select')[0]
    var checkBox = document.getElementById(checkboxId)

    if(checkBox.getAttribute('checked') !== null){
        // Change from selected to not selected
        // Set to not checked
        checkBox.removeAttribute('checked')
    }
    else{
        // Change from not selected to selected
        // Set to checked
        checkBox.setAttribute('checked', true)
    }

    // Set flag to checked unit not found
    var found = false

    // Loop through the default unit select options
    for(var i = 0; i < defaultMeasureSelect.options.length; i++){
        var option = defaultMeasureSelect.options[i]
        // If an option matches the checked unit ...
        if(option.value == checkBox.getAttribute("measure_id")){
            // ... then set found to true
            found = true
            // If the found option exists is being unchecked ..
            if(checkBox.getAttribute('checked') === null){
                // ... and is selected ..
                if(option.selected){
                    // ... then set the selection to none
                    defaultMeasureSelect.selectedIndex = 0
                }
                // Remove the found option
                defaultMeasureSelect.remove(i)
            }
            // No more loop as the option was found
            break
        }
    }
    // If no option was found during the loop ...
    if(!found){
        // ... then create it
        var option = document.createElement("option")
        option.value = checkBox.getAttribute("measure_id")
        option.text = checkBox.getAttribute("text")
        defaultMeasureSelect.add(option)
    }
}

function clearUnitsChange() {
    var unitLinkDialogFrameHtmlSaved = document.getElementById('unit_link_dialog_frame_html_saved')
    var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')

    unitLinkDialogFrameHtmlSaved.innerHTML = ""
    unitLinkDialogFrame.innerHTML = ""
}

/**
 * 
 * Action to open an input for the 
 * creation of a new measure unit
 * 
 */
function addUnitClick(){
    var newUnitInput = $('#unit_link_dialog_frame').find('.new_unit_input')
    var unitAddLink = $('#unit_link_dialog_frame').find('.unit_add_link')[0]
    var unitCreateLink = $('#unit_link_dialog_frame').find('.unit_create_link')[0]

    // Show the new unit input
    newUnitInput.show()
    // Hide the unit add link 
    unitAddLink.style.display = 'none'
    // Set the focus to new input
    newUnitInput[0].focus()
    // Show the unit create link
    unitCreateLink.style.display = 'block'
}

/**
 * 
 * @param {string} unitId
 *  
 */
function removeUnitClick(unitId){
    var unitLinkCheckboxFrame = document.getElementById('unit_link_checkbox_frame_' + unitId)
    unitLinkCheckboxFrame.setAttribute("removed", "true")
    unitLinkCheckboxFrame.style.display = 'none'
}

/**
 * 
 * Action to open the Accept/Cancel unit removal Dialog
 * 
 */
function openAcceptCancelUnitLinkDialog(measureUnit){
    var acceptCancelUnitLinkDialogFrameHtml = document.getElementById('accept_cancel_unit_link_dialog_frame_html').innerHTML
    var acceptCancelUnitLinkDialogFrame = document.getElementById('accept_cancel_unit_link_dialog_frame')

    acceptCancelUnitLinkDialogFrameHtml = acceptCancelUnitLinkDialogFrameHtml.replace(/unit-id/g, measureUnit.id)
    acceptCancelUnitLinkDialogFrameHtml = acceptCancelUnitLinkDialogFrameHtml.replace(/unit-description/g, measureUnit.unit_description)

    acceptCancelUnitLinkDialogFrame.innerHTML = acceptCancelUnitLinkDialogFrameHtml
    acceptCancelUnitLinkDialogFrame.style.display = 'block'
}

/**
 * 
 * Action to close the Accept/Cancel unit link removal
 * 
 */
function acceptCancelUnitLinkDialogClose(){
    var acceptCancelUnitLinkDialogFrame = document.getElementById('accept_cancel_unit_link_dialog_frame')

    acceptCancelUnitLinkDialogFrame.innerHTML = ""
    acceptCancelUnitLinkDialogFrame.style.display = 'none'
}

/**
 * 
 * @param {String} productId
 *  
 */
function acceptUnitRemoval(unitId){
    var unitLinkDialogFrame = document.getElementById('unit_link_dialog_frame')
    var unitLlinkCheckboxFrames = $(unitLinkDialogFrame).find('.unit_link_checkbox_frame')
    $.post('/removemeasureunit',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:unitId,
            element_tag:unitId,
        }, function(data, status){
            var element_tag = data.element_tag
            if (status == 'success'){
                switch (data.status) {
                    case 'ok':
                        var unitLinkCheckboxFrame = document.getElementById('unit_link_checkbox_frame_' + unitId)
                        unitLinkCheckboxFrame.outerHTML = ""
                        acceptCancelUnitLinkDialogClose()
                        break
                    case 'error':
                        break
                    case '419':
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
 * Action to create a new measure unit
 * 
 */

function createUnitClick(button) {
    if(button !== undefined){
        button.disabled = true
    }
    var newUnitInput = $('#unit_link_dialog_frame').find('.new_unit_input')
    var unitAddLink = $('#unit_link_dialog_frame').find('.unit_add_link')[0]
    var unitCreateLink = $('#unit_link_dialog_frame').find('.unit_create_link')[0]

    // Get the name for the new unit
    var newUnit = newUnitInput.val()
    $.post('/createmeasureunit',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            unit_description:newUnit,
            element_tag:'unit_link_dialog_frame',
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var unitLinks = $('#unit_link_dialog_frame').find('.unit_links')[0]
                        var unitLinkCheckboxFrameHtml = $('.unit_link_checkbox_frame_html')[0]
                        var measureUnit = data.measureunit
                        var unitLinkCheckboxHtml = unitLinkCheckboxFrameHtml.innerHTML

                        unitLinkCheckboxHtml = unitLinkCheckboxHtml.replace(/hidden/g, '')
                        unitLinkCheckboxHtml = unitLinkCheckboxHtml.replace(/measureunit-id/g, measureUnit.id)
                        unitLinkCheckboxHtml = unitLinkCheckboxHtml.replace(/unit-description/g, measureUnit.unit_description)
                        
                        unitLinks.innerHTML = unitLinkCheckboxHtml + unitLinks.innerHTML

                        $(unitLinks).find('#unit_' + measureUnit.id)[0].checked = true
                        $(unitLinks).find('#unit_' + measureUnit.id)[0].classList.add('added_unit')
                        measureUnitChange('unit_' + measureUnit.id)

                        // Clear the value of the new unit input
                        newUnitInput.val('')
                        // Hide the new unit input
                        newUnitInput.hide()
                        // Hide the unit create link
                        unitCreateLink.style.display = 'none'
                        // Show the unit add link
                        unitAddLink.style.display = 'block'
                        break;
                
                    default:
                        break;
                }
            }
            button.disabled = false
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

/**
 * 
 * @param {*} sectionId
 *  
 */
function planTypeChanged(sectionId) {
    var section = document.getElementById(sectionId)
    var daysToCount =  $(section).find('.days_to_count')[0]

    planTypeSelect = $(section).find('.plan_type_select')[0]
    if(planTypeSelect.options[planTypeSelect.selectedIndex].value == 2){
        daysToCount.disabled = true
        daysToCount.setAttribute('oldValue', daysToCount.value)
        daysToCount.value = ''
    }
    else{
        daysToCount.disabled = false
        daysToCount.value = daysToCount.getAttribute('oldValue') !== undefined ? daysToCount.getAttribute('oldValue') : 0
    }
}

/**
 * 
 * Discard all 
 */
function discardAllSupplierEditions(){

}
