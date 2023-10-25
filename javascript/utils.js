function go_Page(page)
{
	window.location = page;
}

function submit_Action(id,action,desc,msg,action_form)
{
  	var doit = true;
  	
	if (action == 'del')
		doit = confirm(msg + ': ' + id + ' -  ' + desc + '?');
  
	if (doit)
	{	
      document.frm_send_data.haction.value = action;
	  document.frm_send_data.hid.value = id;
	  document.frm_send_data.action = action_form;
	  document.frm_send_data.submit();
	}
	
  return false;
}

function submit_Action2(id,id1, action, desc,msg,action_form){
  var doit = true;
  if (action == 'del')
	  doit = confirm(msg + ': ' + id + ' -  ' + desc + '?');
  
	if (doit){	
      document.frm_send_data.haction.value = action;
	  document.frm_send_data.hid.value = id;
	  document.frm_send_data.hid1.value = id1;
	  document.frm_send_data.action = action_form;
	  document.frm_send_data.submit();
	}
	
  return false;
}

//Antes de qualquer a��o checa se o cadeado esta fechado 
function submit_Action_Check(id,action,desc,msg,action_form,msg_alert)
{
  var clique = document.getElementById(id+"sit");
  var cont= 0;
  
  for (var i = 0; i < clique.childNodes.length; i++) 
  	if(clique.childNodes[i].nodeName.toLowerCase() == "a")
		cont = cont + 1;
	
  if(cont == 2)
  {
  
  	submit_Action(id,action,desc,msg,action_form);
	return false;
  }
  else
  {
  	alert(msg_alert);
	return false;
  }
  
}

function submit_Action_Check1(id, action, desc,msg,action_form,msg_alert){
	
  var clique = document.getElementById(id+"sit");
  var cont= 0;
  
  for (var i = 0; i < clique.childNodes.length; i++) 
	{
    	if(clique.childNodes[i].nodeName.toLowerCase() == "a")
			cont = cont + 1;
	}
  
  if(cont != 2)
  {
  	submit_Action(id,action,desc,msg,action_form);
  }
  else
  {
  	alert(msg_alert);
	return false;
  }
}

function altera_medida_produto(id)
{

	var medida_produto = document.getElementById("medida_aux"+id).value;
	
	var url_search = "altera_medida_produto.php";
	
	var url = url_search + "?hid="+escape(medida_produto);
	
	request.open("GET", url,true);
	
	request.onreadystatechange=function() 
	{
		if (request.readyState==4)
		{
			
			if (request.status == 200) 
			{
				alert(request.responseText);
			}
		}
	}
	
	request.send(null);
			
}


function pop_select(url_search,value_zero)
{
	
	var flag = false;
	var cidade = document.getElementById("cidade");
	
	var cidade_id = cidade.value;
	
	var c = document.getElementById("select_pop");
	
	if(cidade_id != 0)
	{
		//c.setAttribute("class",null);
		//c.style.cssText = "";
		c.className = '';
		c.disabled = null;

	
		//Monta a url com a cidade
		url = url_search + '?hid='+escape(cidade_id);
		request.open("GET", url,true);
	
		request.onreadystatechange=function() 
		{
			if (request.readyState==4)
			{
			
		
				while(c.options.length>0)
					c.options[0]=null;
				
				c.options[0]=new Option(" ---------   Carregando ...    --------- "," ---------   Carregando ...    --------- ");
				 
	
				
				//Transforma a lista de cidades JSON em Javascript
				try
				{
					
					 var jsonData = eval('(' + request.responseText + ')');
					 //alert(jsonData);
					 
					  while(c.options.length>0)
						c.options[0]=null;
						
					 c.options[0] = new Option(value_zero, 0);
					 
					//alert(jsonData);
					//popula o select com a lista de cidades obtida
					for(var i=0;i<(jsonData.length)/2;i++)
						c.options[c.length]=new Option(jsonData[(2*i)+1],jsonData[(2*i)]);
				}
				catch(ex)
				{
					  while(c.options.length>0)
						c.options[0]=null;
						
					c.setAttribute("class","select_desativado");
					c.setAttribute("disabled","disabled");
						
					 c.options[0] = new Option(value_zero, 0);
					 //alert(ex);
					 alert('Sem estabelecimentos cadastros nesta cidade!');
				}
			}
		}

    request.send(null);
	
	}
	else
	{
		c.setAttribute("class","select_desativado");
		c.setAttribute("disabled","disabled");
		
		while(c.options.length>0)
			c.options[0]=null;
			
		c.options[0] = new Option(value_zero, 0);
		
		
	}
}

function pop_select2()
{
	
	var flag = false;
	var cidade = document.getElementById("cidade");
	
	var cidade_id = cidade.value;
	var url_search = 'procura_estabelecimentos.php';
	var c = document.getElementById("select_pop");
	
	if(cidade_id != 0)
	{
		//c.setAttribute("class",null);
		//c.style.cssText = "";
		c.className = '';
		c.disabled = null;

	
		//Monta a url com a cidade
		url = url_search + '?hid='+escape(cidade_id);
		request.open("GET", url,true);
	
		request.onreadystatechange=function() 
		{
			if (request.readyState==4)
			{
			
		
				while(c.options.length>0)
					c.options[0]=null;
				
				c.options[0]=new Option(" ---------   Carregando ...    --------- "," ---------   Carregando ...    --------- ");
				 
	
				
				//Transforma a lista de cidades JSON em Javascript
				try
				{
					
					 var jsonData = eval('(' + request.responseText + ')');
					 //alert(jsonData);
					 
					  while(c.options.length>0)
						c.options[0]=null;
						
					 
					//alert(jsonData);
					//popula o select com a lista de cidades obtida
					for(var i=0;i<(jsonData.length)/2;i++)
						c.options[c.length]=new Option(jsonData[(2*i)+1],jsonData[(2*i)]);
				}
				catch(ex)
				{
					  while(c.options.length>0)
						c.options[0]=null;
						
					c.setAttribute("class","select_desativado");
					c.setAttribute("disabled","disabled");
						
					 //alert(ex);
					 alert('Sem estabelecimentos cadastros nesta cidade!');
				}
			}
		}

    request.send(null);
	
	}
}

function muda_cadeado(flag,id,url,msg_alert)
{	

	var bool = false;
	var msg = '';
	
	if(flag == 0)
		msg = 'Deseja abrir';
	else
		msg = 'Deseja fechar';
		
	msg = msg + msg_alert;
	
	bool = confirm(msg);
	var aux = '';
	
	
	if(bool)
	{
		
		var req = cria_request();
		
		var aux = url + '?situacao='+ escape(flag)+"&hid="+escape(id);
		req.open("GET",aux,true);
		
		req.onreadystatechange=function(){
			if (req.readyState==4)
			{
					if (req.status == 200) 
					{
					   
					   
						   var resposta = req.responseText;
						   //alert(resposta);
						   
						   if(resposta == '')
						   {
							   var campo_editar = document.getElementById(id+"edit");
							   
							   
							   //for(var i =0 ;i<campo_editar.childNodes.length;i++);
							   var imagem_editar = document.getElementById(id+"edit").firstChild;
							   
							   //alert(imagem_editar.src);
							   
							   var campo_del = document.getElementById(id+"del");
							   var imagem_del = campo_del.firstChild;
							   
							   var campo_carrinho = document.getElementById(id+"car");
							   var imagem_carrinho = campo_carrinho.firstChild;
							   
							   var campo_visualizar = document.getElementById(id+"vis");
							   var imagem_visualizar = campo_visualizar.firstChild;
							   
							   var situacao = document.getElementById(id+"sit");
							   
							   var a_link = document.createElement("a");
							   a_link.setAttribute("id","flag");
							   
							   limpa_nodes_filhos(situacao);
							   
						  
							   var node = '';
				
							   if(flag == 1)
							   {   
								   imagem_editar.setAttribute("src","images/botao_editar_apagado.png");
								   imagem_del.setAttribute("src","images/botao_deletar_apagado.png");
								   imagem_carrinho.setAttribute("src","images/carrinho_apagado.png");
								   imagem_visualizar.setAttribute("src","images/imprimir.png");
								   node = '<a href="javascript: void(0)" onClick="muda_cadeado('+0+','+id+',\''+url+'\',\''+msg_alert+'\')"><img src="images/padlock.gif" border="0"></a>';
				
							   }
							   else
									if(flag ==0)
									{
										imagem_editar.setAttribute("src","images/botao_editar.png");
										imagem_del.setAttribute("src","images/botao_deletar.png");
										imagem_carrinho.setAttribute("src","images/carrinho.gif");
										imagem_visualizar.setAttribute("src","images/imprimir_apagado.png");
										node = '<a href="javascript: void(0)" onClick="muda_cadeado('+1+','+id+',\''+url+'\',\''+msg_alert+'\')"><img src="images/padlock_unlocked.gif" border="0"></a></a><a id="flag"></a>';
										
									}
							
							
							situacao.innerHTML = node;
					}
					else
						alert(resposta);
				
					}//do req readyState
				
				}//do req statusdo req readyState
			
			}//da funcao
			
			req.send(null);
			
	}//do if do bool
	
	
}


function limpa_nodes_filhos(el) {
  if (el != null) {
    if (el.childNodes) {
      for (var i = 0; i < el.childNodes.length; i++) {
        var childNode = el.childNodes[i];
        el.removeChild(childNode);
      }
    }
  }
}


function mascara(o,f){
v_obj=o;
v_fun=f;

setTimeout("execmascara()",1)
}

function execmascara(){
v_obj.value=v_fun(v_obj.value)
}

function telefone(v)
{
	v=v.replace(/\D/g,"") //Remove tudo o que n�o � d�gito
	v=v.replace(/^(\d\d)(\d)/g,"($1) $2") //Coloca par�nteses em volta dos dois primeiros d�gitos
	v=v.replace(/(\d{4})(\d)/,"$1-$2") //Coloca h�fen entre o quarto e o quinto d�gitos
	return v
}

function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca uma barra entre o segundo e o terceiro d�gitos
    //v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca uma barra entre o quarto e o quinto d�gitos
                                             //de novo (para o segundo bloco de n�meros)

    return v
}

function data_completa(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca uma barra entre o segundo e o terceiro d�gitos
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca uma barra entre o quarto e o quinto d�gitos
                                             //de novo (para o segundo bloco de n�meros)

    return v
}

// campo com 4  e 5 numeros decimais e duas casas decimais depois da v�rgula;
function soNumeros(v){
	
	v = v.replace(/\D/g,"");
	var lim = 4,qt_decimais = 2,tam = v.length;
	var sep = '.';
	
	v = formata_numeros(v,tam,lim,qt_decimais,sep);
	
    return v;	
}

function soNumeros1(v){
	
	v = v.replace(/\D/g,"");
	var lim = 5,qt_decimais = 2,tam = v.length;
	var sep = '.';
	
	v = formata_numeros(v,tam,lim,qt_decimais,sep);
	
    return v;	
}

function soNumerosDSD(v){
	
	v = v.replace(/\D/g,"");
	var lim = 6,qt_decimais = 2,tam = v.length;
	var sep = '.';
	
    v = formata_numeros(v,tam,lim,qt_decimais,sep);
	
    return v;	
}

function soNumerosDSD1(v){
	
	v = v.replace(/\D/g,"");
	var lim = 6,qt_decimais = 1,tam = v.length;
	var sep = '.';
	
    v = formata_numeros(v,tam,lim,qt_decimais,sep);
	
    return v;	
}

//formata 3 casas decimais depois da virgula
function soNumeros2(v){
	
	v = v.replace(/\D/g,"");
	var lim = 5,qt_decimais = 3,tam = v.length;
	var sep = '.';
	
	v = formata_numeros(v,tam,lim,qt_decimais,sep);
	
    return v;	
}

function formata_numeros(v,tam,lim,qt_decimais,sep)
{
	if(tam<=lim)
		if(tam>qt_decimais)
		{
			var aux = tam-qt_decimais;
			v = v.substring(0,aux) + sep + v.substring(aux,tam);
		}
	
	return v;
}


function Limpar(valor, validos) {
// retira caracteres invalidos da string
var result = "";
var aux;
for (var i=0; i < valor.length; i++) {
aux = validos.indexOf(valor.substring(i, i+1));
if (aux>=0) {
result += aux;
}
}
return result;
}

//Formata n�mero tipo moeda usando o evento onKeyDown

function Formata(campo,tammax,teclapres,decimal) {
var tecla = teclapres.keyCode;
vr = Limpar(campo.value,"0123456789");
tam = vr.length;
dec=decimal

if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

if (tecla == 8 )
{ tam = tam - 1 ; }

if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 )
{

if ( tam <= dec )
{ campo.value = vr ; }

if ( (tam > dec) && (tam <= 5) ){
campo.value = vr.substr( 0, tam - 2 ) + "." + vr.substr( tam - dec, tam ) ; }
if ( (tam >= 6) && (tam <= 8) ){
campo.value = vr.substr( 0, tam - 5 ) + "." + vr.substr( tam - 5, 3 ) + "." + vr.substr( tam - dec, tam ) ; 
}

}
}


/*
//formata os numeros com casas decimais
function formata_decimais(fld,sep,lim,qt,e) 
{

	var strCheck = '0123456789';
	var str_aux = fld.value;
	var str = '';
	var aux;
	var key,len,tam,i;
	var flag = true;
	//var whichCode = (window.Event) ? e.which : e.keyCode;
	var whichCode = e.keyCode;
	
	if (whichCode == 13) 
		return true;  // Enter

	if(whichCode == 8)
	{
		aux = str_aux.indexOf(sep);
		len = str_aux.length;
		
		if(aux != -1)
			str_aux = str_aux.substring(0,aux) + str_aux.substring(aux+1,len);

		len = str_aux.length;
		
		str_aux = str_aux.substring(0,len-1);
		
		flag = false;
		
	}
		
	if(flag)
		key = String.fromCharCode(whichCode);  // Get key value from key code
	else
		key = '';
		
	if ((strCheck.indexOf(key) == -1) && flag) 
		return false;  // Not a valid key
	
	len = str_aux.length;

	for(i = 0; i < len; i++)
		if ((str_aux.charAt(i) != '0') && (str_aux.charAt(i) != sep)) 
		break;
		
	for(; i < len; i++)
		if (strCheck.indexOf(str_aux.charAt(i))!=-1)
			str += str_aux.charAt(i);
	
	str += key;
	
	tam = str.length;
	
	
	if(tam<lim)
	{
	
		if(tam<=qt)
		{
			str_aux = '0' + sep;
			
			for(i = 0 ; i<(qt-tam) ; i++)
			{
				str_aux += '0';
			} 
			
			
			str = str_aux + str;
		}
		else
		{
			str_aux = str.substring(0,tam-qt) + sep + str.substring(tam-qt,tam);
			str = str_aux;
			
		}
		
		fld.value = str;
	}
	
	
	return false;
	
	
}
*/
