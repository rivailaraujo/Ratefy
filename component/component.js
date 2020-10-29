// configurar seus argumentos
var config = {
  key: 'ad5cf886d451ca1e2e92ed3862bab2aa',
  type: 1 // {1- five-stars, 2- like-dislike, 3-Slider,}
};


var trigger;
var closeButton;
var modal;



const apiURL = 'http://localhost/API-Reputacao/public/index.php/v1.0';

var headID = document.getElementsByTagName('head')[0];
var linkicons = document.createElement('link');
linkicons.type = 'text/css';
linkicons.rel = 'stylesheet';
linkicons.href = '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css';
headID.appendChild(linkicons);



var package = {
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
  <h1  >Deixe sua opinião!</h1>
  <div class="rating">
          <div class="stars">
              <form id = "form" action="">
                <input class="star star-5" id="star-5" type="radio" name="star" onClick="starvalor(5)"/>
                <label class="star star" for="star-5"></label>
                <input class="star star" id="star-4" type="radio" name="star" onClick="starvalor(4)"/>
                <label class="star star" for="star-4"></label>
                <input class="star star" id="star-3" type="radio" name="star" onClick="starvalor(3)"/>
                <label class="star star" for="star-3"></label>
                <input class="star star" id="star-2" type="radio" name="star" onClick="starvalor(2)"/>
                <label class="star star" for="star-2"></label>
                <input class="star star-1" id="star-1" type="radio" name="star" onClick="starvalor(1)"/>
                <label class="star star" for="star-1"></label>
              </form>
            </div>
  </div>
  <div style = "text-align: center; padding-top: 15px;">
  <button class="confirm" onClick="avaliar_item()">Confirmar</button>
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
            </div>
            <!-- Thumbs down -->
            <div class="dislike grow">
                <i class="fa fa-thumbs-down fa-3x dislike" aria-hidden="true" onClick="starvalor(0)"></i>
            </div>
        </div>
        <div style="text-align: center; padding-top: 15px;">
            <button class="confirm" onClick="avaliar_item()">Confirmar</button>
        </div>
    </div>
  
    `);

  } else if (config.type == 3) {
    document.getElementById('rate').innerHTML = '';
    console.log("ENTROU 3")
    return (`
    <div id='confirm' class="rate-component-modal-content">
        <span class="close-button">&times;</span>
        <h1>Deixe sua opinião!</h1>
                
                <div class = "ratingslider">
                <div><input type="range" class = "slider" name="ageInputName" id="ageInputId" value="50" min="1" max="100"  oninput="ageOutputId.value = ageInputId.value" onchange="updateTextInput(ageInputId.value)";>
                </div>
                <div style="padding-top: 10px;"><output name="ageOutputName" id="ageOutputId">50</output></div>
                                
                </div>
        <div style="text-align: center; padding-top: 15px;">
            <button class="confirm" onClick="avaliar_item()">Confirmar</button>
        </div>
    </div>
    
    
    `);
  }


}

function updateTextInput(val) {
  //document.getElementById('textInput').value=val; 
  console.log(val)
  if (val >= 70) {
    document.getElementById("ageInputId").className = "slider3";
    package.value = val;
  }else if (val >= 35 && val < 70){
    document.getElementById("ageInputId").className = "slider2";
    package.value = val;
  }else if (val < 35){
    document.getElementById("ageInputId").className = "slider1";
    package.value = val;
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
  config.type = type;
  package.item_id = itemId;
  document.getElementById('rate').innerHTML = '';

  console.log(config)
  modal = document.querySelector(".rate-component");
  renderModal(modal);



  //trigger = document.querySelector(".trigger");
  closeButton = document.querySelector(".close-button");
  //trigger.addEventListener("click", toggleModal);
  closeButton.addEventListener("click", toggleModal);
  window.addEventListener("click", windowOnClick);
  toggleModal();
}




function renderModal(element) {
  console.log("Entrou 2")
  const markup = createModal();
  element.innerHTML = markup;
};



function toggleModal() {

  if (document.getElementById('form')) {
    console.log("Entrou Form")
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
  console.log("AKi")
  if (config.type == 2) {
    if (id == 1) {
      document.querySelector(".like").classList.toggle("activelike");
      document.querySelector(".dislike").classList.remove("activedislike");
    } else if (id == 0) {
      document.querySelector(".dislike").classList.toggle("activedislike");
      document.querySelector(".like").classList.remove("activelike");
    }
  } else if (id == 3) {
      
  }
  // event.target is the element that is clicked (button in this case).
  console.log(id);
  package.value = id;
  //this.listarTodosProdutos();
  console.log(apiURL)
  //this.avaliar_item(id)
}

function listarTodosProdutos() {
  this.http.get(`${this.apiURL}/project/item/7de98fb468595368a4194ba5936852b2/1`)
    .subscribe(resultado => console.log(resultado));
}

function avaliar_item() {
  let date = new URLSearchParams({
    id_item: package.item_id,
    note: package.value,
    type: config.type,
    key: config.key
  });


  console.log(date.toString());

  var ajax = new XMLHttpRequest();
  //console.log(this.data.apiURL)
  // Seta tipo de requisição: Post e a URL da API
  ajax.open("POST", `${apiURL}/project/avaliarItem`, true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  //console.log(date)
  // Seta paramêtros da requisição e envia a requisição
  ajax.send(date);

  ajax.onreadystatechange = function () {
    // Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
    if (ajax.readyState == 4 && ajax.status == 200) {
      var data = ajax.responseText;
      // Retorno do Ajax
      console.log(data);
      document.getElementById("confirm").style.width = "12rem";
      document.getElementById("confirm").innerHTML = `
      
      <div class="modal-image">
      <svg viewBox="0 0 32 32" style="fill:#48DB71"><path d="M1 14 L5 10 L13 18 L27 4 L31 8 L13 26 z"></path></svg>
      </div>
    <h1 style = "padding-left: 0px !important;">Obrigado!</h1>
    <p style="text-align: center" >Sua avaliação é importante</p>

      `;
      //toggleModal();

    }
  }
}

