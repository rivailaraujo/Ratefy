# SysReput

SysReput é um sistema de avaliação portátil compativel com qualquer aplicação web.

## Getting Started

***TODO***
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
Give examples
```

## Instalação


Certifique-se de baixar os arquivos `component.js` e `component.css` em [releases](https://gitlab.com/Rivail/sysreput/-/releases).

Acesse o site do [sysreput](#), crie uma conta e faça seu login.
Adicione um projeto para gerar uma chave única. Salve-a.

Copie a chave de sua escolha e substitua o campo `key` na váriavel `config` em `component.js` pela sua chave.

```js
var config = {
  key: 'Your_Key',
  (...)
};

``` 
### Instalação Web
Coloque-os em seu projeto web e adicione as linhas a seguir em sua página html:

```
<script src="component.js"></script>
<link rel="stylesheet" href="component.css">
```

Na página html de sua escolha, adicione no `body` de sua página a seguinte linha:

```
<div id="rate" class="rate-component"></div>
```
### Instalação Ionic/Angular

Adicione o `component.js` em seu `index.html` da pasta `src` do projeto.

```
<script type="text/javascript" src="./assets/js/component.js" charset="UTF-8"></script>
```
Adicione também o `component.css` em `global.scss` na pasta `src`

```
@import "./assets/css/component.css";
```
**Você não é obrigado a adiconar os arquivos na pasta assets do projeto ionic. Essa é apenas uma forma de organização.**


## Usando o Componente

Na página html de sua escolha, adicione no `body` de sua página a seguinte linha:

```
<div id="rate" class="rate-component"></div>
```

Agora você pode chamar a função `rate()` em qualquer evento para acionar o component.
Por exemplo um click em um `button`:

```
<button onClick="rate(1, 3)">Button</button>
```
A função `rate()` é declarada da seguinte forma:

``` js
function rate(itemId, type)
```
- `itemId` refere-se ao id do item avaliado de sua escolha.
- `type` refere-se ao tipo de avaliação. (1- Five-Stars, 2- Thumbs, 3- Slider)



End with an example of getting some data out of the system or using it for a little demo

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - The web framework used
* [Maven](https://maven.apache.org/) - Dependency Management
* [ROME](https://rometools.github.io/rome/) - Used to generate RSS Feeds

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Billie Thompson** - *Initial work* - [PurpleBooth](https://github.com/PurpleBooth)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc

