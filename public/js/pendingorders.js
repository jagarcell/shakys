$(document).ready(function(){
    // Create an options HTML for all the product quantity selects
    var optionsHTML = ""
    for(var i = 1; i < 201; i++){
        optionsHTML += "<option value='" + i + "'>" + i + "</option>"
    }

    var allProductsSearchText = document.getElementById('all_products_search_text')

    allProductsSearchText.addEventListener("keyup", function (event){
        if(event.code == 'Enter'){
            allProductsSearchClick()
        }        
    })

    // Prepare the products qty selects
    var orderQtys = document.getElementsByClassName('order_qty_select')
    //
    $.each(orderQtys, function(index, orderQty){
        orderQty.innerHTML += optionsHTML
        if(orderQty.options[orderQty.getAttribute("qty_to_order")] !== undefined){
            orderQty.options[orderQty.getAttribute("qty_to_order")].setAttribute("selected", "")
        }
    })

    // Prepare the Orders Products Qty
    var orderQtyDisplaySelects = document.getElementsByClassName('qty_select')
    
    $.each(orderQtyDisplaySelects, function(index, orderQtyDisplaySelect){
        // Sets the options
        orderQtyDisplaySelect.innerHTML += optionsHTML
        // Select the option that matches the products qty_to_order (value stored in the qty attribute)
        orderQtyDisplaySelect.options[orderQtyDisplaySelect.getAttribute("qty")].setAttribute("selected", "")
    })

    // Obtains the last active Tab ID from the hidden input that holds it
    // That value came from the backend through blade
    var tabId = $('#tab_id').val()

    // Opens the last active Tab element
    openTab(tabId)
})

function openTab(tabId){
    // Gets the tab element
    var tab = document.getElementById(tabId)
    var tabLinks = document.getElementsByClassName('w-tab-link')
    var tabPanes = document.getElementsByClassName('w-tab-pane')

    $.each(tabLinks, function(index, tabLink){
        tabLink.classList.remove('w--current')
        if(tab.getAttribute('data-w-tab') == tabLink.getAttribute('data-w-tab')){
            tabLink.classList.add('w--current')
        }
    })

    $.each(tabPanes, function(index, tabPane){
        tabPane.classList.remove('w--tab-active')
        if(tab.getAttribute('data-w-tab') == tabPane.getAttribute('data-w-tab')){
            tabPane.classList.add('w--tab-active')
        }
    })

    if(tab.getAttribute('data-w-tab') == "Tab 3"){
        $('#add_to_order_button').show()
    }
    else{
        $('#add_to_order_button').hide()
    }

    if(tab.getAttribute('data-w-tab') == "Tab 4"){
        $('#all_products_add_to_order_button').show()
    }
    else{
        $('#all_products_add_to_order_button').hide()
    }

    if(tab.getAttribute('data-w-tab') == "Tab 7"){
        document.getElementById('not_found_add_to_order_button').style.display = 'block'
    }
    else{
        document.getElementById('not_found_add_to_order_button').style.display = 'none'
    }
    $('.pending_content').show()
}

function tabClick(tab) {
    // When a tab is clicked we redirect to pending orders panel 
    // with the appropiate Tab ID 
    window.location.replace('/showpendingorderspanel?tab_id=' + tab.id)
}

function closeOrder(){
    document.getElementById('order_top_id').style.display = 'none'
}

function closeDiscarded(){
    document.getElementById('discarded_top_id').style.display = 'none'
}

function productClick(productId, button){
    if(button !== undefined){
        button.disabled = true
    }
    $.get('/getproduct',
        {
            id:productId.replace('pending_', ''),
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var orderTopMostHTML = document.getElementById('order_top_most').innerHTML
                        var orderTopId = document.getElementById('order_top_id')
                        orderTopId.innerHTML = orderTopMostHTML
                        var measureUnitSelect = $(orderTopId).find('#measure_unit')[0]

                        // Let's prepare the measure units select
                        $.each(product.measure_units, function(index, measureUnit){
                            var option = document.createElement("option")
                            option.value = measureUnit.id
                            option.text = measureUnit.unit_description
                            if(product.default_measure_unit_id == measureUnit.id){
                                option.setAttribute("selected", true)
                            }
                            measureUnitSelect.options.add(option)
                        })                        

                        $(orderTopId).find('#product_order_image')[0].src = product.image_path
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/internal-description/g, product.internal_description)
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/product-id/g, product.id)

                        $(orderTopId).show()
                        $(orderTopId).find('#qty').focus()
                        break;
                
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

function discardedProductClick(productId, button){
    if(button !== undefined){
        button.disabled = true
    }
    $.get('/getproduct',
        {
            id:productId.replace('discarded_', ''),
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var orderTopMostHTML = document.getElementById('discarded_top_most').innerHTML
                        var orderTopId = document.getElementById('discarded_top_id')
                        orderTopId.innerHTML = orderTopMostHTML
                        var measureUnitSelect = $(orderTopId).find('#discarded_measure_unit')[0]

                        // Let's prepare the measure units select
                        $.each(product.measure_units, function(index, measureUnit){
                            var option = document.createElement("option")
                            option.value = measureUnit.id
                            option.text = measureUnit.unit_description
                            if(product.default_measure_unit_id == measureUnit.id){
                                option.setAttribute("selected", true)
                            }
                            measureUnitSelect.options.add(option)
                        })

                        var discardedDaysToCount = $(orderTopId).find('#discarded_days_to_count')[0]
                        discardedDaysToCount.options[product.days_to_count].setAttribute("selected", true)

                        $(orderTopId).find('#discarded_product_image')[0].src = product.image_path
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/internal-description/g, product.internal_description)
                        orderTopId.innerHTML = orderTopId.innerHTML.replace(/product-id/g, product.id)

                        $(orderTopId).show()
                        $(orderTopId).find('#qty').focus()
                        break;
                
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

function orderClick(productId, button){
    if(button !== undefined){
        button.disabled = true
    }
    var qty = $('#order_top_id').find('#qty').val()
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
                        var markAsCounted = document.getElementById('pending_' + product.id)

                        markAsCounted.outerHTML = ""
                        var products = $('.product')
                        $.each(products, function(index, product){
                            product.classList.remove("bbg")
                            product.classList.remove("rbg")
                            if(Math.floor(index / 2) * 2 == index){
                                product.classList.add('rbg')
                            }
                            else{
                                product.classList.add('bbg')
                            }
                        })
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
                button.disabled = false
            }
        })
    }
    else{
        closeOrder()
    }
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
                    var markAsCounted = document.getElementById('pending_' + product.id)
                    if(markAsCounted !== undefined && markAsCounted !== null){
                        markAsCounted.outerHTML = ""
                    }
                    var products = $('.product')
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

function requestAndRescheduleClick(productId, button){
    if(button !== undefined){
        button.disabled = true
    }
    var qty = $('#discarded_top_id').find('#discarded_qty').val()
    var measureUnitSelect = $('#discarded_top_id').find('#discarded_measure_unit')[0]
    var measureUnitId = measureUnitSelect.options[measureUnitSelect.selectedIndex].value
    var daysToCountSelect =  $('#discarded_top_id').find('#discarded_days_to_count')[0]
    var daysToCount = daysToCountSelect.options[daysToCountSelect.selectedIndex].value

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
            days_to_count:daysToCount,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var markAsCounted = document.getElementById('discarded_' + product.id)

                        markAsCounted.outerHTML = ""
                        var products = $('.discarded_product')
                        if(products.length == 0){
                            window.location.replace('/showpendingorderspanel?tab_id=tab_2')
                        }
                        closeDiscarded()
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
                button.disabled = false
            }
        })
    }
    else{
        closeDiscarded()
    }    
}

function rescheduleClick(productId, button){
    if(button !== undefined){
        button.disabled = true
    }
    var measureUnitSelect = $('#discarded_top_id').find('#discarded_measure_unit')[0]
    var measureUnitId = measureUnitSelect.options[measureUnitSelect.selectedIndex].value
    var daysToCountSelect =  $('#discarded_top_id').find('#discarded_days_to_count')[0]
    var daysToCount = daysToCountSelect.options[daysToCountSelect.selectedIndex].value

    $.post('/reschedulecount',
    {
        _token:$('meta[name="csrf-token"]').attr('content'),
        id:productId,
        days_to_count:daysToCount,
        element_tag:productId,
    }, function(data, status){
        if(status == 'success'){
            switch (data.status) {
                case 'ok':
                    var product = data.product
                    var markAsCounted = document.getElementById('discarded_' + product.id)

                    markAsCounted.outerHTML = ""
                    var products = $('.discarded_product')
                    if(products.length == 0){
                        window.location.replace('/showpendingorderspanel?tab_id=tab_2')
                    }
                    closeDiscarded()
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
            button.disabled = false
        }
    })
}

function supplierSelChange(uiSectionId) {
    var uiSection = $('#' + uiSectionId)
    supplierSel = uiSection.find('#product_supplier_select')[0]
    var supplier = supplierSel.options[supplierSel.options.selectedIndex]
    var supplier_id = supplier.getAttribute("value")
    var product_id = uiSection[0].getAttribute("productId")
    var measure_unit_selected_index = uiSection.find('.order_unit_sel')[0].selectedIndex
    var measure_unit_id = uiSection.find('.order_unit_sel')[0].options[measure_unit_selected_index].value
 
    supplierSel.style = ""

    // Check if the supplier is pickup or delivery
    var orderPickupGuySelect = uiSection.find('#order_pickup_guy_select')[0]
    var orderPickupSelect = uiSection.find('#order_pickup_select')[0]
    var orderPriceField = uiSection.find('#order_price_field')

    if(supplier.getAttribute('pickup') == 'delivery'){
        orderPickupGuySelect.setAttribute("disabled", "")
        orderPickupSelect.selectedIndex = indexOfValue(orderPickupSelect, 'delivery')
    }
    else{
        orderPickupGuySelect.removeAttribute("disabled")
        orderPickupSelect.selectedIndex = indexOfValue(orderPickupSelect, 'pickup')
    }

    var lastPickupGuy = supplier.getAttribute('last_pickup_guy')
    if(lastPickupGuy !== undefined){
        orderPickupGuySelect.selectedIndex = indexOfValue(orderPickupGuySelect, supplier.getAttribute('last_pickup_guy'))
    }
    else{
        orderPickupGuySelect.selectedIndex = 0
    }

    $.get('getsupplierprice',
        {
            supplier_id:supplier_id,
            product_id:product_id,
            measure_unit_id:measure_unit_id,
            element_tag:uiSection[0].id,
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                var uiSection = document.getElementById(element_tag)
                var orderPriceField = $(uiSection).find('.order_price_field')[0]
                switch (data.status) {
                    case 'ok':
                        $(orderPriceField).val(data.supplier_price)
                        break;

                    case 'error':
                        break

                    case 'notfound':
                        $(orderPriceField).val(0)
                        break

                    default:
                        break;
                }
            }
        }
    )
}

function indexOfValue(select, value) {
    var index = 0;
    $.each(select.options, function(optionIndex, option){
        if(option.value == value){
            index = optionIndex
        }
    })
    return index
}

function orderPickupSelectChange(orderPickupGuySelect) {
    orderPickupGuySelect.style = ""
    if(orderPickupGuySelect.options[orderPickupGuySelect.options.selectedIndex].value == 'pickup'){
        $(garcellParentNodeByClassName(orderPickupGuySelect, 'request_section')).find('#order_pickup_guy_select')[0].removeAttribute("disabled")
    }
    else{
        $(garcellParentNodeByClassName(orderPickupGuySelect, 'request_section')).find('#order_pickup_guy_select')[0].setAttribute("disabled", "")
    }
}

function orderTypeSelectChange(orderTypeSelect, orderId){
    var orderType = orderTypeSelect.options[orderTypeSelect.selectedIndex].getAttribute("value")
    var order = document.getElementById(orderId)
    var orderPickupDisplayWrap = $(order).find('#order_pickup_display_wrap')[0]

    if(orderType == 'delivery'){
        orderPickupDisplayWrap.style.display = 'none'
    }
    else{
        orderPickupDisplayWrap.style.display = 'block'
    }
}

function orderPickupGuySelectChange(orderPickupGuySelect){
    orderPickupGuySelect.style = ""
}

function orderUnitSelectChange(uiSectionId) {
    supplierSelChange(uiSectionId)
}

function submitOrderButtonClick(order_id, button){
    if(button !== undefined){
        button.disabled = true
    }
    var order = document.getElementById(order_id)
    var orderSupplierSelect = $(order).find('#order_supplier_select')[0]
    var orderTypeSelect = $(order).find('#order_type_select')[0]
    var orderPickupUserSelect = $(order).find('#order_pickup_user_select')[0]
    var orderLines = document.getElementsByClassName('approval_order_line')
    var lines = []
    var actionResultMessage = $('#' + order_id).find('#action_result_message')

    reportResult(
        {
            frame:actionResultMessage,
            alignTop:false,
            message:"PROCESSING ORDER",
            error:false,
        }
    )    
    $.each(orderLines, function(index, orderLine){
        var productQtySelect = $(orderLine).find('#product_qty_display_select')[0]
        var line = 
            {
                id:orderLine.id,
                qty:productQtySelect.options[productQtySelect.selectedIndex].getAttribute('value'),
            }
        lines.push(line)
    })
    


    $.post('/submitorder',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            order_id:order_id.replace('approval_', ''),
            supplier_id:orderSupplierSelect.options[orderSupplierSelect.selectedIndex].getAttribute('value'),
            order_type:orderTypeSelect.options[orderTypeSelect.selectedIndex].getAttribute('value'),
            pickup_user_id:orderPickupUserSelect.options[orderPickupUserSelect.selectedIndex].getAttribute('value'),
            order_lines:lines,
            element_tag:order_id,
        },
        function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#' + elementTag).find('#action_result_message')

                switch (data.status) {
                    case 'ok':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                alignTop:false,
                                message:"THE ORDER WAS SENT TO " + data.order.email,
                                error:false,
                                param:elementTag,
                            }, function(frame, elementTag){
                                frame.hide()
                                var order = document.getElementById(elementTag)
                                order.outerHTML = ""
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
                    case 'emailnotsent':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"ORDER SUBMITTED BUT EMAIL NOT SENT",
                                alignTop:false,
                                timeout:1,
                            }, function(frame, param){
                                frame[0].innerHTML += 
                                    "<div style='color:blue; text-decoration:underline;'><a href='/orderpreview?id=" + elementTag.replace('approval_', '') + "&previousURL=showpendingorderspanel?tab_id=tab_5'>View The Order</a></div>";
                            }
                        )   
                        break 
                    case 'noemail':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"ORDER SUBMITTED BUT EMAIL NOT SENT",
                                alignTop:false,
                                timeout:1,
                            }, function(frame, param){
                                frame[0].innerHTML += 
                                    "<div style='color:blue; text-decoration:underline;'><a href='/orderpreview?id=" + elementTag.replace('approval_', '') + "&previousURL=showpendingorderspanel?tab_id=tab_5'>View The Order</a></div>";
                            }
                        )
                        break        
                    default:
                        break
                }
            }
            button.disabled = false
        }
    )
}

function resendOrderButtonClick(orderId, button){
    if(button !== undefined){
        button.disabled = true
    }
    var actionResultMessage = $('#action_result_message_' + orderId.replace('submitted_', ''))
    reportResult(
        {
            frame:actionResultMessage,
            message:"SENDING THE ORDER ...",
            error:false,
            alignTop:false,
        }
    )
    $.post('/emailorder',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            order_id:orderId.replace('submitted_', ''),
            element_tag:orderId.replace('submitted_', ''),
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                var actionResultMessage = $('#action_result_message_' + elementTag)
                switch (data.status) {
                    case 'ok':
                        var order = data.order
                        reportResult(
                            {
                                frame:actionResultMessage,
                                error:false,
                                alignTop:false,
                                message:"THE ORDER WAS SUCCESFULLY SENT TO:<br> " + order.email,
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                        break

                    case 'notfound':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"ORDER NOT FOUND",
                            }, function(frame, param){
                                frame.hide()
                            }
                        )
                        break

                    case 'noemail':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"THERE IS NO EMAIL REGISTERED",
                                alignTop:false,
                                timeout:1,
                            }, function(frame, param){
                                frame[0].innerHTML += 
                                    "<div style='color:blue; text-decoration:underline;'><a href='/orderpreview?id=" + elementTag + "&previousURL=showpendingorderspanel?tab_id=tab_5'>View The Order</a></div>";
                            }
                        )
                        break        
                    case 'error':
                        var message = data.message
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:true,
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

function receiveOrderButtonClick(orderId, button) {
    if(button !== undefined){
        button.disabled = true
    }
    var actionResultMessage = $('#action_result_message_' + orderId.replace('submitted_', ''))
    var submittedOrdersTab = document.getElementById('submitted_orders_tab')
    var order = $(submittedOrdersTab).find("#" + orderId)
    var order_lines = order.find('.submitted_order_line')
    
    var lines = []

    /* Show a message for the oreder receiving process */
    reportResult(
        {
            frame:actionResultMessage,
            error:false,
            message:"RECEIVING THE ORDER ...",
            alignTop:false,
        }
    )
    /* Prepare the order lines param array */    
    $.each(order_lines, function(index, order_line){
        var available_qty = $(order_line).find('.available_qty')[0].selectedIndex
        var supplier_price = $(order_line).find('.submitted_supplier_price').val()
        lines.push({id:order_line.id, available_qty:available_qty, supplier_price:supplier_price})
    })

    /* Request to receive the order */
    $.post('/receiveorder',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:orderId.replace('submitted_', ''),
            order_lines:lines,
            element_tag:orderId,
        }, function(data, status){
            if(status == 'success'){
                var actionResultMessage = $('#action_result_message_' + orderId)
                var elementTag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var submittedOrdersTab = document.getElementById('submitted_orders_tab')
                        var order = $(submittedOrdersTab).find("#" + elementTag)[0]

                        order.outerHTML = ""
                        break
 
                    case 'notfound':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"ORDER NOT FOUND",
                                alignTop:false,
                            }, function(frame, param){
                                frame.hide()
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

function addToOrderClick(addCheckClass, prefixToReplace, button) {
    if(button !== undefined){
        button.disabled = true
    }

    document.getElementById('wait_dialog').style.display = 'block'

    var productsToOrder = []
    var checkedToOrder = $('.' + addCheckClass + ':checkbox:checked')
    $.each(checkedToOrder, function(index, toOrder){
        var uiSection = garcellParentNodeByClassName(toOrder, 'request_section')
        var supplierSel = $(uiSection).find('#product_supplier_select')[0]
        var productId = uiSection.id.replace(prefixToReplace, '')
        var supplierId = supplierSel.options[supplierSel.selectedIndex].getAttribute("value")
        var orderQtySel = $(uiSection).find('#order_qty_sel')[0]
        var orderUnitSelect = $(uiSection).find('.order_unit_sel')[0]
        var measuretUnitId = orderUnitSelect.options[orderUnitSelect.selectedIndex].getAttribute("value")
        var originalUnitId = orderUnitSelect.getAttribute("original_unit_id")
        var qty = orderQtySel.options[orderQtySel.selectedIndex].getAttribute("value")
        var orderPickupSelect = $(uiSection).find('#order_pickup_select')[0]
        var pickup = orderPickupSelect.options[orderPickupSelect.selectedIndex].getAttribute("value")
        
        var orderPickupGuySelect = $(uiSection).find('#order_pickup_guy_select')[0]
        var orderPickupGuy = orderPickupGuySelect.options[orderPickupGuySelect.selectedIndex].getAttribute("value")

        if(supplierId == -1){
            supplierSel.style.color = 'red'
            supplierSel.style.backgroundColor = 'cadetblue'
            supplierSel.style.fontStyle = 'italic'
            if(button !== undefined){
                button.disabled = false
            }
            
            document.getElementById('wait_dialog').style.display = 'none'

            showSomeMissingFieldsMessage()

            return
        }

        if(pickup == 'pickup' && orderPickupGuy == -1){
            orderPickupGuySelect.style.color = 'red'
            orderPickupGuySelect.style.backgroundColor = 'cadetblue'
            orderPickupGuySelect.style.fontStyle = 'italic'
            if(button !== undefined){
                button.disabled = false
            }
            
            document.getElementById('wait_dialog').style.display = 'none'
            showSomeMissingFieldsMessage()

            return
        }

        productsToOrder.push(
            {
                supplier_id:supplierId,
                pickup:pickup,
                qty:qty,
                original_unit_id:originalUnitId,
                measure_unit_id:measuretUnitId,
                pickup_guy_id:orderPickupGuy,
                product_id:productId,
                element_tag:uiSection.id,
            }
        )
    })

    $.post('/addtoorder',
    {
        _token: $('meta[name="csrf-token"]').attr('content'),
        productsToOrder:productsToOrder,
    }, function(data, status){
            if(status == 'success'){
                var elementTags = data.element_tags
                switch (data.status) {
                    case 'ok':
                        $.each(elementTags, function(index, elementTag){
                            var uiSection = document.getElementById(elementTag)
                            uiSection.outerHTML = ""
                        })

                        var uiSections = document.getElementsByClassName('ui_section')
                        $.each(uiSections, function(index, uiSection){
                            uiSection.classList.remove('bbg')
                            uiSection.classList.remove('rbg')
                            if(Math.round(index / 2) * 2 == index){
                                uiSection.classList.add('rbg')
                            }
                            else{
                                uiSection.classList.add('bbg')
                            }
                        })
                        break;
                    default:
                        break;
                }
            }

            document.getElementById('wait_dialog').style.display = 'none'

            if(button !== undefined){
                button.disabled = false
            }
        }
    )
}

function showSomeMissingFieldsMessage() {
    document.getElementById('missing_fields_dialog').style.display = 'block'
    setTimeout(
        () => {
            document.getElementById('missing_fields_dialog').style.display = 'none'
        }, 3000
    )
}

function orderSupplierSelectChange(supplierSelect, orderSectionId){
    var orderSection = document.getElementById(orderSectionId)
    var supplierId = supplierSelect.options[supplierSelect.selectedIndex].getAttribute("value") 
    var prices = $(orderSection).find('.approval_order_price')
    var productIdsArray = []
    $.each(prices, function(index, price){
        productIdsArray.push(price.getAttribute("productId"))
    })
    $.get('getpricesforsupplier',
        {
            supplier_id:supplierId,
            product_ids:productIdsArray,
            element_tag:orderSectionId,
        }, function(data, status){
            if(status == 'success'){
                var element_tag = data.element_tag
                var actionResultMessage = $('#' + element_tag).find('#action_result_message')
                switch (data.status) {
                    case 'ok':
                        var productsPrices = data.productsprices
                        var orderSection = document.getElementById(orderSectionId)
                        var prices = $(orderSection).find('.approval_order_price')
 
                        $.each(prices, function(index, price){
                            var productId = price.getAttribute("productId")
                            var productPrice = productsPrices[productId]

                            if(productPrice !== undefined){
                                price.setAttribute("value", productPrice)
                            }
                            else{
                                price.setAttribute("value", 0)
                            }
                        })
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
                    
                    default:
                        break
                }
            }
        }
    )
}

function allProductsSearchClick() {
    var allProductsSearchText = document.getElementById('all_products_search_text').value
    // When a serach is clicked we redirect to pending orders panel 
    // with the Tab ID of all the products and the search text 
    window.location.replace('/showpendingorderspanel?tab_id=tab_4&search_text=' + allProductsSearchText)
}
