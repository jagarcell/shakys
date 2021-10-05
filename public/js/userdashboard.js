$(document).ready(function(){
    // Create the HTML for the request quantity select control
    var qtySelectHtml = ""
    for(var i = 0; i < 201; i++){
        qtySelectHtml += "<option value='" + i + "'>" + i + "</option>"
    }

    // Aign the HTML to the request quantity select control
    var qtySelect = document.getElementById('qty')
    qtySelect.innerHTML = qtySelectHtml

    // Make the navbar sticky and level 1 depth
    document.getElementById('navbarSupportedContent').style.zIndex = 1
    document.getElementById('navbarSupportedContent').style.position = 'sticky'
    document.getElementById('navbarSupportedContent').style.background = 'white'

    // Add the search bar event listener to respond to an enter
    var searchText = document.getElementById('search_text')
    searchText.addEventListener("keyup", function (event){
        if(event.code == 'Enter'){
            searchClick()
        }        
    })

})

// Set the timeout for the seacrh and preview results to 10 secs
var timeoutInMiliseconds = 10000;
var timeoutId;

/**
 * Starts the back to user panel view timer
 */
function startTimer() { 
    // window.setTimeout returns an Id that can be used to start and stop a timer
    setupTimers()
    timeoutId = window.setTimeout(doInactive, timeoutInMiliseconds)
}

/**
 * When there is no activity detected we go back to the user's panel otiginal view
 */
function doInactive() {
    // does whatever you need it to actually do - probably signs them out or stops polling the server for info
    clearTimers()
    window.location = "/userdashboard"
}
 
/**
 * Creates the activities event listeners
 */
function setupTimers () {
    document.addEventListener("mousemove", resetTimer, false)
    document.addEventListener("mousedown", resetTimer, false)
    document.addEventListener("keypress", resetTimer, false)
    document.addEventListener("touchmove", resetTimer, false)
}
 
/**
 * Remove the activities event listeners
 */
function clearTimers () {
    document.removeEventListener("mousemove", resetTimer, false)
    document.removeEventListener("mousedown", resetTimer, false)
    document.removeEventListener("keypress", resetTimer, false)
    document.removeEventListener("touchmove", resetTimer, false)
}

/**
 * Restarts the tomer
 */
function resetTimer() { 
    window.clearTimeout(timeoutId)
    startTimer();
}

function locationClick(locationId){
    alert(locationId)
}

/**
 * Closes the request dialog
 */
function closeOrder(){
    document.getElementById('order_top_id').style.display = 'none'
}

/**
 * 
 * @param {*} productId 
 * @param {*} button 
 * 
 * This action opens the product request dialog 
 */
function productClick(productId, button){
    if(button !== undefined){
        button.disabled = true
    }

    $.get('/getproduct',
        {
            id:productId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var orderTopMost = document.getElementById('order_top_most')
                        var orderTopId = document.getElementById('order_top_id')
                        orderTopId.innerHTML = orderTopMost.innerHTML
                        $(orderTopId).find('#product_order_image')[0].src = product.image_path
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/internal-description/g, product.internal_description)
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/product-id/g, product.id)
                        $(orderTopId).show()
                        $(orderTopId).find('#qty').focus()

                        var measureUnitSelect = $(orderTopId).find('#measure_unit')[0]
                        // Let's prepare the measure units select
                        $.each(product.measure_units, function(index, measureUnit){
                            var option = document.createElement("option")
                            option.value = measureUnit.id
                            option.text = measureUnit.unit_description
                            if(product.default_measure_unit_id == measureUnit.id){
                                option.selected = true
                            }
                            measureUnitSelect.options.add(option)
                        })                        
                        break;
                
                    default:
                        break;
                }
            }
            if(button !== undefined){
                button.disabled = true
            }
        
        }
    )
}

/**
 * 
 * @param {*} productId 
 * @param {*} button 
 * @returns 
 * 
 * This action submits the request
 */
function orderClick(productId, button){
    if(button !== undefined){
        button.disabled = true
    }

    var qtySelect = $('#order_top_id').find('#qty')[0]
    var qty = qtySelect.selectedIndex
    var measureUnitSelect = $('#order_top_id').find('#measure_unit')[0]
    var measureUnitId = measureUnitSelect.options[measureUnitSelect.selectedIndex].value
    
    if(measureUnitId == -1){
        alert("YOU MUST SELECT A UNIT!")
        return
    }

    if(qty > 0)
    {
        $.post('/markascounted',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:productId,
            qty_to_order:qty,
            measure_unit_id:measureUnitId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var markAsCounted = document.getElementById(product.id)
                        if(markAsCounted !== undefined && markAsCounted !== null){
                            markAsCounted.outerHTML = ""
                        }
                        var products = $('.product')
                        var previewProductLink = document.getElementById('preview_product_link_' + product.id + "_" + product.measure_unit_id)
                        var previewQtyRequest = document.getElementById('preview_qty_request_' + product.id + "_" + product.measure_unit_id)

                        if(previewProductLink === null){
                            $.each(products, function(index, product){
                                product.classList.remove("bbg")
                                product.classList.remove("rbg")
                                if(Math.floor(index / 2) * 2 == index){
                                    product.classList.add('rbg')
                                }
                                else{
                                    product.classList.add('rbg')
                                }
                            })
                        }
                        else{
                            previewQtyRequest.innerHTML = product.qty_to_order
                            $(previewProductLink).find('.product_preview_frame')[0].style.backgroundColor = 'red'
                        }
                        closeOrder()
                        break
                    case 'error':

                        break
                    case '419':
                        break

                    case 'notfound':

                        break

                    default:
                        break
                }
            }
            if(button !== undefined){
                button.disabled = true
            }
        
        })
    }
    else{
        closeOrder()
    }
}

/**
 * This is the action that serves the selective product seacrh
 */
function searchClick() {
    var searchtext = $('#search_text').val()
    $.get('/searchfor',
        {
            searchtext:searchtext,
        }, function(data, status){
            if(status == 'success'){
                switch (data.status) {
                    case 'ok':
                        var products = data.products
                        var productsListWrap = document.getElementById('products_list_wrap')
                        var productCouple = document.createElement("div")
                        productsListWrap.innerHTML = ""
                        $.each(products, function(index, product){
                            if(Math.floor(index / 2) * 2 == index){
                                productCouple = document.createElement("div")
                                productCouple.classList.add('product_couple')
                            }

                            productHtml = document.getElementById('product').innerHTML
                            productHtml = productHtml.replace(/product-id/g, product.id)
                            productHtml = productHtml.replace(/image-path/g, product.image_path)
                            productHtml = productHtml.replace(/internal-description/g, product.internal_description)
                            productCouple.innerHTML += productHtml

                            if(index == products.length - 1 || Math.floor(index / 2) * 2 != index){
                                productsListWrap.innerHTML = productsListWrap.innerHTML + productCouple.outerHTML
                            }
                        })
                        productsListWrap.scrollIntoView(true)
                        startTimer()
                        break;
                    default:
                        break;
                }
            }
        }
    )
}

var imagesCount

/**
 * This is the action to show the counts preview
 */
function userCountPreview(link) {
    if(link !== undefined && link !== null){
        link.hidden = true
    }

    $.get('/getcountedproducts',
        function(data, status){
            if(status == 'success'){
                switch(data.status){
                    case 'ok':
                        var products = data.products
                        var groupIndex = 0
                        var previewRow

                        // Let's populate the preview
                        $.each(products, function(index, product){
                            // Check if a new preview row is starting
                            if(groupIndex == 0){
                                previewRow = document.createElement('div')
                                previewRow.innerHTML = document.getElementById('preview_row_html').innerHTML
                            }
                            productPreviewGroupHTML = document.getElementById('product_preview_group_html').innerHTML
                            productPreviewGroupHTML = productPreviewGroupHTML.replace(/product-id/g, product.id)
                            productPreviewGroupHTML = productPreviewGroupHTML.replace(/src1="image-path"/g, "src=" + product.image_path)
                            productPreviewGroupHTML = productPreviewGroupHTML.replace(/internal-description/g, product.internal_description)
                            productPreviewGroupHTML = productPreviewGroupHTML.replace(/unit-description/g, product.unit_description)
                            productPreviewGroupHTML = productPreviewGroupHTML.replace(/qty-to-order/g, product.qty_to_order)
                            productPreviewGroupHTML = productPreviewGroupHTML.replace(/measure-unit-id/g, product.measure_unit_id)
                            
                            var productPreviewGroup = $(previewRow).find('.' + groupIndex)[0]
                            productPreviewGroup.innerHTML += productPreviewGroupHTML

                            groupIndex++
                            
                            if(groupIndex == 3 || index == products.length - 1){
                                groupIndex = 0
                                document.getElementById('counted_products_preview').innerHTML += previewRow.innerHTML
                            }
                        })

                        var productsView = document.getElementById('products_view')
                        var countedProductsPreview = document.getElementById('counted_products_preview')

                        // Hide products to count view
                        productsView.style.display = 'none'
                        // Show counted products preview
                        countedProductsPreview.style.display = 'block'
                        startTimer()

                        // Adjust the product layouts to fit the tallest one
                        var countedProductsPreview = $('#counted_products_preview')
                        var productPics = $(countedProductsPreview).find('.product_pic')

                        // Sets the ount for the loaded images
                        imagesCount = productPics.length
                                            
                    break
                    case 'error':
                        break
                    default:
                        break
                }
            }
            if(link !== undefined && link !== null){
                link.hidden = false
            }
        }
    );
}

function prodImgLoaded(image) {
    // When an image is loaded ...
    if(image === undefined || image ===null){
        return
    }
    // ..  we check if it has a valid height 
    var height = $(image).height()
    if(height > 0){
        // If the height is valid we count down the loaded images
        imagesCount--
    }
    // If there are still images pending to load ...
    if(imagesCount > 0){
        // ... continue
        return
    }

    $.each($('.preview_row'), function(index, previewRow){
        // When all the images are loaded we
        // compute the heighest section of each row
        $.each($(previewRow).find('.product_preview_frame'), function(index, productPreviewFrame){
            var productPicFrame = $(productPreviewFrame).find('.product_pic_frame')
            var previewProductData = $(productPreviewFrame).find('.preview_product_data')
            var height = productPicFrame.height() + previewProductData.height()

            if(previewRow.getAttribute('height') === undefined || height > previewRow.getAttribute('height')){
                previewRow.setAttribute('height', height)
            }
        })

        // Now we apply the abosulte position to the elements with the lowest height
        // in order to mke them appear aligned with the highest one
        $.each($(previewRow).find('.product_preview_frame'), function(index, productPreviewFrame){
            var productPicFrame = $(productPreviewFrame).find('.product_pic_frame')
            var previewProductData = $(productPreviewFrame).find('.preview_product_data')
            // The height to acomodate the aligned elements
            var height = productPicFrame.height() + previewProductData.height()

            // The offset to place the product image
            var picOffset = ($(productPreviewFrame).height() - previewProductData.height()) / 2 -
                            productPicFrame.height() / 2 + 10

            // If this is not the element with the largest height ...                
            if(height < previewRow.getAttribute('height')){
                // ... Then offset the descriptions and image

                // Description to bottom
                previewProductData[0].classList.add('dynamic_product_alignment')
                previewProductData[0].style.bottom = '10px'

                // Image vertically centered
                productPicFrame[0].classList.add('dynamic_product_alignment')
                productPicFrame[0].style.top = picOffset + 'px'    
            }
        })
    })
}

/**
 * 
 * @param {*} productId 
 * @param {*} button 
 * 
 * This action opens the product request dialog 
 */
 function previewProductClick(productId, measureUnitId, button){
    if(button !== undefined){
        button.disabled = true
    }

    $.get('/getproductrequests',
        {
            product_id:productId,
            measure_unit_id:measureUnitId,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var orderTopMost = document.getElementById('order_top_most')
                        var orderTopId = document.getElementById('order_top_id')
                        orderTopId.innerHTML = orderTopMost.innerHTML
                        $(orderTopId).find('#product_order_image')[0].src = product.image_path
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/internal-description/g, product.internal_description)
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/product-id/g, product.id)
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/measure-unit-id/g, product.measure_unit_id)
                        $(orderTopId).show()

                        var qtySelect = $(orderTopId).find('#qty')
                        qtySelect.focus()
                        qtySelect[0].selectedIndex = product.qty_to_order

                        var measureUnitSelect = $(orderTopId).find('#measure_unit')[0]
                        measureUnitSelect.disabled = true
                        var option = document.createElement('option')
                        option.text = product.unit_description
                        option.value = product.measure_unit_id
                        option.selected = true
                        measureUnitSelect.options.add(option)
                        break;
                
                    default:
                        break;
                }
            }
            if(button !== undefined){
                button.disabled = true
            }
        
        }
    )
}

/**
 * 
 * @param {*} productId 
 * @param {*} button
 *  
 */
function discardOrderClick(productId, button) {
    if(button !== undefined){
        button.disabled = true
    }

    var measureUnitSelect = $('#order_top_id').find('#measure_unit')[0]
    var measureUnitId = measureUnitSelect.options[measureUnitSelect.selectedIndex].value
    
    if(measureUnitId == -1){
        alert("YOU MUST SELECT A UNIT!")
        return
    }

    $.post('/markasdiscarded',
    {
        _token:$('meta[name="csrf-token"]').attr('content'),
        id:productId,
        measure_unit_id:measureUnitId,
        element_tag:productId,
    }, function(data, status){
        if(status == 'success'){
            switch (data.status) {
                case 'ok':
                    var product = data.product
                    var markAsCounted = document.getElementById(product.id)
                    if(markAsCounted !== undefined && markAsCounted !== null){
                        markAsCounted.outerHTML = ""
                    }
                    var products = $('.product')
                    var previewProductLink = document.getElementById('preview_product_link_' + product.id + "_" + product.measure_unit_id)
                    var previewQtyRequest = document.getElementById('preview_qty_request_' + product.id + "_" + product.measure_unit_id)

                    if(previewProductLink === null){
                        $.each(products, function(index, product){
                            product.classList.remove("bbg")
                            product.classList.remove("rbg")
                            if(Math.floor(index / 2) * 2 == index){
                                product.classList.add('rbg')
                            }
                            else{
                                product.classList.add('rbg')
                            }
                        })
                    }
                    else{
                        previewQtyRequest.innerHTML = product.qty_to_order
                        $(previewProductLink).find('.product_preview_frame')[0].style.backgroundColor = 'red'
                    }
                    closeOrder()
                    break
                case 'error':

                    break
                case '419':
                    break

                case 'notfound':

                    break

                default:
                    break
            }
        }
        if(button !== undefined){
            button.disabled = true
        }
    
    })
}