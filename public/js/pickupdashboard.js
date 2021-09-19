$(document).ready(function(){
    var selectHtml = ''
    for(var i = 0; i < 201; i++){
        selectHtml = selectHtml + '<option value="' + i + '">' + i + '</option>'
    }
    var selects = document.getElementsByClassName('order_detail_qty_purchased')
    $.each(selects, function(index, select){
        select.innerHTML += selectHtml
        select.options[select.getAttribute("qty")].setAttribute("selected", "")
    })

    var locationStopSelects = document.getElementsByClassName('location_stop_select')

    $.each(locationStopSelects, function(index, locationStopSelect){
        var locationStop = locationStopSelect.getAttribute('location_stop')

        if(locationStop != '-1'){
            locationStopSelect.options[locationStop].setAttribute('selected', true)
        }
    })
})

/**
 * 
 * Action that opens that order details when 
 * the user clicks on an order header
 * 
 * @param {HTML element} pickupOrder
 *  
 */
function pickupOrderClick(pickupOrderHeader) {
    // Order element
    var pickupOrder = $(garcellParentNodeByClassName(pickupOrderHeader, 'pickup_order'))

    // Order details element
    var orderDetails = pickupOrder.find('.order_details_wrap')

    // If not showing yet then show
    if(pickupOrderHeader.getAttribute("showing") === null){
        // Hide all the orders heder
        $('.pickup_order').hide()

        // And show only the one that will show the details
        pickupOrder.show()

        // Set all the lines to show
        $('.order_detail_line').show()

        // Set the "showing" attribute
        pickupOrderHeader.setAttribute("showing", "")
        orderDetails.show()
    }
    // If it was showing then hide it
    else{
        // Hide the current order details
        orderDetails.hide()

        // Remove showing attribute
        pickupOrderHeader.removeAttribute("showing")

        // Show all the order headers
        $('.pickup_order').show()
    }
}

function hideOrderLineClick(orderLineId, orderId, lineId) {
    $.post('checkorderline', 
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:lineId,
            checked:1,
        },
        function(data, status){
            if(status == 'success'){
                var actionResultMessage = $('#action_result_message')
                switch(data.status){
                    case 'ok':
                        var orderLine = document.getElementById(orderLineId)
                        $(orderLine).find('.pickup_check_button')[0].style.display = 'none'
                        $(orderLine).find('.pickup_uncheck_button')[0].style.display = 'block'
                        var orderLineHtml = orderLine.outerHTML
                        var orderLines = $('#' + orderId).find('.order_lines')[0]
                        orderLine.outerHTML = ""
                        orderLines.innerHTML += orderLineHtml
                        break
                    case '419':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"SESSION EXPIRED, PLEASE REFRESH YOUR BROWSER!",
                            }
                        )
                        break    
                }
            }
        }
    );
}

function showOrderLineClick(orderLineId, orderId, lineId) {
    $.post('checkorderline', 
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:lineId,
            checked:0,
        },
        function(data, status){
            if(status == 'success'){
                var actionResultMessage = $('#action_result_message')
 
                switch(data.status){
                    case 'ok':
                        var orderLine = document.getElementById(orderLineId)
                        $(orderLine).find('.pickup_uncheck_button')[0].style.display = 'none'
                        $(orderLine).find('.pickup_check_button')[0].style.display = 'block'
                        var orderLineHtml = orderLine.outerHTML
                        var orderLines = $('#' + orderId).find('.order_lines')[0]
                        orderLine.outerHTML = ""
                        orderLines.innerHTML = orderLineHtml + orderLines.innerHTML
                        break
                    case '419':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"SESSION EXPIRED, PLEASE REFRESH YOUR BROWSER!",
                            }
                        )

                        break
                }
            }
        }
    );

    
}

function showAllOrderLinesClick(orderId) {
    var order = document.getElementById(orderId)
    var orderLines = $(order).find('.order_detail_line')
    $.each(orderLines, function(index, orderLine){
        
        $(orderLine).show()
    })
}

function allDone(orderId) {
    var lines = []
    var order = $('#' + orderId)
    var orderLines = order.find('.order_detail_line')
    $.each(orderLines, function(index, orderLine){
        var available_select = order.find("#available_" + orderLine.id)[0]
        var stop = order.find('#stop_' + orderLine.id)[0]
        lines.push(
            {
                id:orderLine.getAttribute("lineid"), 
                available_qty:available_select.selectedIndex,
                location_stop:stop.selectedIndex,
            }
        )
    })

    $.post('/completeorder',
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id:orderId,
            order_lines:lines,
            element_tag:orderId,
        }, function(data, status){
            if(status == 'success'){
                var actionResultMessage = $('#action_result_message')
                var elementTag = data.element_tag
                switch (data.status) {
                    case 'ok':
                        var order = document.getElementById(elementTag)

                        // Show all the order headers
                        $('.pickup_order').show()
                    
                        // Remove this order from the list
                        // Will only come up again after a refresh 
                        order.outerHTML = ""
                        if($('.pickup_order').length == 0){
                            $('.no_pickup_orders').show()
                        }
                        break
                        
                    case '419':
                        reportResult(
                            {
                                frame:actionResultMessage,
                                message:"SESSION EXPIRED, PLEASE REFRESH YOUR BROWSER!",
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
