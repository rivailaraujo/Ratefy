# SysReput

SysReput é um sistema de avaliação portátil compativel com qualquer aplicação web.


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


## Usando o componente

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


## Construído com

* [CodeIgniter](https://codeigniter.com/) - Web Framework
* [Slim](https://www.slimframework.com/) - PHP micro framework para API


## Autores

* **Rivail Araújo** - *Estudante de Ciência da Computação* - [Rivail](https://gitlab.com/Rivail)


## Licença

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details


