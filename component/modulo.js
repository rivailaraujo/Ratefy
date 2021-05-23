// configurar seus argumentos
var config = {
  key: '2aad1de6b37f7e9553d3fe9caa0c066b',
  type: 1 // {1- five-stars, 2- like-dislike, 3-Slider,}
};


var closeButton;
var modal;
var retorno;


const apiURL = 'http://localhost/API-Reputacao/public/index.php/v1';

var headID = document.getElementsByTagName('head')[0];
var linkicons = document.createElement('link');
linkicons.type = 'text/css';
linkicons.rel = 'stylesheet';
linkicons.href = '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css';
headID.appendChild(linkicons);

let div = document.createElement('div');
div.className = 'rate-component';
div.id = 'rate';
document.body.appendChild(div)

var dados = {
  value: 0,
  item_id: 0
}





function createModal() {
  

  // var headID = document.getElementsByTagName('head')[0];
  // var linkstyle = document.createElement('link');
  // linkstyle.type = 'text/css';
  // linkstyle.rel = 'stylesheet';
  // linkstyle.href = 'component.css';
  // headID.appendChild(linkstyle);

  if (config.type == 1) {
    document.getElementById('rate').innerHTML = '';
    return (`
  <div id = 'confirm' class="rate-component-modal-content">
  <span class="close-button">&times;</span>
  <h1>Deixe sua opinião!</h1>
  <div class = "div-rate-status">
  ${retorno.media}
  <span id="star-media-1" class="fa fa-star"></span>
  <span id="star-media-2" class="fa fa-star"></span>
  <span id="star-media-3" class="fa fa-star"></span>
  <span id="star-media-4" class="fa fa-star"></span>
  <span id="star-media-5" class="fa fa-star"></span>
  (${retorno.qtd_total})
  </div>

   <div class="rating">
          <div class="stars">
              <form id = "form" action="">
                <input class="star star-5" id="star-5" type="radio" name="star" onclick="starvalor(5)"/>
                <label class="star star" for="star-5"></label>
                <input class="star star" id="star-4" type="radio" name="star" onclick="starvalor(4)"/>
                <label class="star star" for="star-4"></label>
                <input class="star star" id="star-3" type="radio" name="star" onclick="starvalor(3)"/>
                <label class="star star" for="star-3"></label>
                <input class="star star" id="star-2" type="radio" name="star" onclick="starvalor(2)"/>
                <label class="star star" for="star-2"></label>
                <input class="star star-1" id="star-1" type="radio" name="star" onclick="starvalor(1)"/>
                <label class="star star" for="star-1"></label>
              </form>
            </div>
  </div>
  <div style = "text-align: center; padding-top: 15px;">
  <button class="confirm" onclick="rate_item()">Confirmar</button>
  </div>
</div>

  `);
  } else if (config.type == 2) {
    document.getElementById('rate').innerHTML = '';
    console.log("ENTROU 2")
    return (`
    <div id='confirm' class="rate-component-modal-content">
        <span class="close-button">&times;</span>
        <h1>Deixe sua opinião!</h1>
        <div class="ratinglikedislike">
            <div class="like grow">
                <i class="fa fa-thumbs-up fa-3x like" aria-hidden="true" onClick="starvalor(1)"></i>
                <div id = "rate-like" class="column">${retorno.detalhes_nota.qtd_like}</div>
            </div>
            <!-- Thumbs down -->
            <div class="dislike grow">
                <i class="fa fa-thumbs-down fa-3x dislike" aria-hidden="true" onClick="starvalor(0)"></i>
                <div id = "rate-dislike" class="column">${retorno.detalhes_nota.qtd_dislike}</div>
            </div>
        </div>
        <div style="text-align: center; padding-top: 15px;">
            <button class="confirm" onClick="rate_item()">Confirmar</button>
        </div>
    </div>
  
    `);

  } else if (config.type == 3) {
    document.getElementById('rate').innerHTML = '';
    console.log("ENTROU 3")
    dados.value = 50;
    return (`
    <div id='confirm' class="rate-component-modal-content">
        <span class="close-button">&times;</span>
        <h1>Deixe sua opinião!</h1>
                
                <div class = "ratingslider">
                <div><input type="range" class = "slider" name="ageInputName" id="ageInputId" value="50" min="1" max="100"  oninput="ageOutputId.value = ageInputId.value" onchange="updadosTextInput(ageInputId.value)";>
                </div>
                <div style="padding-top: 10px;"><output name="ageOutputName" id="ageOutputId">50</output></div>
                                
                </div>
        <div style="text-align: center; padding-top: 15px;">
            <button class="confirm" onClick="rate_item()">Confirmar</button>
        </div>
    </div>
    
    
    `);
  }


}

function updadosTextInput(val) {
  //document.getElementById('textInput').value=val; 
  console.log(val)
  if (val >= 70) {
    document.getElementById("ageInputId").className = "slider3";
    dados.value = val;
  }else if (val >= 35 && val < 70){
    document.getElementById("ageInputId").className = "slider2";
    dados.value = val;
  }else if (val < 35){
    document.getElementById("ageInputId").className = "slider1";
    dados.value = val;
  }
}

function createModalConfirm() {

  // var headID = document.getElementsByTagName('head')[0];
  // var linkstyle = document.createElement('link');
  // linkstyle.type = 'text/css';
  // linkstyle.rel = 'stylesheet';
  // linkstyle.href = 'component.css';
  // headID.appendChild(linkstyle);
  return (`
  <div class="rate-component-modal-content">
  <span class="close-button">&times;</span>

  <div class="thank-you-pop">
							<img src="http://goactionstations.co.uk/wp-content/uploads/2017/03/Green-Round-Tick.png" alt="">
							<h1>Thank You!</h1>
							<p>Your submission is received and we will contact you soon</p>
							<h3 class="cupon-pop">Your Id: <span>12345</span></h3>
							
 						</div>

</div>

  `);
}

function rate(itemId, type) {
  console.log("RATE");
  config.type = type;
  dados.item_id = itemId;
  let data = new URLSearchParams({
    id_item: dados.item_id,
    note: dados.value,
    type: config.type,
    tipo_item: 'aplicativo'
  });


  //console.log(data.toString());
 // var response;
  return new Promise(function(resolve, reject) {
    var ajax = new XMLHttpRequest();
  //console.log(this.data.apiURL)
  // Seta tipo de requisição: Post e a URL da API
  ajax.open("GET", `${apiURL}/items/`+itemId+`/`+type, true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  ajax.setRequestHeader("Api_key", config.key);  
  //console.log(dados)
  // Seta paramêtros da requisição e envia a requisição
  ajax.send(data);

  ajax.onreadystatechange =  function () {
    // Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
    if (ajax.readyState == 4 && ajax.status == 200) {
      //console.log(JSON.parse(ajax.response))
      retorno = JSON.parse(ajax.response);
      //console.log(retorno)
      //resolve(JSON.parse(ajax.response));
      document.getElementById('rate').innerHTML = '';
      //console.log(config)
      modal = document.querySelector(".rate-component");
      renderModal(modal);

    //trigger = document.querySelector(".trigger");
    closeButton = document.querySelector(".close-button");
    //trigger.addEventListener("click", toggleModal);
    closeButton.addEventListener("click", toggleModal);
    window.addEventListener("click", windowOnClick);
    toggleModal();
      //toggleModal();
      //retorno = data;
      //callback(JSON.parse(ajax.responseText)); 
    }else if (ajax.readyState == 4 && ajax.status != 200){
      //console.log("TESTE")
      if (type == 1){
        retorno = {
          qtd_total: 0,
          media: 0
        }
      }else if (type == 2){
        retorno = {
          detalhes_nota: {
            qtd_like: 0,
            qtd_dislike: 0
          }
        }
      }
      //retorno = JSON.parse(ajax.response);
      //console.log(retorno)
      //resolve(JSON.parse(ajax.response));
      document.getElementById('rate').innerHTML = '';
      //console.log(config)
      modal = document.querySelector(".rate-component");
      renderModal(modal);

    //trigger = document.querySelector(".trigger");
    closeButton = document.querySelector(".close-button");
    //trigger.addEventListener("click", toggleModal);
    closeButton.addEventListener("click", toggleModal);
    window.addEventListener("click", windowOnClick);
    toggleModal();
      //toggleModal();
      //retorno = data;
      //callback(JSON.parse(ajax.responseText)); 
    }
    
  }
  });
  
}




function renderModal(element) {
  //console.log("Entrou 2")
  const markup = createModal();
  element.innerHTML = markup;
  if(config.type == 1){
    if(retorno.media >= 1 && retorno.media < 2){
      document.getElementById('star-media-1').classList.add("checked");
    }else if(retorno.media >= 2 && retorno.media < 3){
      document.getElementById('star-media-1').classList.add("checked");
      document.getElementById('star-media-2').classList.add("checked");
    }
    else if(retorno.media >= 3 && retorno.media < 4){
      document.getElementById('star-media-1').classList.add("checked");
      document.getElementById('star-media-2').classList.add("checked");
      document.getElementById('star-media-3').classList.add("checked");
    }else if(retorno.media >= 4 && retorno.media < 5){
      document.getElementById('star-media-1').classList.add("checked");
      document.getElementById('star-media-2').classList.add("checked");
      document.getElementById('star-media-3').classList.add("checked");
      document.getElementById('star-media-4').classList.add("checked");
    }else if(retorno.media == 5){
      document.getElementById('star-media-1').classList.add("checked");
      document.getElementById('star-media-2').classList.add("checked");
      document.getElementById('star-media-3').classList.add("checked");
      document.getElementById('star-media-4').classList.add("checked");
      document.getElementById('star-media-5').classList.add("checked");
    }
  }
};



function toggleModal() {
  if (document.getElementById('form')) {
    retorno = {
      media: 0,
      qtd_total: 0,
      detalhes_nota: {
        qtd_like: 0,
        qtd_dislike: 0
      }
    };
    document.getElementById('form').reset();
  }
  modal.classList.toggle("show-modal");
}

function windowOnClick(event) {
  if (event.target === modal) {
    toggleModal();
  }
}



function starvalor(id) {
  //console.log("AKi")
  if (config.type == 2) {
    if (id == 1) {
      document.getElementById("rate-dislike").innerHTML = Number(retorno.detalhes_nota.qtd_dislike);
      document.querySelector(".like").classList.toggle("activelike");
      document.querySelector(".dislike").classList.remove("activedislike");
      //document.getElementById("rate-like").innerHTML = '';
      document.getElementById("rate-like").innerHTML = Number(retorno.detalhes_nota.qtd_like) + 1;
    } else if (id == 0) {
      document.getElementById("rate-like").innerHTML = Number(retorno.detalhes_nota.qtd_like);
      document.querySelector(".dislike").classList.toggle("activedislike");
      document.querySelector(".like").classList.remove("activelike");
      document.getElementById("rate-dislike").innerHTML = Number(retorno.detalhes_nota.qtd_dislike) + 1;
    }
  } else if (id == 3) {
      
  }
  // event.target is the element that is clicked (button in this case).
  //console.log(id);
  dados.value = id;
  //this.listarTodosProdutos();
  //console.log(apiURL)
  //this.rate_item(id)
}

function listarTodosProdutos() {
  this.http.get(`${this.apiURL}/project/item/7de98fb468595368a4194ba5936852b2/1`)
    .subscribe(resultado => console.log(resultado));
}

function rate_item() {
  console.log("RATE ITEM");
  let data = new URLSearchParams({
    id_item: dados.item_id,
    note: dados.value,
    type: config.type,
    tipo_item: 'aplicativo'
  });


  console.log(data.toString());
 // var response;
  return new Promise(function(resolve, reject) {
    var ajax = new XMLHttpRequest();
  //console.log(this.data.apiURL)
  // Seta tipo de requisição: Post e a URL da API
  ajax.open("POST", `${apiURL}/ratings`, true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  ajax.setRequestHeader("Api_key", config.key);  
  //console.log(dados)
  // Seta paramêtros da requisição e envia a requisição
  ajax.send(data);

  ajax.onreadystatechange =  function () {
    // Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
    if (ajax.readyState == 4 && ajax.status == 200) {
      //var data = JSON.parse(ajax.responseType(json)) ;
      //response = JSON.parse(ajax.response);
      // Retorno do Ajax
     // console.log(response);

      document.getElementById("confirm").style.width = "12rem";
      document.getElementById("confirm").innerHTML = `
      
      <div class="modal-image">
      <svg viewBox="0 0 32 32" style="fill:#48DB71"><path d="M1 14 L5 10 L13 18 L27 4 L31 8 L13 26 z"></path></svg>
      </div>
    <h1 style = "padding-left: 0px !important;">Obrigado!</h1>
    <p style="text-align: center" >Sua avaliação é importante</p>

      `;
      resolve(JSON.parse(ajax.response));
      //toggleModal();
      //retorno = data;
      //callback(JSON.parse(ajax.responseText)); 
    }
    
  }
  });
}
  


//export {rate};