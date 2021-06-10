window.onload = function() {
    /**
     * Cria as opções de pagamento na tela de checkout
     * Div com id paymentGatewaysContainer responsável por renderizar as opções de pagamento
     */

    let divControlPaymentView = document.querySelectorAll("#paymentGatewaysContainer .text-center");
    let optionPix = `<label id="labelPix" class="radio-inline">
    <div class="iradio_square-blue " id="divPix" style="position: relative;"><input id="inputPix" type="radio" name="paymentmethod" value="gerencianetpix" data-payment-type="Invoices" data-show-local="" data-remote-inputs="" class="payment-methods"  style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
    Gerencianet via Pix
</label>`
    let optionBoleto = `<label id="labelBoleto" class="radio-inline">
    <div class="iradio_square-blue " id="divBoleto" style="position: relative;"><input  id="inputBoleto" type="radio" name="paymentmethod" value="gerencianetboleto" data-payment-type="Invoices" data-show-local="" data-remote-inputs="" class="payment-methods"  style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
    Gerencianet via Boleto
</label>`

    divControlPaymentView[0].insertAdjacentHTML("beforeend", optionPix);
    divControlPaymentView[0].insertAdjacentHTML("beforeend", optionBoleto);

    let actionInputPix = document.getElementById("inputPix");
    let actionInputBoleto = document.getElementById("inputBoleto");

    actionInputPix.onclick = () => {
        let removeCheckedLabel = document.querySelectorAll("#paymentGatewaysContainer .text-center label div");
        let removeCheckedDiv = document.querySelectorAll("#paymentGatewaysContainer .text-center label div input");

        removeCheckedLabel.forEach((element, i) => {
            element.classList.remove("checked");
        })
        removeCheckedDiv.forEach((element, i) => {
            element.removeAttribute("checked");
        })
        document.getElementById("divPix").classList.add("checked");
        document.getElementById("inputPix").setAttribute("checked", "");
        // document.getElementById("divBoleto").classList.remove("checked");
        //  document.getElementById("inputBoleto").removeAttribute("checked");

    }


    let removeCheckedLabelWhmcs = document.querySelectorAll("#paymentGatewaysContainer .text-center label");
    removeCheckedLabelWhmcs.forEach((element, i) => {
        if (element.id.indexOf("labelPix") == -1 && element.id.indexOf("labelBoleto") == -1) {
            element.onclick = () => {
                document.getElementById("divPix").classList.remove("checked");
                document.getElementById("inputPix").removeAttribute("checked");
                document.getElementById("divBoleto").classList.remove("checked");
                document.getElementById("inputBoleto").removeAttribute("checked");
            }
        }

    });
    let removeCheckedInsWhmcs = document.querySelectorAll("#paymentGatewaysContainer .text-center label div ins");
    removeCheckedInsWhmcs.forEach((element, i) => {
        if (i <= (removeCheckedInsWhmcs.length - 2)) {
            element.onclick = () => {
                document.getElementById("divPix").classList.remove("checked");
                document.getElementById("inputPix").removeAttribute("checked");
                document.getElementById("divBoleto").classList.remove("checked");
                document.getElementById("inputBoleto").removeAttribute("checked");
            }
        }

    });


    actionInputBoleto.onclick = () => {
        let removeCheckedLabel = document.querySelectorAll("#paymentGatewaysContainer .text-center label div");
        let removeCheckedDiv = document.querySelectorAll("#paymentGatewaysContainer .text-center label div input");

        removeCheckedLabel.forEach((element, i) => {
            element.classList.remove("checked");
        })
        removeCheckedDiv.forEach((element, i) => {
            element.removeAttribute("checked");
        })
        document.getElementById("divBoleto").classList.add("checked");
        document.getElementById("inputBoleto").setAttribute("checked", "");
    }


    document.getElementById("labelPix").addEventListener("mouseover", () => {
        document.getElementById("labelPix").classList.add("hover");
        document.getElementById("divPix").classList.add("hover");

    })
    document.getElementById("labelPix").addEventListener("mouseleave", () => {
        document.getElementById("labelPix").classList.remove("hover");
        document.getElementById("divPix").classList.remove("hover");

    })



    document.getElementById("labelBoleto").addEventListener("mouseover", () => {
        document.getElementById("labelBoleto").classList.add("hover");
        document.getElementById("divBoleto").classList.add("hover");

    })
    document.getElementById("labelBoleto").addEventListener("mouseleave", () => {
        document.getElementById("labelBoleto").classList.remove("hover");
        document.getElementById("divBoleto").classList.remove("hover");

    })

}