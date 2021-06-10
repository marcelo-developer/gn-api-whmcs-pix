window.onload = function() {
    let htmlPix = `<div class='optionPaymentGerencianet'>
    <div class='meuPopUp'>
        <button class='fechar' type='button'>X</button>
        <h3>Escolha o método de pagamento:</h3>
        <form enctype='multipart/form-data' class='formularios'>
            <label for=''>
                <input type='radio' name='optionPayment'>
                Pagar com PIX
            </label>
            <label for=''>
                <input type='radio' name='optionPayment'>
                Pagar com Boleto
            </label>
            <button class='button' id='buttonPayment' type='button'>Ok</button>
        </form>
    </div>
</div>
    `
    let folhaDeEstilo = `
    <style>
    .fechar {
        position: absolute !important;
        top: -30px !important;
        right: -30px !important;
        width: 50px !important;
        height: 50px !important;
        border-radius: 50% !important;
        border: 4px solid white !important;
        background-color: #f3722c !important;
        color: white !important;
        font-family: monospace !important;
        cursor: pointer !important;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .3) !important;
        font-size: 1.2em !important;
    }
    
    .optionPaymentGerencianet {
        width: 100vw !important;
        height: 100vh !important;
        position: fixed !important;
        top: 0px !important;
        left: 0px !important;
        background-color: rgba(0, 0, 0, .5) !important;
        z-index: 2000 !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }
    .mostrar {
        display: flex;
    }
    
    .meuPopUp {
        background-color: white !important;
        width: 30% !important;
        min-width: 300px !important;
        padding: 30px !important;
        border: 10px solid #f8961e !important;
        box-shadow: 0 0 0 10px white !important;
        position: relative !important;
        z-index: 200000 !important;
    }
    
    .meuPopUp form {
        display: flex !important;
        flex-direction: column !important;
    }
    
    .button {
        background-color: #f3722c !important;
        width: 100px !important;
        margin: auto !important;
        border: none !important;
        color: white !important;
        padding: 15px 32px !important;
        text-align: center !important;
        text-decoration: none !important;
        display: inline-block !important;
        font-size: 16px !important;
        cursor: pointer !important;
        -webkit-transition-duration: 0.4s !important;
        transition-duration: 0.4s !important;
        border-radius: 5px !important;
    }
    
    .button:hover {
        box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19) !important;
        background-color: #f8961e !important;
    }
    @keyframes modal {
        from {
            opacity: 0;
            transform: translate3d(0, -60px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    
    .mostrar .meuPopUp {
        animation: modal .6s;
    }
    </style>
    `
    setTimeout(() => {
        document.body.insertAdjacentHTML('beforeend', htmlPix);
        document.body.insertAdjacentHTML('beforeend', folhaDeEstilo);
        document.getElementsByClassName('optionPaymentGerencianet')[0].classList.add('mostrar')
        document.getElementById('buttonPayment').onclick = () => {
            let formulario = document.querySelector(".optionPaymentGerencianet .meuPopUp .formularios");
            console.log(formulario)
            let gatewayVariables = new FormData(formulario);

            const options = {
                method: 'POST',
                body: gatewayVariables
            }
            let baseUrl = document.getElementById('baseUrl').value;
            fetch(`${baseUrl}/modules/gateways/gerencianetpix/gerencianetpix_lib/routes/gerencianetpix.php`, options)
                .then((res) => {
                    console.log(res.json())
                })
                .catch(console.log)
        }
    }, 1000)

}