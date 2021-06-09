$(document).ready(function(){
    var optionsHTML = ""
    for(var i = 1; i < 201; i++){
        optionsHTML += "<option value='" + i + "'>" + i + "</option>"
    }

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

    // Obtains the Tab ID from the hidden input that holds it
    var tabId = $('#tab_id').val()
    // Gets the tab element
    var tab = document.getElementById(tabId)
    // Opens the Tab element
    openTab(tab)
})

function openTab(element){
    var tabLinks = document.getElementsByClassName('w-tab-link')
    var tabPanes = document.getElementsByClassName('w-tab-pane')

    $.each(tabLinks, function(index, tabLink){
        tabLink.classList.remove('w--current')
        if(element.getAttribute('data-w-tab') == tabLink.getAttribute('data-w-tab')){
            tabLink.classList.add('w--current')
        }
    })

    $.each(tabPanes, function(index, tabPane){
        tabPane.classList.remove('w--tab-active')
        if(element.getAttribute('data-w-tab') == tabPane.getAttribute('data-w-tab')){
            tabPane.classList.add('w--tab-active')
        }
    })

    if(element.getAttribute('data-w-tab') == "Tab 2"){
        $('#add_to_order_button').show()
    }
    else{
        $('#add_to_order_button').hide()
    }

    if(element.getAttribute('data-w-tab') == "Tab 3"){
        $('#all_products_add_to_order_button').show()
    }
    else{
        $('#all_products_add_to_order_button').hide()
    }
}

function tabClick(element) {
    // When a tab is clicked we redirect to pending orders panel 
    // with the appropiate Tab ID 
    window.location.replace('/showpendingorderspanel?tab_id=' + element.id)
}

function closeOrder(){
    document.getElementById('order_top_id').style.display = 'none'
}

function productClick(productId){
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
                        var orderTopMostHTML = document.getElementById('order_top_most').innerHTML
                        var orderTopId = document.getElementById('order_top_id')

                        orderTopId.innerHTML = orderTopMostHTML
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
        }
    )
}

function orderClick(productId){
    var qty = $('#order_top_id').find('#qty').val()
    if(qty > 0)
    {
        $.post('/markascounted',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:productId,
            qty_to_order:qty,
            element_tag:productId,
        }, function(data, status){
            if(status == 'success'){
                switch (data.status) {
                    case 'ok':
                        var product = data.product
                        var markAsCounted = document.getElementById(product.id)
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
        })
    }
    else{
        closeOrder()
    }
}

function supplierSelChange(supplierSel) {
    var uiSection = $(garcellParentNodeByClassName(supplierSel, 'ui_section'))
    var supplier = supplierSel.options[supplierSel.options.selectedIndex]
    supplierSel.style = ""

    // Check if the supplier is pickup or delivery
    var orderPickupGuySelect = uiSection.find('#order_pickup_guy_select')[0]
    var orderPickupSelect = uiSection.find('#order_pickup_select')[0]
    if(supplier.getAttribute('pickup') == 'delivery'){
        uiSection.find('#order_pickup_guy_wrap').hide()
        orderPickupSelect.selectedIndex = indexOfValue(orderPickupSelect, 'delivery')
    }
    else{
        uiSection.find('#order_pickup_guy_wrap').show()
        orderPickupSelect.selectedIndex = indexOfValue(orderPickupSelect, 'pickup')
    }

    var lastPickupGuy = supplier.getAttribute('last_pickup_guy')
    if(lastPickupGuy !== undefined){
        orderPickupGuySelect.selectedIndex = indexOfValue(orderPickupGuySelect, supplier.getAttribute('last_pickup_guy'))
    }
    else{
        orderPickupGuySelect.selectedIndex = 0
    }
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
        $(garcellParentNodeByClassName(orderPickupGuySelect, 'ui_section')).find('#order_pickup_guy_wrap').show()
    }
    else{
        $(garcellParentNodeByClassName(orderPickupGuySelect, 'ui_section')).find('#order_pickup_guy_wrap').hide()
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

function submitOrderButtonClick(order_id){
    var order = document.getElementById(order_id)
    var orderSupplierSelect = $(order).find('#order_supplier_select')[0]
    var orderTypeSelect = $(order).find('#order_type_select')[0]
    var orderPickupUserSelect = $(order).find('#order_pickup_user_select')[0]
    var orderLines = document.getElementsByClassName('approval_order_line')
    var lines = []

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
            order_id:order_id,
            supplier_id:orderSupplierSelect.options[orderSupplierSelect.selectedIndex].getAttribute('value'),
            order_type:orderTypeSelect.options[orderTypeSelect.selectedIndex].getAttribute('value'),
            pickup_user_id:orderPickupUserSelect.options[orderPickupUserSelect.selectedIndex].getAttribute('value'),
            order_lines:lines,
            element_tag:order_id,
        },
        function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var order = document.getElementById(elementTag)
                        order.outerHTML = ""
                        break
                
                    case 'error':

                        break
    
                    default:
                        break
                }
            }
        }
    )
}

function resendOrderButtonClick(orderId){
    $.post('/emailorder',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            order_id:orderId,
            element_tag:orderId,
        }, function(data, status){
            if(status == 'success'){
                var elementTag = data.element_tag;
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
                            }, function(frame, parama){
                                frame.hide()
                            }
                        )
                        break        
                    case 'error':
                        var message = data.message
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:message,
                                alignTop:false
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

function addToOrderClick(addCheckClass) {
    var checkedToOrder = $('.' + addCheckClass + ':checkbox:checked')
    $.each(checkedToOrder, function(index, toOrder){
        var uiSection = garcellParentNodeByClassName(toOrder, 'ui_section')
        var supplierSel = $(uiSection).find('#product_supplier_select')[0]
        var productId = uiSection.id
        var supplierId = supplierSel.options[supplierSel.selectedIndex].getAttribute("value")
        var orderQtySel = $(uiSection).find('#order_qty_sel')[0]
        var qty = orderQtySel.options[orderQtySel.selectedIndex].getAttribute("value")
        var orderPickupSelect = $(uiSection).find('#order_pickup_select')[0]
        var pickup = orderPickupSelect.options[orderPickupSelect.selectedIndex].getAttribute("value")
        var orderPickupGuySelect = $(uiSection).find('#order_pickup_guy_select')[0]
        var orderPickupGuy = orderPickupGuySelect.options[orderPickupGuySelect.selectedIndex].getAttribute("value")
        
        if(supplierId == -1){
            supplierSel.style.color = 'red'
            supplierSel.style.backgroundColor = 'cadetblue'
            supplierSel.style.fontStyle = 'italic'
            return
        }
        if(orderPickupGuy == -1){
            orderPickupGuySelect.style.color = 'red'
            orderPickupGuySelect.style.backgroundColor = 'cadetblue'
            orderPickupGuySelect.style.fontStyle = 'italic'
            return
        }
        
        $.post('/addtoorder',
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                supplier_id:supplierId,
                pickup:pickup,
                qty:qty,
                pickup_guy_id:orderPickupGuy,
                product_id:productId,
                element_tag:productId,
            }, function(data, status){
                if(status == 'success'){
                    var elementTag = data.element_tag
                    switch (data.status) {
                        case 'ok':
                            var uiSection = document.getElementById(elementTag)
                            uiSection.outerHTML = ""
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
            }
        )
    })
}
